<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Bus;
use Illuminate\Http\Request;
use App\Repositories\DashboardRepository;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    use ApiResponser;
    protected $dashboardRepository;

    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function getAll(Request $request)
    {
        $dashboarddata = $this->dashboardRepository->getAll($request);
        return $this->successResponse($dashboarddata, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }
    public function getAllAgentData(Request $request)
    {
        $dashboarddata = $this->dashboardRepository->getAllAgentData($request);
        return $this->successResponse($dashboarddata, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getRoute(Request $request)
    {
        $routedata = $this->dashboardRepository->getRoute($request);
        return $this->successResponse($routedata, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getOperator()
    {
        $operatordata = $this->dashboardRepository->getOperator();
        return $this->successResponse($operatordata, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getticketstatics()
    {
        $ticketstaticsdata = $this->dashboardRepository->getticketstatics();
        return $this->successResponse($ticketstaticsdata, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getbookingbydevice()
    {
        $bookingbydevicedata = $this->dashboardRepository->getbookingbydevice();
        return $this->successResponse($bookingbydevicedata, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getpnrstatics(Request $request)
    {
        $pnrstaticsdata = $this->dashboardRepository->getpnrstatics($request);
        return $this->successResponse($pnrstaticsdata, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function AlertDuplicateBooking()
    {
        $sql = "SELECT 
            bkg.journey_dt,
            bkg.bus_id,
            bus.name       AS bus_name,
            bus.bus_number AS bus_number,
            s.seatText     AS seat_no,
            COUNT(*)       AS total_bookings,
            GROUP_CONCAT(DISTINCT bkg.pnr)   AS pnrs,
            GROUP_CONCAT(DISTINCT u.name)    AS customer_names,
            GROUP_CONCAT(DISTINCT u.phone)   AS mobile_numbers,
            GROUP_CONCAT(DISTINCT u.email)   AS email_ids
        FROM booking bkg
        JOIN booking_detail bd ON bd.booking_id = bkg.id
        JOIN bus_seats bs      ON bs.id = bd.bus_seats_id
        JOIN seats s           ON s.id = bs.seats_id
        JOIN bus               ON bus.id = bkg.bus_id
        LEFT JOIN users u      ON u.id = bkg.users_id
        WHERE 
            bkg.status IN (1,4)   -- Booked / Hold
            AND bd.status = 1    -- Seat booked
            and bkg.journey_dt >= CURDATE()
        GROUP BY 
            bkg.journey_dt,
            bkg.bus_id,
            bus.name,
            bus.bus_number,
            bs.seats_id,
            s.seatText
        HAVING COUNT(*) > 1
        ORDER BY bkg.journey_dt DESC";

        return $result = DB::select($sql);

        // foreach ($result as $row) {
        //     echo $row->journey_dt . "<br>";
        //     echo $row->bus_name . " (" . $row->bus_number . ")<br>";
        //     echo "Seat: " . $row->seat_no . "<br>";
        //     echo "PNRs: " . $row->pnrs . "<br>";
        //     echo "Mobiles: " . $row->mobile_numbers . "<br>";
        //     echo "Emails: " . $row->email_ids . "<hr>";
        // }
    }


    //add by sahil
    function activeBus($id)
    {
        $bus = Bus::where('bus_operator_id', $id)
            ->where('status', 1)
            ->select('id', 'bus_operator_id', 'name', 'bus_number')
            ->get();
        return $bus;
    }

    public function operatorDashbord(Request $request)
    {
        $operatordata = $this->activeBus($request->operator_id);
        $busIds = $operatordata->pluck('id');

        $activeBusCount = $busIds->count();

        $filter = $request->rangeFor ?? 'Today';

        $fromDate = Carbon::today();
        $toDate   = Carbon::today();

        if ($filter === 'This Week') {
            $fromDate = Carbon::today()->subDays(6);
        }

        if ($filter === 'This Month') {
            $fromDate = Carbon::now()->startOfMonth();
            $toDate   = Carbon::now();
        }

        $totalBookingCount = DB::table('booking')
            ->whereIn('bus_id', $busIds)
            ->where('status', 1)
            ->whereBetween('created_at', [
                $fromDate->startOfDay(),
                $toDate->endOfDay()
            ])
            ->count();

        $totalPnrcancel = DB::table('booking')
            ->whereIn('bus_id', $busIds)
            ->where('status', 2)
            ->whereBetween('created_at', [
                $fromDate->startOfDay(),
                $toDate->endOfDay()
            ])
            ->count();

        $upcomingPnr = DB::table('booking')
            ->whereIn('bus_id', $busIds)
            ->where('status', 1)
            ->whereDate('journey_dt', '>', Carbon::today())
            ->count();

        return response()->json([
            "status" => 200,
            "data" => [
                'active_bus_count' => $activeBusCount,
                'total_pnr' => $totalBookingCount,
                'total_pnr_cancel' => $totalPnrcancel,
                'upcoming_pnr' => $upcomingPnr,
                'filter' => $filter,
                'from_date' => $fromDate->toDateString(),
                'to_date' => $toDate->toDateString(),
            ]
        ]);
    }


    //OPERATOR BUS WISE BOOKING
    // public function opBooking(Request $request)
    // {
    //     $operatorId = $request->operator_id;

    //     $filter = $request->rangeFor ?? 'Today';

    //     $fromDate = Carbon::today();
    //     $toDate   = Carbon::today();

    //     // $fromDate = '2025-01-01 00:00:00';
    //     // $toDate   = '2025-06-01 23:59:59';


    //     if ($filter === 'This Week') {
    //         $fromDate = Carbon::today()->subDays(6);
    //     }

    //     if ($filter === 'This Month') {
    //         $fromDate = Carbon::now()->startOfMonth();
    //         $toDate   = Carbon::now();
    //     }

    //     // Get active buses
    //     $busIds = $this->activeBus($operatorId)->pluck('id');

    //     if ($busIds->isEmpty()) {
    //         return response()->json([
    //             'status' => 200,
    //             'data' => []
    //         ]);
    //     }

    //     $ticketPriceSub = DB::table('ticket_price')
    //         ->select('bus_id', DB::raw('MIN(id) as tp_id'))
    //         ->groupBy('bus_id');

    //     $booking = Booking::query()
    //         ->join('bus', 'bus.id', '=', 'booking.bus_id')
    //         ->joinSub($ticketPriceSub, 'tp1', function ($join) {
    //             $join->on('tp1.bus_id', '=', 'bus.id');
    //         })
    //         ->join('ticket_price as tp', 'tp.id', '=', 'tp1.tp_id')
    //         ->join('location as src', 'src.id', '=', 'tp.source_id')
    //         ->join('location as dst', 'dst.id', '=', 'tp.destination_id')
    //         ->whereIn('booking.bus_id', $busIds)
    //         ->whereBetween('booking.created_at', [$fromDate, $toDate])
    //         ->select(
    //             'booking.bus_id',
    //             'bus.name as bus_name',
    //             'bus.bus_number',
    //             'src.id as source_id',
    //             'src.name as source',
    //             'dst.id as destination_id',
    //             'dst.name as destination',
    //             DB::raw('COUNT(booking.id) as booking_count')
    //         )
    //         ->groupBy(
    //             'booking.bus_id',
    //             'bus.name',
    //             'bus.bus_number',
    //             'src.id',
    //             'src.name',
    //             'dst.id',
    //             'dst.name'
    //         )
    //         ->get();

    //     return response()->json([
    //         'status' => 200,
    //         'data' => $booking
    //     ]);
    // }

    public function opBooking(Request $request)
    {
        $operatorId = $request->operator_id;
        $filter = $request->rangeFor ?? 'Today';

        $fromDate = Carbon::today()->startOfDay();
        $toDate   = Carbon::today()->endOfDay();

        if ($filter === 'This Week') {
            $fromDate = Carbon::today()->subDays(6)->startOfDay();
            $toDate   = Carbon::today()->endOfDay();
        }

        if ($filter === 'This Month') {
            $fromDate = Carbon::now()->startOfMonth()->startOfDay();
            $toDate   = Carbon::now()->endOfDay();
        }

        // Format dates
        $fromDate = $fromDate->format('Y-m-d H:i:s');
        $toDate   = $toDate->format('Y-m-d H:i:s');

        // Get active buses
        $busIds = $this->activeBus($operatorId)->pluck('id')->toArray();

        if (empty($busIds)) {
            return response()->json([
                'status' => 200,
                'data' => []
            ]);
        }

        $ticketPriceSub = DB::table('ticket_price')
            ->select('bus_id', DB::raw('MIN(id) as tp_id'))
            ->groupBy('bus_id');

        $booking = Booking::query()
            ->join('bus', 'bus.id', '=', 'booking.bus_id')
            ->joinSub($ticketPriceSub, 'tp1', function ($join) {
                $join->on('tp1.bus_id', '=', 'bus.id');
            })
            ->join('ticket_price as tp', 'tp.id', '=', 'tp1.tp_id')
            ->join('location as src', 'src.id', '=', 'tp.source_id')
            ->join('location as dst', 'dst.id', '=', 'tp.destination_id')
            ->whereIn('booking.bus_id', $busIds)
            ->whereBetween('booking.created_at', [$fromDate, $toDate])
            ->select(
                'booking.bus_id',
                'bus.name as bus_name',
                'bus.bus_number',
                'src.id as source_id',
                'src.name as source',
                'dst.id as destination_id',
                'dst.name as destination',
                DB::raw('COUNT(booking.id) as booking_count')
            )
            ->groupBy(
                'booking.bus_id',
                'bus.name',
                'bus.bus_number',
                'src.id',
                'src.name',
                'dst.id',
                'dst.name'
            )
            ->get();

        return response()->json([
            'status' => 200,
            'data' => $booking
        ]);
    }

    //OPERATOR BUS WISE REVENUE
    public function opRevenue(Request $request)
    {
        $operatorId = $request->operator_id;
        $filter = $request->rangeFor ?? 'Today';

        $fromDate = Carbon::today()->startOfDay();
        $toDate   = Carbon::today()->endOfDay();

        if ($filter === 'This Week') {
            $fromDate = Carbon::today()->subDays(6)->startOfDay();
            $toDate   = Carbon::today()->endOfDay();
        }

        if ($filter === 'This Month') {
            $fromDate = Carbon::now()->startOfMonth()->startOfDay();
            $toDate   = Carbon::now()->endOfDay();
        }

        // Format dates for query
        $fromDate = $fromDate->format('Y-m-d H:i:s');
        $toDate   = $toDate->format('Y-m-d H:i:s');

        // Get active bus IDs for operator
        $busIds = $this->activeBus($operatorId)->pluck('id');

        if ($busIds->isEmpty()) {
            return response()->json([
                'status' => 200,
                'data'   => []
            ]);
        }

        $ticketPriceSub = DB::table('ticket_price')
            ->select('bus_id', DB::raw('MIN(id) as tp_id'))
            ->groupBy('bus_id');

        $booking = Booking::query()
            ->join('bus', 'bus.id', '=', 'booking.bus_id')
            ->joinSub($ticketPriceSub, 'tp1', function ($join) {
                $join->on('tp1.bus_id', '=', 'bus.id');
            })
            ->join('ticket_price as tp', 'tp.id', '=', 'tp1.tp_id')
            ->join('location as src', 'src.id', '=', 'tp.source_id')
            ->join('location as dst', 'dst.id', '=', 'tp.destination_id')
            ->whereIn('booking.bus_id', $busIds)
            ->whereBetween('booking.created_at', [$fromDate, $toDate])
            ->select(
                'booking.bus_id',
                'bus.name as bus_name',
                'bus.bus_number',
                'src.id as source_id',
                'src.name as source',
                'dst.id as destination_id',
                'dst.name as destination',
                DB::raw('SUM(booking.owner_fare) as total_revenue')
            )
            ->groupBy(
                'booking.bus_id',
                'bus.name',
                'bus.bus_number',
                'src.id',
                'src.name',
                'dst.id',
                'dst.name'
            )
            ->get();

        return response()->json([
            'status' => 200,
            'data'   => $booking
        ]);
    }
}
