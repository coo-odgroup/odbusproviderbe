<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AssocAssignReportService;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AssocAssignReportController extends Controller
{
    use ApiResponser;
   
    protected $AssocAssignReportService; 
 

    
    public function __construct(AssocAssignReportService $AssocAssignReportService)
    {
        $this->AssocAssignReportService = $AssocAssignReportService;        
    }

    public function getAssignBusData(Request $request)
    {
        $completeData = $this->AssocAssignReportService->getAssignBusData($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function getAssignAgentData(Request $request)
    {
        $completeData = $this->AssocAssignReportService->getAssignAgentData($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function getAssignOperatorData(Request $request)
    {
        $completeData = $this->AssocAssignReportService->getAssignOperatorData($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}
