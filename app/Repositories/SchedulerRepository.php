<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\Booking;
use App\Models\Location;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use App\Models\PhonePayToken;
use App\Models\CustomerPayment;
use Illuminate\Support\Facades\Http;
use App\Jobs\ScheduleRefundJob;
use App\Models\CustomerPaymentLog;

class SchedulerRepository
{
    protected $booking;
    protected $location;
    protected $bus;

    public function __construct(Booking $booking, Location $location, Bus $bus)
    {
        $this->booking = $booking;
        $this->location = $location;
        $this->bus = $bus;
    }

    // function scheduleRefund($request)
    // {
    //     $paginate = $request->rows_number;
    //     // $cancel_by = $request->cancel_by;

    //     $data = $this->booking->with(
    //         'BookingDetail.BusSeats.seats',
    //         'BookingDetail.BusSeats.ticketPrice',
    //         'Bus',
    //         'Users',
    //         'User',
    //         'CustomerPayment',
    //         'source',
    //         'destination'
    //     )
    //         ->with('bus.busstoppage')
    //         ->with('ClientWallet')
    //         ->where('status', 2)
    //         ->whereHas('CustomerPayment', function ($q) {
    //             $q->whereNotNull('pp_orderId')->where('payment_done', 1);
    //         });


    //     if (!empty($cancel_by)) {
    //         $data = $data->where('user_type', 1);
    //     }

    //     $data = $data->paginate($paginate);

    //     $response = array(
    //         "count" => $data->count(),
    //         "total" => $data->total(),
    //         "data" => $data
    //     );

    //     // ScheduleRefundJob::dispatch();

    //     return $response;
    // }

    // function scheduleRefundSelected($request)
    // {
    //     $booking_ids = $request->booking_ids;

    //     $data = $this->booking::with('CustomerPayment')
    //         ->where('status', 2)
    //         ->whereIn('id', $booking_ids)
    //         ->get();

    //         return $data;

    //     foreach ($data as $booking) {
    //         $customerId = $booking->CustomerPayment->id;
    //         $orderId = $booking->transaction_id; // TX123456 // order_id
    //         $amount = $booking->refund_amount;
    //         ScheduleRefundJob::dispatch($amount, $orderId, $customerId);
    //         // ScheduleRefundJob::dispatch($amount, $orderId, $customerId)->delay(now()->addSeconds(5));
    //     }
    // }


    // public function scheduleRefundSelected($request)
    // {
    //     $booking_ids = $request->booking_ids;

    //     $data = $this->booking::with('CustomerPayment')
    //         ->where('status', 2)
    //         ->whereIn('id', $booking_ids)
    //         ->get();

    //     $results = [];

    //     foreach ($data as $booking) {

    //         try {

    //             if (!$booking->CustomerPayment) {
    //                 continue;
    //             }

    //             $payment = $booking->CustomerPayment;

    //             if ($payment->refund_id != "0") {
    //                 $results[] = [
    //                     'booking_id' => $booking->id,
    //                     'status' => 'SKIPPED',
    //                     'message' => 'Already refunded'
    //                 ];
    //                 continue;
    //             }

    //             $orderId = $payment->order_id;

    //             $amount = (int) round($booking->refund_amount * 100);

    //             $merchantRefundId = "REFUND_" . time() . "_" . $booking->id;

    //             $payload = [
    //                 "merchantRefundId" => $merchantRefundId,
    //                 "originalMerchantOrderId" => $orderId,
    //                 "amount" => $amount
    //             ];

    //             Log::info("Refund Payload", $payload);

    //             $token = PhonePayToken::first();

    //             if (!$token) {
    //                 throw new \Exception("Token not found");
    //             }

    //             $response = Http::withHeaders([
    //                 'Authorization' => $token->token_type . " " . $token->access_token,
    //                 'Content-Type' => 'application/json'
    //             ])->post("https://api.phonepe.com/apis/pg/payments/v2/refund", $payload);

    //             $res = $response->json();

    //             Log::info("Refund Response", $res);

    //             $statusMap = [
    //                 'PENDING'   => 1,
    //                 'COMPLETED' => 3,
    //                 'FAILED'    => 4
    //             ];

    //             $refundStatus = $statusMap[$res['state']] ?? 0;

    //             if (!empty($res['refundId'])) {

    //                 $logData = [
    //                     "customer_payment_id"=>$payment->id,
    //                     "booking_id"=>$payment->booking_id,
    //                     "refund_status" => 1
    //                 ];

    //                 CustomerPaymentLog::create($logData);


    //                 CustomerPayment::where('id', $payment->id)->update([
    //                     'payment_done'  => 2,
    //                     'refund_mode'   => 2,
    //                     'refund_id'     => $merchantRefundId,
    //                     // 'refund_id'     => $res['refundId'],
    //                     'refund_status' => $refundStatus
    //                 ]);

    //                 $results[] = [
    //                     'booking_id' => $booking->id,
    //                     'status' => 'SUCCESS',
    //                     'refundId' => $res['refundId']
    //                 ];
    //             } else {

