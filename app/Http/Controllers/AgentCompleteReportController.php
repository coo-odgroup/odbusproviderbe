<?php

namespace App\Http\Controllers;

use App\Repositories\AgentCompleteReportRepository;
use Illuminate\Http\Request;
use App\Services\AgentCompleteReportService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AgentCompleteReportController extends Controller
{
    use ApiResponser;
   
    protected $agentcompletereportService; 
    protected $agentcompletereportRepository;
    
 

    
    public function __construct(AgentCompleteReportService $agentcompletereportService,
                                AgentCompleteReportRepository $agentcompletereportRepository)
    {
        $this->agentcompletereportService = $agentcompletereportService; 
        $this->agentcompletereportRepository = $agentcompletereportRepository;       
    }

    // public function getalldata(Request $request)
    // {
    //     $completeData = $this->agentcompletereportService->getalldata($request);
    //     return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }

     public function getalldata(Request $request)
    {
        $completeData = $this->agentcompletereportRepository->getData($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}
