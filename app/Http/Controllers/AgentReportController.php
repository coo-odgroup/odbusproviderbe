<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use App\Repositories\AgentReportRepository;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AgentReportController extends Controller
{
    use ApiResponser;

    protected $agentreportRepository;



    public function __construct(AgentReportRepository $agentreportRepository)
    {
        $this->agentreportRepository = $agentreportRepository;
    }

    public function getData(Request $request){
        try {
            $completeData = $this->agentreportRepository->getData($request);
            return $this->successResponse($completeData, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        
    }

    public function agentcancelreport(Request $request){
        try {
            $completeData = $this->agentreportRepository->agentcancelreport($request);
            return $this->successResponse($completeData, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        
    }

    public function agentCommissionreport(Request $request){
        try {
            $completeData = $this->agentreportRepository->agentCommissionreport($request);
            return $this->successResponse($completeData, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        
    }
    public function redeemableCommissions(Request $request)
{
    try {
        $data = $this->agentreportRepository->redeemableCommissions($request);
        return $this->successResponse($data, 'Redeemable commissions fetched', 200);
    } catch (\Exception $e) {
        return $this->errorResponse($e->getMessage(), 500);
    }
}


}
