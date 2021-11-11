<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AgentCompleteReportService;
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
 

    
    public function __construct(AgentCompleteReportService $agentcompletereportService)
    {
        $this->agentcompletereportService = $agentcompletereportService;        
    }

    public function getalldata(Request $request)
    {
        $completeData = $this->agentcompletereportService->getalldata($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}
