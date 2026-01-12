<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\DashboardRepository;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
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

    public function AlertDuplicateBooking(){
        $sql="SELECT 
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
}
