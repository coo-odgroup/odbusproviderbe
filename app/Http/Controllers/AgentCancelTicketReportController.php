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
<<<<<<< HEAD
   
    protected $agentCancelTicketReportRepository;
 
    public function __construct(AgentCancelTicketReportRepository $agentCancelTicketReportRepository)
    {
        $this->agentCancelTicketReportRepository = $agentCancelTicketReportRepository;
=======

    protected $agentCancelTicketReportService;



    public function __construct(AgentCancelTicketReportService $agentCancelTicketReportService)
    {
        $this->agentCancelTicketReportService = $agentCancelTicketReportService;
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
    }

    public function getalldata(Request $request)
    {
<<<<<<< HEAD
        $completeData = $this->agentCancelTicketReportRepository->getData($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
=======
        $completeData = $this->agentCancelTicketReportService->getalldata($request);
        return $this->successResponse($completeData, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
    }

}
