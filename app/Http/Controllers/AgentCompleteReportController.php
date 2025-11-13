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
<<<<<<< HEAD
   
    protected $agentcompletereportRepository;
 

    
    public function __construct(AgentCompleteReportRepository $agentcompletereportRepository)
    {
        $this->agentcompletereportRepository = $agentcompletereportRepository;
=======

    protected $agentcompletereportService;



    public function __construct(AgentCompleteReportService $agentcompletereportService)
    {
        $this->agentcompletereportService = $agentcompletereportService;
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
    }

    public function getalldata(Request $request)
    {
<<<<<<< HEAD
        $completeData = $this->agentcompletereportRepository->getData($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
=======
        $completeData = $this->agentcompletereportService->getalldata($request);
        return $this->successResponse($completeData, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
    }

}
