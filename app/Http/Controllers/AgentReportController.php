<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AgentReportService;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AgentReportController extends Controller
{
    use ApiResponser;
   
    protected $agentreportService; 
 

    
    public function __construct(AgentReportService $agentreportService)
    {
        $this->agentreportService = $agentreportService;        
    }

    public function getData(Request $request)
    {
        $completeData = $this->agentreportService->getData($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function agentcancelreport(Request $request)
    {
        $completeData = $this->agentreportService->agentcancelreport($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function agentCommissionreport(Request $request)
    {
        $completeData = $this->agentreportService->agentCommissionreport($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}
