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

    function scheduleRefund($request)
    {
        $paginate = $request->rows_number ?? 100;

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
            ->where('user_type', 1)
            ->where('status', 2)
            ->where('refund_amount', '!=', 0)
            ->whereDate('created_at', '>=', '2026-06-01')
            ->whereHas('CustomerPayment', function ($q) {
                $q->whereNotNull('order_id')
                    ->whereNotNull('razorpay_id')
                    ->where('payment_done', 1);
            })
            ->when($request->journey_dt, function ($q, $journey_dt) {
                $q->whereDate('journey_dt', $journey_dt);
            })
            ->when($request->updated_at, function ($q, $updated_at) {
                $q->whereDate('updated_at', $updated_at);
            })
            ->when($request->pnr, function ($q, $pnr) {
                $q->where('pnr', $pnr);
            })
            ->paginate($paginate);

        return array(
            "count" => $data->count(),
            "total" => $data->total(),
            "data" => $data
        );
    }

    public function scheduleRefundSelected($request)
    {
        // return Config::get('constants.CASHFREE_API_URL');
        $booking_ids = $request->booking_ids;

        $data = $this->booking::with('CustomerPayment')
            ->where('status', 2)
            ->whereIn('id', $booking_ids)
            ->get();

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

                // Cashfree amount should be in normal rupees
                $amount = round($booking->refund_amount, 2);

                $refundId = "REFUND_" . time() . "_" . $booking->id;

                $payload = [
                    "refund_amount" => $amount,
                    "refund_id" => $refundId,
                    "refund_note" => "Bus ticket refund"
                ];

                Log::info("Cashfree Refund Payload", $payload);

                // Sandbox URL
                $url = Config::get('constants.CASHFREE_API_URL') . "/" . $orderId . "/refunds";

                // Production URL
                // $url = "https://api.cashfree.com/pg/orders/" . $orderId . "/refunds";

                $response = Http::withHeaders([
                    'x-client-id'     => Config::get('constants.CASHFREE_KEY'), // TEST108577409ff7eb8e2b1cb161978f04775801
                    'x-client-secret' => Config::get('constants.CASHFREE_SECRET'), // cfsk_ma_test_c0f4b0bd0ccd2731dfb130a93c1edc8b_2f49aced
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
                        'refund_mode' => 1,
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

    function completeRefund($request)
    {
        $paginate = $request->rows_number ?? 100;

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
            ->where('user_type', 1)
            ->where('refund_amount', '!=', 0)
            ->whereDate('created_at', '>=', '2026-06-01')
            ->whereHas('CustomerPayment', function ($q) {
                $q->whereNotNull('order_id')
                    ->whereNotNull('razorpay_id')
                    ->whereNotNull('refund_id')
                    ->where('payment_done', 2);
            })
            ->when($request->journey_dt, function ($q, $journey_dt) {
                $q->whereDate('journey_dt', $journey_dt);
            })
            ->when($request->updated_at, function ($q, $updated_at) {
                $q->whereDate('updated_at', $updated_at);
            })
            ->when($request->pnr, function ($q, $pnr) {
                $q->where('pnr', $pnr);
            })
            ->paginate($paginate);

        return array(
            "count" => $data->count(),
            "total" => $data->total(),
            "data" => $data
        );
    }
}
