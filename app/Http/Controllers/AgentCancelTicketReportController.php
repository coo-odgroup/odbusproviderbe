<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AgentCancelTicketReportRepository;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AgentCancelTicketReportController extends Controller
{
    use ApiResponser;
   
    protected $agentCancelTicketReportRepository;
 
    public function __construct(AgentCancelTicketReportRepository $agentCancelTicketReportRepository)
    {
        $this->agentCancelTicketReportRepository = $agentCancelTicketReportRepository;
    }

    public function getalldata(Request $request)
    {
        $completeData = $this->agentCancelTicketReportRepository->getData($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}
