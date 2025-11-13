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
   
    protected $agentcommissionreportRepository;

    
    public function __construct(AgentCommissionReportRepository $agentcommissionreportRepository)
    {
        $this->agentcommissionreportRepository = $agentcommissionreportRepository;
    }

    public function getalldata(Request $request)
    {
        $completeData = $this->agentcommissionreportRepository->getData($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}
