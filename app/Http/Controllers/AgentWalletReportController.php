<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AgentWalletReportService;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AgentWalletReportController extends Controller
{
    use ApiResponser;
   
    protected $agentwalletreportService; 
 

    
    public function __construct(AgentWalletReportService $agentwalletreportService)
    {
        $this->agentwalletreportService = $agentwalletreportService;        
    }

    public function getalldata(Request $request)
    {
        $completeData = $this->agentwalletreportService->getalldata($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}
