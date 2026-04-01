<?php

namespace App\Console\Commands;

use App\Models\CustomerPayment;
use App\Models\CustomerPaymentLog;
use App\Models\PhonePayToken;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PhonePeRefundStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phonepe:refund-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update PhonePe refund status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $payments = CustomerPayment::where('refund_status', 1)->whereNotNull("refund_id")->get();


        // Log::info($payments);

        // return;

        $token = PhonePayToken::first();
        $access_token = $token->access_token;



        foreach ($payments as $payment) {

            $refundId = $payment->refund_id; // merchantRefundId

            $response = Http::withHeaders([
                'Authorization' => 'O-Bearer ' . $access_token,
                'Content-Type' => 'application/json'
            ])->get("https://api.phonepe.com/apis/pg/payments/v2/refund/$refundId/status");

            $res = $response->json();

            // Log::info($res['state']);

            if (!empty($res['state'])) {

                $statusMap = [
                    'PENDING'   => 1,
                    'COMPLETED' => 3,
                    'FAILED'    => 4
                ];

                $logData = [
                    "customer_payment_id" => $payment->id,
                    "booking_id" => $payment->booking_id,
                    "refund_status" => $statusMap
                ];

                CustomerPaymentLog::create($logData);

                $status = $statusMap[$res['state']] ?? 1;

                CustomerPayment::where('id', $payment->id)->update(["refund_status" => $status]);
            }
        }
    }
}
