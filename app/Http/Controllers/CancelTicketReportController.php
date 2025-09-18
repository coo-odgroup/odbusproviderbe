<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Services\CancelTicketReportService;
use App\Repositories\CancelTicketReportRepository;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CancelTicketReportController extends Controller
{
    use ApiResponser;
   
    //protected $cancelticketreportService;  
    protected  $cancelticketreportRepository;
    
    public function __construct(//CancelTicketReportService $cancelticketreportService
                                CancelTicketReportRepository $cancelticketreportRepository)
    {
        //$this->cancelticketreportService = $cancelticketreportService;
       $this->cancelticketreportRepository = $cancelticketreportRepository;
    } 
    public function getData(Request $request)
    // {
    //     $cancelticketData = $this->cancelticketreportService->getData($request);
    //     return $this->successResponse($cancelticketData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }
    {
        $cancelticketData = $this->cancelticketreportRepository->getData($request);
        return $this->successResponse($cancelticketData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}