<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AgentCancelTicketReportService;
use App\Repositories\AgentCancelTicketReportRepository;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AgentCancelTicketReportController extends Controller
{
    use ApiResponser;
   
    protected $agentCancelTicketReportService; 
    protected $agentCancelTicketReportRepository;
 

    
    public function __construct(AgentCancelTicketReportService $agentCancelTicketReportService,
                                AgentCancelTicketReportRepository $agentCancelTicketReportRepository)
    {
        $this->agentCancelTicketReportService = $agentCancelTicketReportService; 
        $this->agentCancelTicketReportRepository = $agentCancelTicketReportRepository;       
    }

    // public function getalldata(Request $request)
    // {
    //     $completeData = $this->agentCancelTicketReportService->getalldata($request);
    //     return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }
    public function getalldata(Request $request)
    {
        $completeData = $this->agentCancelTicketReportRepository->getData($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}
