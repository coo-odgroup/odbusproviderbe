<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CompleteReportService;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CompleteReportController extends Controller
{
    use ApiResponser;
   
    protected $completereportService; 

    
    public function __construct(CompleteReportService $completereportService)
    {
        $this->completereportService = $completereportService;        
    }

    public function getData(Request $request)
    {
        // Log::info($request);
        // exit;

        $completeData = $this->completereportService->getData($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    //Created By Chakra 26-04-2022 11:56 AM
    public function getPendingPNR(Request $request)
    {
        // Log::info($request);
        // exit;

        $completeData = $this->completereportService->getPendingPNR($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function getLessBookingUrls(){
        $result = $this->completereportService->getLessBookingUrls();
        return $this->successResponse($result,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}
