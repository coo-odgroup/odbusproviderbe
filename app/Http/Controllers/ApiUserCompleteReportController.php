<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiUserCompleteReportService;
use App\Repositories\ApiUserCompleteReportRepository;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiUserCompleteReportController extends Controller
{
    use ApiResponser;
   
    protected $apiusercompletereportService; 
    protected $apiusercompletereportRepository;

    
    public function __construct(ApiUserCompleteReportService $apiusercompletereportService,
                            ApiUserCompleteReportRepository $apiusercompletereportRepository)
    {
        $this->apiusercompletereportService = $apiusercompletereportService;  
        $this->apiusercompletereportRepository = $apiusercompletereportRepository;      
    }

    public function getData(Request $request)
    {
        // Log::info($request);
        // exit;

       // $completeData = $this->apiusercompletereportService->getData($request);
       $completeData =  $this->apiusercompletereportRepository->getData($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
}
