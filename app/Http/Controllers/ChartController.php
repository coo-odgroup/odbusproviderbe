<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Bus;
use App\Models\BusOperator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class ChartController extends Controller
{
    use ApiResponser;
    // public function topRoutes(Request $request)
    // {
    //     $status         = true;
    //     $statusCode     = 200;
    //     $response       = [];
    //     $message        = '';

    //     try {
    //         $defaultStart = Carbon::now()->subMonth()->startOfMonth()->toDateString();
    //         $defaultEnd   = Carbon::now()->subMonth()->endOfMonth()->toDateString();

    //         $startDate = $request->start_date ?? "2025-01-01";
    //         $endDate = $request->end_date ?? $defaultEnd;
    //         $order = $request->order ?? 'DESC';
    //         $limit = $request->limit ?? 10;

    //         $routes = DB::table('booking')
    //             ->join('location as src', 'src.id', '=', 'booking.source_id')
    //             ->join('location as dst', 'dst.id', '=', 'booking.destination_id')
    //             ->select(
    //                 'booking.source_id',
    //                 'src.name as source_name',
    //                 'booking.destination_id',
    //                 'dst.name as destination_name',
    //                 DB::raw('COUNT(*) as total_bookings')
    //             )
    //             ->whereBetween('booking.created_at', [$startDate, $endDate])
    //             ->groupBy('booking.source_id', 'booking.destination_id', 'src.name', 'dst.name')
    //             ->orderBy('total_bookings', $order)
    //             ->limit($limit)
    //             ->get();

    //         $topRoute = [];

    //         if (!empty($routes) && count($routes) > 0) {
    //             foreach ($routes as $value) {
    //                 $topRoute[] = [
    //                     "Route" => $value->source_name . '-' . $value->destination_name,
    //                     "TotalBooking" => $value->total_bookings
    //                 ];
    //             }
    //             $message = Config::get('constants.RECORD_FETCHED');
    //             $response = $topRoute;
    //         } else {
    //             $message = Config::get('constants.RECORD_NOT_FOUND');
    //         }
    //     } catch (\Throwable $th) {
    //         $status     = false;
    //         $statusCode = 500;
    //         $message    = 'Something went wrong. Please try again later.';
    //     }

    //     return response()->json([
    //         'status'         => $status,
    //         'statusCode'     => $statusCode,
    //         'message'        => $message,
    //         'data'       => $response,
    //     ], $statusCode);
    // }

    public function topRoutes(Request $request)
    {
        $status     = true;
        $statusCode = 200;
        $response   = [];
        $message    = '';

        try {
            // Default date (previous month)
            $defaultStart = Carbon::now()->subMonth()->startOfMonth()->toDateString();
            $defaultEnd   = Carbon::now()->subMonth()->endOfMonth()->toDateString();

            // Request params
            $startDate = $request->start_date ?? "2025-01-01";
            $endDate   = $request->end_date ?? $defaultEnd;
            $order     = $request->order ?? 'DESC';
            $limit     = $request->limit ?? 10;

            // Fetch top routes
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
                ->groupBy(
                    'booking.source_id',
                    'booking.destination_id',
                    'src.name',
                    'dst.name'
                )
                ->orderBy('total_bookings', $order)
                ->limit($limit)
                ->get();

            if ($routes->count() > 0) {

                // Total bookings of top N routes
                $totalTopBookings = $routes->sum('total_bookings');

                foreach ($routes as $route) {

                    $percentage = $totalTopBookings > 0
                        ? round(($route->total_bookings / $totalTopBookings) * 100, 2)
                        : 0;

                    $response[] = [
                        "Route"         => $route->source_name . '-' . $route->destination_name,
                        "TotalBooking"  => $route->total_bookings,
                        "Percentage"    => $percentage . '%'
                    ];
                }

                $message = Config::get('constants.RECORD_FETCHED');
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


    public function topCity(Request $request)
    {
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

        $startDate = $request->start_date ?? $startDate;
        $endDate   = $request->end_date ?? $endDate;

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
                ->orderBy("total_booking", "ASC")
                ->get();

            $result = $data->map(function ($item) {
                return [
                    "cat" => date("g A", mktime($item->hour)),
                    "total_booking" => $item->total_booking,
                    "odbus" => $item->odbus_total,
                    "abhibus" => $item->abhibus_total,
                    "paytm" => $item->paytm_total,
                ];
            });

            return response()->json([
                "type" => "hour-wise",
                "data" => $result
            ]);
        }

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
                ->orderBy("total_booking", "ASC")
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

    // public function TotalBusSeat(Request $request)
    // {
    //     $date = $request->start_date;

    //     $totalBooked = DB::table('booking_detail')
    //         ->join('seats', 'seats.id', '=', 'booking_detail.bus_seats_id')
    //         ->selectRaw("
    //             DATE(booking_detail.created_at) AS booking_date,
    //             COUNT(*) AS total_booking,
    //             SUM(CASE WHEN seats.berthType = 1 THEN 1 ELSE 0 END) AS total_seat,
    //             SUM(CASE WHEN seats.berthType = 2 THEN 1 ELSE 0 END) AS total_sleeper
    //         ")
    //         ->whereDate('booking_detail.created_at', $date)
    //         ->groupByRaw('DATE(booking_detail.created_at)')
    //         ->first();

    //     // return $totalBooked;

    //     $seats = Bus::where('bus.status', 1)
    //         ->join('seats', 'seats.bus_seat_layout_id', '=', 'bus.bus_seat_layout_id')
    //         ->select('seats.id', 'seats.berthType')
    //         ->distinct()
    //         ->get();

    //     $lower_berth = $seats->where('berthType', 1)->count();
    //     $upper_berth = $seats->where('berthType', 2)->count();

    //     $data = [$lower_berth, $upper_berth];

    //     // return $data;

    //     $response = [
    //         // "total_seat" => $lower_berth +$upper_berth,
    //         "total_lower_berth" => $lower_berth,
    //         "total_upper_berth" => $upper_berth,
    //         "total_seat_booked" => $totalBooked[0]->total_seat,
    //         "total_seat_avl" => $lower_berth - $totalBooked[0]->total_seat,
    //         "total_sleeper_booked" => $totalBooked[0]->total_sleeper,
    //         "total_sleeper_avl" => $upper_berth - $totalBooked[0]->total_sleeper

    //     ];

    //     return $response;
    // }

    public function TotalBusSeat(Request $request)
    {
        $currentDate = now()->toDateString();

        $fromdate = $request->start_date ?? $currentDate;
        $todate   = $request->end_date ?? $currentDate;

        $fromDate = Carbon::parse($fromdate);
        $toDate   = Carbon::parse($todate);

        $totalDays = $fromDate->diffInDays($toDate) + 1;
        $totalBooked = DB::table('booking_detail')
            ->join('bus_seats', 'bus_seats.id', '=', 'booking_detail.bus_seats_id')
            ->join('seats', 'seats.id', '=', 'bus_seats.seats_id')
            ->selectRaw("
            COUNT(*) AS total_booking,
            SUM(CASE WHEN seats.berthType = 1 THEN 1 ELSE 0 END) AS total_seat,
            SUM(CASE WHEN seats.berthType = 2 THEN 1 ELSE 0 END) AS total_sleeper
        ")
            ->whereBetween('booking_detail.created_at', [
                $fromdate . ' 00:00:00',
                $todate   . ' 23:59:59'
            ])
            ->first();

        $totalSeatBooked    = (int) ($totalBooked->total_seat ?? 0);
        $totalSleeperBooked = (int) ($totalBooked->total_sleeper ?? 0);

        $seats = Bus::where('bus.status', 1)
            ->join('seats', 'seats.bus_seat_layout_id', '=', 'bus.bus_seat_layout_id')
            ->select('seats.id', 'seats.berthType')
            ->distinct()
            ->get();

        $lower_berth_per_day = $seats->where('berthType', 1)->count();
        $upper_berth_per_day = $seats->where('berthType', 2)->count();

        $total_lower_berth = $lower_berth_per_day * $totalDays;
        $total_upper_berth = $upper_berth_per_day * $totalDays;

        $response = [
            "start_date"             => $fromdate,
            "end_date"               => $todate,
            "total_days"             => $totalDays,

            "total_lower_berth"      => $total_lower_berth,
            "total_upper_berth"      => $total_upper_berth,

            "total_seat_booked"      => $totalSeatBooked,
            "total_seat_avl"         => max(0, $total_lower_berth - $totalSeatBooked),

            "total_sleeper_booked"   => $totalSleeperBooked,
            "total_sleeper_avl"      => max(0, $total_upper_berth - $totalSleeperBooked),
        ];

        return response()->json($response);
    }


    public function operatorBooking(Request $request)
    {
        $currentDate = now()->toDateString();
        // $currentDate = "2025-06-01";
        $fromDate = $request->from_j_date ?? $currentDate;
        $toDate   = $request->to_j_date ?? "2025-12-01";
        $limit    = $request->limit ?? 10;
        $order    = strtoupper($request->order ?? 'DESC');
        $operatorIds = $request->bus_operator_id;

        $operators = BusOperator::query()
            ->when(!empty($operatorIds), function ($q) use ($operatorIds) {
                $q->whereIn('id', is_array($operatorIds) ? $operatorIds : [$operatorIds]);
            })
            ->with([
                'buses' => function ($query) use ($fromDate, $toDate) {
                    $query->withCount([
                        // 'bookings' => function ($q) use ($fromDate, $toDate) {
                        //     $q->whereBetween('journey_dt', [$fromDate, $toDate]);
                        // }
                        'bookings' => function ($q) use ($fromDate, $toDate) {
                            $q->where('status', 1)
                                ->where('journey_dt', $fromDate);
                        }

                    ])
                        ->with([
                            'route.source',
                            'route.destination'
                        ]);
                }
            ])
            ->get()
            ->map(function ($operator) {

                $busWise = $operator->buses
                    ->filter(fn($bus) => $bus->bookings_count > 0)
                    ->groupBy('id') // bus-wise
                    ->map(function ($buses) {
                        $bus = $buses->first();

                        $src  = optional(optional($bus->route)->source)->name;
                        $dest = optional(optional($bus->route)->destination)->name;

                        return [
                            'bus_number' =>
                            $bus->bus_number . ($src && $dest ? "({$src}-{$dest})" : ''),
                            'total_booking' => $bus->bookings_count,
                        ];
                    })
                    ->values();

                $totalBooking = $busWise->sum('total_booking');

                if ($totalBooking === 0) {
                    return null;
                }

                return [
                    'operator_id'   => $operator->id,
                    'operator_name' => $operator->organisation_name,
                    'total_booking' => $totalBooking,
                    'bus_wise'      => $busWise,
                ];
            })
            ->filter();

        $operators = $order === 'ASC'
            ? $operators->sortBy('total_booking')
            : $operators->sortByDesc('total_booking');

        return response()->json(
            $operators->values()->take($limit)
        );
    }


    public function operatorRevenue(Request $request)
    {
        $currentDate = now()->toDateString();

        $fromDate = $request->from_j_date ?? $currentDate;
        // $toDate   = $request->to_j_date   ?? "2025-12-01";
        $limit    = $request->limit ?? 10;
        $order    = strtoupper($request->order ?? "DESC");
        $operatorIds = $request->bus_operator_id;
        $charges = $request->revenue_by ?? "total_fare";

        $rows = DB::table('bus_operator')
            ->join('bus', 'bus_operator.id', '=', 'bus.bus_operator_id')
            ->join('booking', 'bus.id', '=', 'booking.bus_id')

            ->joinSub(
                DB::table('ticket_price')
                    ->select(
                        'bus_id',
                        DB::raw('MIN(source_id) as source_id'),
                        DB::raw('MIN(destination_id) as destination_id')
                    )
                    ->groupBy('bus_id'),
                'tp',
                'tp.bus_id',
                '=',
                'bus.id'
            )

            ->join('location as src', 'src.id', '=', 'tp.source_id')
            ->join('location as dest', 'dest.id', '=', 'tp.destination_id')

            ->where('booking.journey_dt', $fromDate)
            ->where('booking.status', 1)

            ->when(!empty($operatorIds), function ($q) use ($operatorIds) {
                $q->whereIn(
                    'bus_operator.id',
                    is_array($operatorIds) ? $operatorIds : [$operatorIds]
                );
            })

            ->select(
                'bus_operator.id as operator_id',
                'bus_operator.organisation_name',
                'bus.id as bus_id',
                'bus.bus_number',
                DB::raw("CONCAT(bus.bus_number,'(',src.name,'-',dest.name,')') as bus_route"),
                DB::raw("SUM(booking.$charges) as bus_revenue"),
                DB::raw('COUNT(booking.id) as bus_booking')
            )

            ->groupBy(
                'bus_operator.id',
                'bus_operator.organisation_name',
                'bus.id',
                'bus.bus_number',
                'src.name',
                'dest.name'
            )
            ->get();

        $result = $rows
            ->groupBy('operator_id')
            ->map(function ($items, $operatorId) {
                return [
                    'operator_id'       => (int) $operatorId,
                    'organisation_name' => $items->first()->organisation_name,
                    'total_revenue'     => (int) $items->sum('bus_revenue'),
                    'total_booking'     => (int) $items->sum('bus_booking'),
                    'bus_wise' => $items->map(function ($bus) {
                        return [
                            'bus_number'    => $bus->bus_route,
                            'total_revenue' => (int) $bus->bus_revenue,
                            'total_booking' => (int) $bus->bus_booking,
                        ];
                    })->values()
                ];
            });


        $result = $order === 'ASC'
            ? $result->sortBy('total_revenue')
            : $result->sortByDesc('total_revenue');

        return response()->json([
            'status' => 1,
            'data'   => $result->values()->take($limit)
        ]);
    }

    public function operatorBusclose(Request $request)
    {
        $defaultStart = Carbon::now()->subDay()->toDateString();

        $fromDate = $request->from_date ?? $defaultStart;
        $toDate   = $request->to_date   ?? $defaultStart;
        $limit    = $request->limit ?? 10;

        $operatorIds = $request->bus_operator_id ?? [];

        if ($fromDate === $toDate) {

            $operators = $this->getOperatorCancelData($fromDate, $operatorIds);

            return response()->json([
                "status" => 1,
                "type"   => "single_date",
                "data"   => $operators
            ]);
        }

        $dates = DB::table('bus_cancelled_date')
            ->select('cancelled_date')
            ->whereBetween('cancelled_date', [$fromDate, $toDate])
            ->distinct()
            ->orderBy('cancelled_date')
            ->limit($limit)
            ->pluck('cancelled_date');

        $result = [];

        foreach ($dates as $date) {
            $operators = $this->getOperatorCancelData($date, $operatorIds);

            if ($operators->isNotEmpty()) {
                $result[] = [
                    "date" => $date,
                    "operators" => $operators
                ];
            }
        }

        return response()->json([
            "status" => 1,
            "type"   => "date_wise",
            "data"   => $result
        ]);
    }


    private function getOperatorCancelData($date, $operatorIds = [])
    {
        $operators = DB::table('bus_operator')
            ->join('bus_cancelled', 'bus_cancelled.bus_operator_id', '=', 'bus_operator.id')
            ->join('bus_cancelled_date', 'bus_cancelled_date.bus_cancelled_id', '=', 'bus_cancelled.id')
            ->select(
                'bus_operator.id',
                'bus_operator.organisation_name',
                DB::raw('COUNT(DISTINCT bus_cancelled.bus_id) as total_cancel')
            )
            ->where('bus_cancelled_date.cancelled_date', $date)
            ->where('bus_operator.status', 1)

            ->when(!empty($operatorIds), function ($q) use ($operatorIds) {
                $q->whereIn('bus_operator.id', $operatorIds);
            })

            ->groupBy('bus_operator.id', 'bus_operator.organisation_name')
            ->orderByDesc('total_cancel')
            ->limit(10)
            ->get();

        $operators->transform(function ($operator) use ($date) {

            $operator->cancelled_buses = DB::table('bus_cancelled')
                ->join('bus', 'bus.id', '=', 'bus_cancelled.bus_id')
                ->join('bus_cancelled_date', 'bus_cancelled_date.bus_cancelled_id', '=', 'bus_cancelled.id')

                ->joinSub(
                    DB::table('ticket_price')
                        ->select(
                            'bus_id',
                            DB::raw('MIN(source_id) as source_id'),
                            DB::raw('MIN(destination_id) as destination_id')
                        )
                        ->groupBy('bus_id'),
                    'tp',
                    'tp.bus_id',
                    '=',
                    'bus.id'
                )
                ->join('location as src', 'src.id', '=', 'tp.source_id')
                ->join('location as dest', 'dest.id', '=', 'tp.destination_id')
                ->select(
                    'bus.id',
                    'bus.bus_number',
                    'bus.name as bus_name',
                    DB::raw("CONCAT(bus.bus_number,'(',src.name,'-',dest.name,')') as cancel_bus")
                )
                ->where('bus_cancelled.bus_operator_id', $operator->id)
                ->where('bus_cancelled_date.cancelled_date', $date)
                ->groupBy(
                    'bus.id',
                    'bus.bus_number',
                    'bus.name',
                    'src.name',
                    'dest.name'
                )
                ->get();

            return $operator;
        });

        return $operators;
    }


    public function activeOperator()
    {
        DB::beginTransaction();
        try {
            $operator = BusOperator::where("status", 1)
                ->select("operator_name", "contact_number", "organisation_name")
                ->get();

            DB::commit();
            return $this->successResponse($operator, "All active Operator");
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function activeBus()
    {
        DB::beginTransaction();
        try {
            $operator = Bus::where("status", 1)
                ->select("name as bus_name", "bus_number")
                ->get();
            DB::commit();
            return $this->successResponse($operator, "All active Buses");
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function allPassenger(Request $request)
    {
        // $date = "2025-11-01";
        $date = $request->date;
        $booking = BookingDetail::join('booking', 'booking.id', '=', 'booking_detail.booking_id')
            ->join('location as source', 'source.id', '=', 'booking.source_id')
            ->join('location as dest', 'dest.id', '=', 'booking.destination_id')
            ->join('users', 'users.id', '=', 'booking.users_id')
            // ->whereDate('booking.journey_dt', $date)
            ->select(
                'users.email',
                'users.phone',
                'users.name',
                'source.name as source_name',
                'dest.name as dest_name',
            )
            ->limit(20)
            ->get();

        return response()->json(["status" => 200, "data" => $booking]);
    }

    public function paymentReport(Request $request)
    {
        // $date = "2025-06-10";
        $defaultStart = Carbon::now()->toDateString();
        $date = $request->start_date ?? $defaultStart;
        $order = $request->order ?? "DESC";
        $report = Booking::where("status", 1)->select(
            DB::raw("
                CASE
                    WHEN user_id != 559 AND user_id != 486 AND app_type != 'AGENT' THEN 'Customer'
                    WHEN app_type = 'AGENT' THEN 'Agent'
                    WHEN user_id = 559 AND app_type != 'AGENT' THEN 'Abhibus'
                    WHEN user_id = 486 AND app_type != 'AGENT' THEN 'Paytm'
                    ELSE 'Customer'
                END AS source
            "),
            DB::raw('ROUND(SUM(total_fare), 2) as total_fare')
        )
            ->when($date, function ($q) use ($date) {
                $q->whereDate('journey_dt', $date);
            })
            ->orderBy("total_fare", $order)
            ->groupBy('source')
            ->get();

        return response()->json(["status" => 200, "data" => $report]);
    }


    public function peakBooking(Request $request)
    {
        $fromDate = $request->start_date ?? now()->toDateString();
        $toDate   = $request->end_date ?? now()->subDays(30)->toDateString();

        $booking = Booking::where('status', 1)
            ->whereBetween('journey_dt', [$fromDate, $toDate])
            ->selectRaw('DATE(journey_dt) as journey_dt, COUNT(*) as total_booking')
            ->groupBy('journey_dt')
            ->limit(30)
            ->get();

        return response()->json(["status" => 200, "data" => $booking]);
    }


    public function monthBooking(Request $request)
    {
        $toDate   = $request->end_date ?? Carbon::now()->toDateString();
        $fromDate = $request->start_date ?? Carbon::now()->subMonths(12)->toDateString();

        // return [$toDate,$fromDate];

        $fromdate = $request->start_date ?? $fromDate;
        $todate = $request->end_date ?? $toDate;

        // return [$fromdate,$todate];
        $booking = Booking::select(
            DB::raw("YEAR(journey_dt) as year"),
            DB::raw("MONTH(journey_dt) as month_no"),
            DB::raw("MONTHNAME(journey_dt) as month_name"),
            DB::raw("COUNT(*) as total_booking")
        )
            ->where('status', 1)
            ->whereBetween('journey_dt', [$fromdate, $todate])
            ->groupBy(
                DB::raw("YEAR(journey_dt)"),
                DB::raw("MONTH(journey_dt)"),
                DB::raw("MONTHNAME(journey_dt)")
            )
            ->orderBy('year')
            ->orderBy('month_no')
            ->get();

        $overallTotal = Booking::where('status', 1)
            ->whereBetween('journey_dt', [$fromDate, $toDate])
            ->count();

        return response()->json(["status" => 200, "total" => $overallTotal, "data" => $booking]);
    }

    public function monthRevenue(Request $request)
    {
        $fromdate = $request->from_j_date ?? "2025-01-01";
        $todate = $request->to_j_date ?? "2025-12-01";
        $charges = $request->revenue_by ?? "total_fare";
        $booking = Booking::select(
            DB::raw("YEAR(journey_dt) as year"),
            DB::raw("MONTH(journey_dt) as month_no"),
            DB::raw("MONTHNAME(journey_dt) as month_name"),
            DB::raw("round(sum($charges),2) as total_revenue")
        )
            ->where('status', 1)
            ->whereBetween('journey_dt', [$fromdate, $todate])
            ->groupBy(
                DB::raw("YEAR(journey_dt)"),
                DB::raw("MONTH(journey_dt)"),
                DB::raw("MONTHNAME(journey_dt)")
            )
            ->orderBy('year')
            ->orderBy('month_no')
            ->get();


        $overallRevenue = Booking::where('status', 1)
            ->whereBetween('journey_dt', [$fromdate, $todate])
            ->select(DB::raw("ROUND(SUM($charges), 2) as total"))
            ->value('total');

        return response()->json(["status" => 200, "total" => $overallRevenue, "data" => $booking]);
    }


    public function routeRevenue(Request $request)
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
            $charges = $request->revenue_by ?? "total_fare";

            $routes = DB::table('booking')
                ->join('location as src', 'src.id', '=', 'booking.source_id')
                ->join('location as dst', 'dst.id', '=', 'booking.destination_id')
                ->where("booking.status", 1)
                ->select(
                    'booking.source_id',
                    'src.name as source_name',
                    'booking.destination_id',
                    'dst.name as destination_name',
                    DB::raw("ROUND(SUM(booking.$charges), 2) as total_revenue")
                )
                ->whereBetween('booking.created_at', [$startDate, $endDate])
                ->groupBy('booking.source_id', 'booking.destination_id', 'src.name', 'dst.name')
                ->orderBy('total_revenue', $order)
                ->limit($limit)
                ->get();
            // return $routes;
            $topRoute = [];

            if (!empty($routes) && count($routes) > 0) {
                foreach ($routes as $value) {
                    $topRoute[] = [
                        "Route" => $value->source_name . '-' . $value->destination_name,
                        "TotalRevenue" => $value->total_revenue
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


    public function busRevenue(Request $request)
    {
        $bus_id = $request->bus_id;
        $operator_id = $request->operator_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        // return $operator_id;
        $booking = Booking::join('bus', 'bus.id', '=', 'booking.bus_id')
            ->join('bus_operator', 'bus_operator.id', '=', 'bus.bus_operator_id')
            ->where('bus.bus_operator_id', $operator_id)
            ->whereIn('bus.id', $bus_id)
            ->where('booking.status', 1)
            ->whereBetween('booking.created_at', [$startDate, $endDate])
            ->select(
                'bus.id as bus_id',
                'bus.name as bus_name',
                'bus_operator.organisation_name',
                DB::raw('SUM(booking.owner_fare) as total_owner_fare')
            )
            ->groupBy('bus.id', 'bus.name','bus_operator.organisation_name')
            ->get();

        return response()->json(["status"=>200,"data"=>$booking]);
    }

}
