<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AgentCancelTicketReportService;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AgentCancelTicketReportController extends Controller
{
    use ApiResponser;
   
    protected $agentCancelTicketReportService; 
 

    
    public function __construct(AgentCancelTicketReportService $agentCancelTicketReportService)
    {
        $this->agentCancelTicketReportService = $agentCancelTicketReportService;        
    }

    public function getalldata(Request $request)
    {
        $completeData = $this->agentCancelTicketReportService->getalldata($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}
