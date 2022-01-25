<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DashboardService;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    use ApiResponser;
   
    protected $dashboardService;    
    
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;        
    }

    public function getAll(Request $request)
    {
        $dashboarddata = $this->dashboardService->getAll($request);
        return $this->successResponse($dashboarddata,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
     public function getAllAgentData(Request $request)
    {
        $dashboarddata = $this->dashboardService->getAllAgentData($request);
        return $this->successResponse($dashboarddata,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function getRoute(Request $request)
    {
        $routedata = $this->dashboardService->getRoute($request);
        return $this->successResponse($routedata,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    
    public function getOperator()
    {
        $operatordata = $this->dashboardService->getOperator();
        return $this->successResponse($operatordata,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 

    public function getticketstatics()
    {
        $ticketstaticsdata = $this->dashboardService->getticketstatics();
        return $this->successResponse($ticketstaticsdata,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function getbookingbydevice()
    {
        $bookingbydevicedata = $this->dashboardService->getbookingbydevice();
        return $this->successResponse($bookingbydevicedata,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    
    public function getpnrstatics(Request $request)
    {
        $pnrstaticsdata = $this->dashboardService->getpnrstatics($request);
        return $this->successResponse($pnrstaticsdata,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}