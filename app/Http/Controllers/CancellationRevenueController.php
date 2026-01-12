<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

use App\Models\Booking;

class CancellationRevenueController extends Controller
{
    public function cancelledTicketCount(Request $request)
    {
        $status     = true;
        $statusCode = 200;
        $response   = [];
        $message    = '';

        try {

            $defaultStart = Carbon::now()->subMonth()->startOfMonth()->toDateString();
            $defaultEnd   = Carbon::now()->subMonth()->endOfMonth()->toDateString();

            $startDate = $request->start_date ?? $defaultStart;
            $endDate = $request->end_date ?? $defaultEnd;
            $order = $request->order ?? 'DESC';
            $limit = $request->limit ?? 10;

            $bookings = Booking::where('status', 2)
                ->whereBetween('journey_dt', [$startDate, $endDate])
                ->selectRaw('DATE(journey_dt) as journey_date, COUNT(*) as total_cancel_bookings')
                ->groupBy('journey_date')
                ->orderBy('journey_date', $order)
                ->limit($limit)
                ->get();

            if (!empty($bookings)) {
                $response = $bookings;
                $message  = Config::get('constants.RECORD_FETCHED');
            } else {
                $message = Config::get('constants.RECORD_NOT_FOUND');
            }

        } catch (\Throwable $th) {
            $status     = false;
            $statusCode = 500;
            $message    = 'Something went wrong. Please try again later.';
        }

        return response()->json([
            'status'     => $status,
            'statusCode' => $statusCode,
            'message'    => $message,
            'data'       => $response,
        ], $statusCode);
    }

    public function refundAmount(Request $request)
    {
        $status     = true;
        $statusCode = 200;
        $response   = [];
        $message    = '';

        try {

            $defaultStart = Carbon::now()->subMonth()->startOfMonth()->toDateString();
            $defaultEnd   = Carbon::now()->subMonth()->endOfMonth()->toDateString();

            $startDate = $request->start_date ?? $defaultStart;
            $endDate = $request->end_date ?? $defaultEnd;
            $order = $request->order ?? 'DESC';
            $limit = $request->limit ?? 10;

            $bookings = DB::table('booking')
                ->join(
                    'customer_payment',
                    'customer_payment.booking_id',
                    '=',
                    'booking.id'
                )
                ->where('booking.status', 2)
                ->where('customer_payment.payment_done', 2)
                ->whereBetween('booking.journey_dt', [$startDate, $endDate])
                ->selectRaw('
                    DATE(booking.journey_dt) as journey_date,
                    COUNT(DISTINCT booking.id) as total_cancel_bookings,
                    SUM(booking.refund_amount) as total_refund_amount
                ')
                ->groupByRaw('DATE(booking.journey_dt)')
                ->orderByRaw('DATE(booking.journey_dt) ' . $order)
                ->limit($limit)
                ->get();

            if (!empty($bookings)) {
                $response = $bookings;
                $message  = Config::get('constants.RECORD_FETCHED');
            } else {
                $message = Config::get('constants.RECORD_NOT_FOUND');
            }

        } catch (\Throwable $th) {
            $status     = false;
            $statusCode = 500;
            $message    = 'Something went wrong. Please try again later.';
        }

        return response()->json([
            'status'     => $status,
            'statusCode' => $statusCode,
            'message'    => $message,
            'data'       => $response,
        ], $statusCode);
    }
}