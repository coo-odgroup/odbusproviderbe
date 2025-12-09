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
  
    public function handle()
    {
        $amount = $this->amount;
        $orderId = $this->orderId;
        $customerId = $this->customerId;

        $amountInPaise = $amount * 100;

        $merchantRefundId = "REFUND_" . time();

        $payload = [
            "merchantRefundId" => $merchantRefundId,
            "originalMerchantOrderId" => $orderId, // TX123456
            "amount" => $amountInPaise
        ];

        $phonpe_url = Config('constants.PHONPE_API_URL');
        $url = $phonpe_url . "payments/v2/refund";

        $getToken = PhonePayToken::first();

        // Make API call
        $resp = Http::withHeaders([
            'Authorization' => $getToken->token_type . " " . $getToken->access_token,
            'Content-Type' => 'application/json'
        ])->post($url, $payload);

        // Convert to array safely
        $rfJsonResp = $resp->json() ?? [];

        // Log response for debugging (optional)
        Log::info("PhonePe Refund Response:", $rfJsonResp);

        // Check refundId exists and is not null
        if (isset($rfJsonResp['refundId']) && !empty($rfJsonResp['refundId'])) {
            CustomerPayment::where('id', $customerId)->update([
                'payment_done' => 2,
                'refund_mode' => 2,
                'refund_id' => $rfJsonResp['refundId']
            ]);
        }

        return $rfJsonResp;
    }
}