    //                 $results[] = [
    //                     'booking_id' => $booking->id,
    //                     'status' => 'FAILED',
    //                     'response' => $res
    //                 ];
    //             }
    //         } catch (\Exception $e) {

    //             $results[] = [
    //                 'booking_id' => $booking->id,
    //                 'status' => 'ERROR',
    //                 'message' => $e->getMessage()
    //             ];
    //         }

    //         sleep(2);
    //     }

    //     return response()->json([
    //         'status' => 1,
    //         'message' => 'Refund Process Completed',
    //         'data' => $results
    //     ]);
    // }

    function scheduleRefund($request)
    {
        $paginate = $request->rows_number ?? 100;
        // $cancel_by = $request->cancel_by;

        $data = $this->booking->with(
            'BookingDetail.BusSeats.seats',
            'BookingDetail.BusSeats.ticketPrice',
            'Bus',
            'Users',
            'User',
            'CustomerPayment',
            'source',
            'destination'
        )
            ->with('bus.busstoppage')
            ->with('ClientWallet')
            ->where('status', 2)
            ->where('refund_amount', '!=', 0)
            ->whereHas('CustomerPayment', function ($q) {
                $q->whereNotNull('order_id')
                    ->whereNotNull('razorpay_id')
                    ->where('payment_done', 1);
            });

        // $data = $data->where('user_type', 1);
        $data = $data->where('pnr', 'ODW989907K4');

        $data = $data->paginate($paginate);

        $response = array(
            "count" => $data->count(),
            "total" => $data->total(),
            "data" => $data
        );

        // ScheduleRefundJob::dispatch();

        return $response;
    }

    public function scheduleRefundSelected($request)
    {
        $booking_ids = $request->booking_ids;

        $data = $this->booking::with('CustomerPayment')
            ->where('status', 2)
            ->whereIn('id', $booking_ids)
            ->get();

        // return $data;

        $results = [];

        foreach ($data as $booking) {

            try {

                if (!$booking->CustomerPayment) {
                    continue;
                }

                $payment = $booking->CustomerPayment;

                // already refunded
                if ($payment->refund_id != "0") {

                    $results[] = [
                        'booking_id' => $booking->id,
                        'status' => 'SKIPPED',
                        'message' => 'Already refunded'
                    ];

                    continue;
                }

                $orderId = $payment->order_id;

                // return $orderId;

                // Cashfree amount should be in normal rupees
                $amount = round($booking->refund_amount, 2);

                Log::info("Cashfree Refund totaal amount", [$booking->total_fare]);
                Log::info("Cashfree Refund amount", [$amount]);

                // return $amount;

                $refundId = "REFUND_" . time() . "_" . $booking->id;

                $payload = [
                    "refund_amount" => $amount,
                    "refund_id" => $refundId,
                    "refund_note" => "Bus ticket refund"
                ];

                Log::info("Cashfree Refund Payload", $payload);

                // Sandbox URL
                $url = "https://sandbox.cashfree.com/pg/orders/" . $orderId . "/refunds";

                // Production URL
                // $url = "https://api.cashfree.com/pg/orders/" . $orderId . "/refunds";

                $response = Http::withHeaders([
                    'x-client-id'     => env('CASHFREE_APP_ID'), // TEST108577409ff7eb8e2b1cb161978f04775801
                    'x-client-secret' => env('CASHFREE_SECRET_KEY'), // cfsk_ma_test_c0f4b0bd0ccd2731dfb130a93c1edc8b_2f49aced
                    'x-api-version'   => '2023-08-01',
                    'Content-Type'    => 'application/json'
                ])->post($url, $payload);

                $res = $response->json();

                Log::info("Cashfree Refund Response", $res);

                $statusMap = [
                    'PENDING' => 1,
                    'SUCCESS' => 3,
                    'FAILED'  => 4
                ];

                $refundStatus = $statusMap[$res['refund_status'] ?? 'FAILED'] ?? 0;

                if (!empty($res['cf_refund_id'])) {

                    CustomerPayment::where('id', $payment->id)->update([
                        'payment_done' => 2,
                        'refund_mode' => 2,
                        'refund_id' => $res['cf_refund_id'],
                        'refund_status' => $refundStatus
                    ]);

                    $results[] = [
                        'booking_id' => $booking->id,
                        'status' => 'SUCCESS',
                        'refund_id' => $res['cf_refund_id']
                    ];
                } else {

                    $results[] = [
                        'booking_id' => $booking->id,
                        'status'     => 'FAILED',
                        'response'   => $res
                    ];
                }
            } catch (\Exception $e) {

                $results[] = [
                    'booking_id' => $booking->id,
                    'status' => 'ERROR',
                    'message' => $e->getMessage()
                ];
            }

            sleep(2);
        }

        return response()->json([
            'status' => 1,
            'message' => 'Refund Process Completed',
            'data' => $results
        ]);
    }

    // public function phonpeToken()
    // {
    //     return PhonePayToken::first();
    // }
}
