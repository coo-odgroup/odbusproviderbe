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
   
    protected $deshboardService;    
    
    public function __construct(DashboardService $deshboardService)
    {
        $this->deshboardService = $deshboardService;        
    }

    public function getAll()
    {
        $dashboarddata = $this->deshboardService->getAll();
        return $this->successResponse($dashboarddata,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function getRoute()
    {
        $routedata = $this->deshboardService->getRoute();
        return $this->successResponse($routedata,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    
    public function getOperator()
    {
        $operatordata = $this->deshboardService->getOperator();
        return $this->successResponse($operatordata,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}