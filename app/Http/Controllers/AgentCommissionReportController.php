<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AgentCommissionReportService;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AgentCommissionReportController extends Controller
{
    use ApiResponser;
   
    protected $agentcommissionreportService; 
 

    
    public function __construct(AgentCommissionReportService $agentcommissionreportService)
    {
        $this->agentcommissionreportService = $agentcommissionreportService;        
    }

    public function getalldata(Request $request)
    {
        $completeData = $this->agentcommissionreportService->getalldata($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}
