<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AgentCompleteReportRepository;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AgentCompleteReportController extends Controller
{
    use ApiResponser;
   
    protected $agentcompletereportRepository;
 

    
    public function __construct(AgentCompleteReportRepository $agentcompletereportRepository)
    {
        $this->agentcompletereportRepository = $agentcompletereportRepository;
    }

    public function getalldata(Request $request)
    {
        $completeData = $this->agentcompletereportRepository->getData($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}
