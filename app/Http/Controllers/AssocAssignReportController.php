<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AssocAssignReportService;
use App\Repositories\AssocAssignReportRepository;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AssocAssignReportController extends Controller
{
    use ApiResponser;
   
    protected $AssocAssignReportService;
    protected $AssocAssignReportRepository; 
 

    
    public function __construct(AssocAssignReportService $AssocAssignReportService,
                                AssocAssignReportRepository $AssocAssignReportRepository)
    {
        $this->AssocAssignReportService = $AssocAssignReportService; 
        $this->AssocAssignReportRepository = $AssocAssignReportRepository;       
    }

    // public function getAssignBusData(Request $request)
    // {
    //     $completeData = $this->AssocAssignReportService->getAssignBusData($request);
    //     return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // } 

    public function getAssignBusData(Request $request)
    {
        $completeData = $this->AssocAssignReportRepository->getAssignBusData($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 

    // public function getAssignAgentData(Request $request)
    // {
    //     $completeData = $this->AssocAssignReportService->getAssignAgentData($request);
    //     return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }

    public function getAssignAgentData(Request $request)
    {
        $completeData = $this->AssocAssignReportRepository->getAssignAgentData($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    // public function getAssignOperatorData(Request $request)
    // {
    //     $completeData = $this->AssocAssignReportService->getAssignOperatorData($request);
    //     return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }

    public function getAssignOperatorData(Request $request)
    {
        $completeData = $this->AssocAssignReportRepository->getAssignOperatorData($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}
