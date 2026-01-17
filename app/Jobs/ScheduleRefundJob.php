<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\PhonePayToken;
use App\Models\CustomerPayment;

class ScheduleRefundJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $amount;
    public $orderId;
    public $customerId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($amount, $orderId, $customerId)
    {
       
        $this->amount = $amount;
        $this->orderId = $orderId;
        $this->customerId = $customerId;
    }
  
    // public function handle()
    // {
    //     Log::info("Handel Working");
    //     $amount = $this->amount;
    //     $orderId = $this->orderId;
    //     $customerId = $this->customerId;

    //     $amountInPaise = $amount * 100;

    //     $merchantRefundId = "REFUND_" . time();

    //     $payload = [
    //         "merchantRefundId" => $merchantRefundId,
    //         "originalMerchantOrderId" => $orderId, // TX123456
    //         "amount" => $amountInPaise
    //     ];

    //     // $phonpe_url = Config('constants.PHONPE_API_URL');
    //     // $url = $phonpe_url . "payments/v2/refund";
    //     $url = "https://api-preprod.phonepe.com/apis/pg-sandbox/payments/v2/refund";

    //     $getToken = PhonePayToken::first();

    //     // Log::info("PhonePe Token bellow:");
    //     // Log::info($getToken);

    //     // Make API call
    //     $resp = Http::withHeaders([
    //         'Authorization' => $getToken->token_type . " " . $getToken->access_token,
    //         'Content-Type' => 'application/json'
    //     ])->post($url, $payload);

    //     Log::info("Response bellow:");
    //     Log::info($resp);

    //     // Convert to array safely
    //     $rfJsonResp = $resp->json() ?? [];

    //     // Log response for debugging (optional)
    //     Log::info("PhonePe Refund Response New:", $rfJsonResp);

    //     // Check refundId exists and is not null
    //     if (isset($rfJsonResp['refundId']) && !empty($rfJsonResp['refundId'])) {
    //         CustomerPayment::where('id', $customerId)->update([
    //             'payment_done' => 2,
    //             'refund_mode' => 2,
    //             'refund_id' => $rfJsonResp['refundId']
    //         ]);
    //     }

    //     return $rfJsonResp;
    // }

    public function handle()
    {
        Log::info("Refund process started");

        $amount = $this->amount;
        $orderId = $this->orderId;
        $customerId = $this->customerId;

        // Convert amount to paise
        $amountInPaise = $amount * 100;

        // Unique merchant refund ID
        $merchantRefundId = "REFUND_" . time();

        $payload = [
            "merchantRefundId" => $merchantRefundId,
            "originalMerchantOrderId" => $orderId,
            "amount" => $amountInPaise
        ];

        $url = config('constants.PHONPE_API_URL')."payments/v2/refund";

        try {
            // Get saved token from database
            try {
                $getToken = PhonePayToken::first();
            }  catch (\Exception $e) {
                Log::error("PhonePe Token API Error: " . $e->getMessage());
                return ['error' => $e->getMessage()];
            }

            // Make API call
            $response = Http::withHeaders([
                'Authorization' => $getToken->token_type . " " . $getToken->access_token,
                'Content-Type' => 'application/json'
            ])->post($url, $payload);

            $rfJsonResp = $response->json() ?? [];

            if (empty($rfJsonResp)) {
                Log::warning("PhonePe refund response is empty. Full response: ", [
                    'body' => $response->body(),
                    'status' => $response->status()
                ]);
            }

            $state = strtoupper($rfJsonResp['state'] ?? '');

            $refundStatus = config('constants.REFUND_STATUS_CODE')[$state] ?? 0;

            // Log::info($refundStatus);

            // if ($rfJsonResp['code']=="BAD_REQUEST") {
            //     Log::warning($rfJsonResp['message']);
            // }

            // Check if refundId exists
            if (!empty($rfJsonResp['refundId'])) {
                CustomerPayment::where('id', $customerId)->update([
                    'payment_done' => 2,
                    'refund_mode' => 2,
                    'refund_id' => $rfJsonResp['refundId'],
                    'refund_status' => $refundStatus
                ]);
                Log::info("Refund updated in database successfully");
            } else {
                Log::warning("Refund not successful or refundId missing");
            }

            return $rfJsonResp;

        } catch (\Exception $e) {
            Log::error("PhonePe Refund API Error: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}