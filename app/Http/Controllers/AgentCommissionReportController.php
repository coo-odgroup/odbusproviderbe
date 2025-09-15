<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AgentCommissionReportService;
use App\Repositories\AgentCommissionReportRepository;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AgentCommissionReportController extends Controller
{
    use ApiResponser;
   
    protected $agentcommissionreportService; 
    protected $agentcommissionreportRepository;
 

    
    public function __construct(AgentCommissionReportService $agentcommissionreportService,
                                AgentCommissionReportRepository $agentcommissionreportRepository)
    {
        $this->agentcommissionreportService = $agentcommissionreportService; 
        $this->agentcommissionreportRepository = $agentcommissionreportRepository;       
    }

    // public function getalldata(Request $request)
    // {
       
    //     $completeData = $this->agentcommissionreportService->getalldata($request);
    //     return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }

     public function getalldata(Request $request)
    {
       
        $completeData = $this->agentcommissionreportRepository->getalldata($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}
