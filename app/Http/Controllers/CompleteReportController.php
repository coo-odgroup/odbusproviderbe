<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CompleteReportService;

use Illuminate\Support\Facades\Validator;
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


    public function getAll()
    {
        $completeData = $this->completereportService->getAll();
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function getData(Request $request)
    {
        // Log:: info($request); exit;
        $completeData = $this->completereportService->getData($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}