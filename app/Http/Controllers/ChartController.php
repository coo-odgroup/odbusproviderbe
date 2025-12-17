<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class ChartController extends Controller
{
    public function topRoutes(Request $request)
    {
        $status         = true;
        $statusCode     = 200;
        $response       = [];
        $message        = '';
        
        try {
            $defaultStart = Carbon::now()->subMonth()->startOfMonth()->toDateString();
            $defaultEnd   = Carbon::now()->subMonth()->endOfMonth()->toDateString();

            $startDate = $request->start_date ?? "2025-01-01";
            $endDate = $request->end_date ?? $defaultEnd;
            $order = $request->order ?? 'DESC';
            $limit = $request->limit ?? 10;

            $routes = DB::table('booking')
                ->join('location as src', 'src.id', '=', 'booking.source_id')
                ->join('location as dst', 'dst.id', '=', 'booking.destination_id')
                ->select(
                    'booking.source_id',
                    'src.name as source_name',
                    'booking.destination_id',
                    'dst.name as destination_name',
                    DB::raw('COUNT(*) as total_bookings')
                )
                ->whereBetween('booking.created_at', [$startDate, $endDate])
                ->groupBy('booking.source_id', 'booking.destination_id', 'src.name', 'dst.name')
                ->orderBy('total_bookings', $order)
                ->limit($limit)
                ->get();

            $topRoute = [];

            if (!empty($routes) && count($routes) > 0) {
                foreach ($routes as $value) {
                    $topRoute[] = [
                        "Route" => $value->source_name . '-' . $value->destination_name,
                        "TotalBooking" => $value->total_bookings
                    ];
                }
                $message = Config::get('constants.RECORD_FETCHED');
                $response = $topRoute;
            } else {
                $message = Config::get('constants.RECORD_NOT_FOUND');
            }
        } catch (\Throwable $th) {
            $status     = false;
            $statusCode = 500;
            $message    = 'Something went wrong. Please try again later.';
        }

        return response()->json([
            'status'         => $status,
            'statusCode'     => $statusCode,
            'message'        => $message,
            'data'       => $response,
        ], $statusCode);
    }

    public function topCity(Request $request){
        $status         = true;
        $statusCode     = 200;
        $response       = [];
        $message        = '';
        
        try {
            $defaultStart = Carbon::now()->subMonth()->startOfMonth()->toDateString();
            $defaultEnd   = Carbon::now()->subMonth()->endOfMonth()->toDateString();

            $startDate = $request->start_date ?? "2024-01-01";
            $endDate = $request->end_date ?? $defaultEnd;
            $order = $request->order ?? 'DESC';
            $limit = $request->limit ?? 10;

            $cities = DB::table('booking')
                    ->join('location as src', 'src.id', '=', 'booking.source_id')
                    ->select(
                        'booking.source_id',
                        'src.name as source_name',
                        DB::raw('COUNT(*) as total_bookings')
                    )
                    ->whereBetween('booking.created_at', [$startDate, $endDate])
                    ->groupBy('booking.source_id', 'src.name')
                    ->orderBy('total_bookings', $order)
                    ->limit($limit)
                    ->get();
            $response = $cities;
            $message = Config::get('constants.RECORD_FETCHED');
        } catch (\Throwable $th) {
            $status     = false;
            $statusCode = 500;
            $message    = 'Something went wrong. Please try again later.';
        }

        return response()->json([
            'status'         => $status,
            'statusCode'     => $statusCode,
            'message'        => $message,
            'data'       => $response,
        ], $statusCode);
    }


    public function bookingReport(Request $request)
    {
        $startDate = "2025-10-02";
        $endDate = "2025-10-02";
        
        $defaultStart = Carbon::now()->subDay()->toDateString();

        // return $defaultStart;

        $startDate = $request->start_date??$startDate;
        $endDate   = $request->end_date??$endDate;

        // CASE 1: Only single date → Hour-wise report
        if ($startDate == $endDate) {
            // return "oneday";
            $date = $startDate;

            $data = DB::table('booking')
                ->select(
                    DB::raw("HOUR(created_at) as hour"),
                    DB::raw("COUNT(*) as total_booking"),
                    DB::raw("SUM(CASE WHEN origin = 'ODBUS' THEN 1 ELSE 0 END) as odbus_total"),
                    DB::raw("SUM(CASE WHEN user_id = '559' THEN 1 ELSE 0 END) as abhibus_total"),
                    DB::raw("SUM(CASE WHEN user_id = '486' THEN 1 ELSE 0 END) as paytm_total")
                )
                ->whereDate('created_at', $date)
                ->groupBy(DB::raw("HOUR(created_at)"))
                ->orderBy("total_booking","ASC")
                ->get();

            $result = $data->map(function ($item) {
                return [
                    "cat" => date("g A", mktime($item->hour)),
                    "total_booking" => $item->total_booking,
                    "odbus" => $item->odbus_total,
                    "abhibus" => $item->abhibus_total,
                    "paytm"=> $item->paytm_total,
                ];
            });

            return response()->json([
                "type" => "hour-wise",
                "data" => $result
            ]);
        }

        // CASE 2: Date range → Day-wise report
        if ($startDate && $endDate) {
            // return "multi";
            $data = DB::table('booking')
                ->select(
                    DB::raw("DATE(created_at) as date"),
                    DB::raw("COUNT(*) as total_booking"),
                    DB::raw("SUM(CASE WHEN origin = 'ODBUS' THEN 1 ELSE 0 END) as odbus_total"),
                    DB::raw("SUM(CASE WHEN user_id = '559' THEN 1 ELSE 0 END) as abhibus_total"),
                    DB::raw("SUM(CASE WHEN user_id = '486' THEN 1 ELSE 0 END) as paytm_total")
                )
                ->whereBetween(DB::raw("DATE(created_at)"), [$startDate, $endDate])
                ->groupBy(DB::raw("DATE(created_at)"))
                ->orderBy("total_booking","ASC")
                ->get();

            $result = $data->map(function ($item) {
                return [
                    "cat" => $item->date,
                    "total_booking" => $item->total_booking,
                    "odbus" => $item->odbus_total,
                    "abhibus" => $item->abhibus_total,
                    "paytm" => $item->paytm_total,
                ];
            });

            return response()->json([
                "type" => "day-wise",
                "data" => $result
            ]);
        }

        return response()->json([
            "message" => "Please provide start_date or start_date + end_date"
        ], 400);
    }


    public function TotalBusSeat()
    {
        $date = '2022-04-15';

        $totalBooked = DB::table('booking_detail')
            ->join('seats', 'seats.id', '=', 'booking_detail.bus_seats_id')
            ->selectRaw("
                DATE(booking_detail.created_at) AS booking_date,
                COUNT(*) AS total_booking,
                SUM(CASE WHEN seats.berthType = 1 THEN 1 ELSE 0 END) AS total_seat,
                SUM(CASE WHEN seats.berthType = 2 THEN 1 ELSE 0 END) AS total_sleeper
            ")
            ->whereDate('booking_detail.created_at', $date)
            ->groupByRaw('DATE(booking_detail.created_at)')
            ->get();

        // return $totalBooked;

        $seats = Bus::where('bus.status', 1)
            ->join('seats', 'seats.bus_seat_layout_id', '=', 'bus.bus_seat_layout_id')
            ->select('seats.id', 'seats.berthType')
            ->distinct()
            ->get();

        $lower_berth = $seats->where('berthType', 1)->count();
        $upper_berth = $seats->where('berthType', 2)->count();

        $data = [$lower_berth,$upper_berth];

        // return $data;

        $response = [
            // "total_seat" => $lower_berth +$upper_berth,
            "total_lower_berth" => $lower_berth,
            "total_upper_berth" => $upper_berth,
            // "total_seat_booked" => $totalBooked[0]->total_seat,
            "total_seat_booked" => 1000,
            "total_seat_avl" => $lower_berth - $totalBooked[0]->total_seat,
            // "total_slepper_booked" => $totalBooked[0]->total_sleeper,
            "total_slepper_booked" => 500,
            "total_slepper_avl" => $upper_berth - $totalBooked[0]->total_sleeper

        ];

        return $response;

        
    }



}
