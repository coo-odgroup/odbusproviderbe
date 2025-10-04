<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DashboardService;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use App\Repositories\DashboardRepository;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    use ApiResponser;
   
    protected $dashboardService;    
    protected $dashboardRepository;

    
    public function __construct(DashboardService $dashboardService,
                                DashboardRepository $dashboardRepository)
    {
        $this->dashboardService = $dashboardService;  
        $this->dashboardRepository = $dashboardRepository;      
    }

    public function getAll(Request $request)
    {
        //$dashboarddata = $this->dashboardService->getAll($request);
        $dashboarddata = $this->dashboardRepository->getAll($request);
        return $this->successResponse($dashboarddata,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
     public function getAllAgentData(Request $request)
    {
        //$dashboarddata = $this->dashboardService->getAllAgentData($request);
        $dashboarddata = $this->dashboardRepository->getAllAgentData($request);
        return $this->successResponse($dashboarddata,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function getRoute(Request $request)
    {
        //$routedata = $this->dashboardService->getRoute($request);
        $routedata = $this->dashboardRepository->getRoute($request);
        return $this->successResponse($routedata,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    
    public function getOperator()
    {
        //$operatordata = $this->dashboardService->getOperator();
        $operatordata = $this->dashboardRepository->getOperator();
        return $this->successResponse($operatordata,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 

    public function getticketstatics()
    {
        //$ticketstaticsdata = $this->dashboardService->getticketstatics();
        $ticketstaticsdata = $this->dashboardRepository->getticketstatics();
        return $this->successResponse($ticketstaticsdata,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function getbookingbydevice()
    {
        //$bookingbydevicedata = $this->dashboardService->getbookingbydevice();
        $bookingbydevicedata = $this->dashboardRepository->getbookingbydevice();
        return $this->successResponse($bookingbydevicedata,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    
    public function getpnrstatics(Request $request)
    {
        //$pnrstaticsdata = $this->dashboardService->getpnrstatics($request);
        $pnrstaticsdata = $this->dashboardRepository->getpnrstatics($request);

        return $this->successResponse($pnrstaticsdata,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}