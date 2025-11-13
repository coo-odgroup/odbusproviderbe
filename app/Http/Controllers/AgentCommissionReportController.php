<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AgentCommissionReportService;
use App\Repositories\AgentCommissionReportRepository;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AgentCommissionReportController extends Controller
{
    use ApiResponser;
<<<<<<< HEAD
   
    protected $agentcommissionreportService;
    protected $agentcommissionreportRepository;

    
    public function __construct(AgentCommissionReportService $agentcommissionreportService,AgentCommissionReportRepository $agentcommissionreportRepository)
    {
        $this->agentcommissionreportService = $agentcommissionreportService;
        $this->agentcommissionreportRepository = $agentcommissionreportRepository;
=======

    protected $agentcommissionreportService;



    public function __construct(AgentCommissionReportService $agentcommissionreportService)
    {
        $this->agentcommissionreportService = $agentcommissionreportService;
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
    }

    public function getalldata(Request $request)
    {
<<<<<<< HEAD
        $completeData = $this->agentcommissionreportRepository->getData($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
=======

        $completeData = $this->agentcommissionreportService->getalldata($request);
        return $this->successResponse($completeData, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
    }

}
