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

    function scheduleRefund($request) {
        $paginate = $request->rows_number;
        $cancel_by = $request->cancel_by;

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
        ->whereHas('CustomerPayment', function ($q) {
            $q->whereNotNull('pp_orderId')->where('payment_done', 1);
        });


        if (!empty($cancel_by)) {
            $data = $data->where('cancel_by', $cancel_by);
        }

        $data = $data->paginate($paginate);

        $response = array(
            "count" => $data->count(),
            "total" => $data->total(),
            "data" => $data
        );

        // ScheduleRefundJob::dispatch();

        return $response;
    }

    function scheduleRefundSelected($request) {
        $booking_ids = $request->booking_ids;

        $data = $this->booking::with('CustomerPayment')
        ->where('status', 2)
        ->whereIn('id', $booking_ids)
        ->get();

        foreach ($data as $booking) {
            $customerId = $booking->CustomerPayment->id;
            $orderId = $booking->CustomerPayment->order_id; // TX123456
            $amount = $booking->refund_amount;
            ScheduleRefundJob::dispatch($amount, $orderId, $customerId);
            // ScheduleRefundJob::dispatch($amount, $orderId, $customerId)->delay(now()->addSeconds(5));
        }
    }

    public function phonpeToken() {
        return PhonePayToken::first();
    }

    public function initiateRefund($amount, $orderId, $customerId) {
        $amountInPaise = $amount * 100;

        $merchantRefundId = "REFUND_" . time();

        $payload = [
            "merchantRefundId" => $merchantRefundId,
            "originalMerchantOrderId" => $orderId, // TX123456
            "amount" => $amountInPaise
        ];

        $phonpe_url = Config('constants.PHONPE_API_URL');
        $url = $phonpe_url . "payments/v2/refund";

        $getToken = $this->phonpeToken();

        // Make API call
        $resp = Http::withHeaders([
            'Authorization' => $getToken->token_type . " " . $getToken->access_token,
            'Content-Type' => 'application/json'
        ])->post($url, $payload);

        $rfJsonResp = $resp->json();

        if ($rfJsonResp["refundId"]) {
            CustomerPayment::where('id', $customerId)->update([
                'payment_done' => 2,
                'refund_mode' => 2,
                'refund_id' => $rfJsonResp["refundId"]
            ]);
        }

        return $resp->json();
    }
}
