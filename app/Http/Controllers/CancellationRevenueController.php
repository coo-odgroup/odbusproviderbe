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

            $result = Booking::where('status', 2)
                ->whereBetween('journey_dt', [$startDate, $endDate])
                ->selectRaw('DATE(journey_dt) as journey_date, COUNT(*) as total_cancel_bookings')
                ->groupBy('journey_date')
                ->orderBy('journey_date', $order)
                ->limit($limit)
                ->get();

            if (!empty($result)) {
                $response = $result;
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

            $result = DB::table('booking')
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
                    ROUND(SUM(booking.refund_amount), 2) as total_refund_amount
                ')
                ->groupByRaw('DATE(booking.journey_dt)')
                ->orderByRaw('DATE(booking.journey_dt) ' . $order)
                ->limit($limit)
                ->get();

            if (!empty($result)) {
                $response = $result;
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

    public function cancellationCharges(Request $request)
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

            $result = Booking::where('status', 2)
                ->whereBetween('journey_dt', [$startDate, $endDate])
                ->where('odbus_cancel_profit', '>', 0)
                ->selectRaw('
                    DATE(journey_dt) as journey_date,
                    COUNT(*) as total_cancel_bookings,
                    ROUND(SUM(odbus_cancel_profit), 2) as total_cancel_profit
                ')
                ->groupBy('journey_date')
                ->orderBy('journey_date', $order)
                ->limit($limit)
                ->get();

            if (!empty($result)) {
                $response = $result;
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

    public function busWiseTotalLoss(Request $request)
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


            $rows = Booking::join('bus', 'bus.id', '=', 'booking.bus_id')
                ->where('booking.status', 2)
                ->whereBetween('booking.journey_dt', [$startDate, $endDate])
                ->selectRaw('
                    DATE(booking.journey_dt) as journey_date,
                    booking.bus_id,
                    bus.name as bus_name,
                    COUNT(booking.id) as total_cancel_booking,
                    ROUND(SUM(booking.odbus_charges), 2) as loss
                ')
                ->groupBy(
                    'journey_date',
                    'booking.bus_id',
                    'bus.name'
                )
                ->orderBy('journey_date', $order)
                ->limit($limit)
                ->get();

            $result = $rows
                ->groupBy('journey_date')
                ->map(function ($items, $date) {

                    return [
                        'journey_date' => $date,
                        'date_total_loss' => round($items->sum('loss'), 2),
                        'buses' => $items->map(function ($row) {
                            return [
                                'bus_id' => (int) $row->bus_id,
                                'bus_name' => $row->bus_name,
                                'total_cancel_bookings' => (int) $row->total_cancel_bookings,
                                'loss' => (float) $row->loss
                            ];
                        })->values()
                    ];

                })
                ->values();

            if (!empty($result)) {
                $response = $result;
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