<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SeatOpenReportService;
use App\Repositories\SeatOpenReportRepository;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SeatOpenReportController extends Controller
{
    use ApiResponser;
   
   // protected $seatopenreportService; 
     protected $seatopenreportRepository;     
    
    public function __construct(//SeatOpenReportService $seatopenreportService
                                SeatOpenReportRepository $seatopenreportRepository )
    {
        //$this->seatopenreportService = $seatopenreportService;  
        $this->seatopenreportRepository = $seatopenreportRepository;        
    }
    // public function getData(Request $request)
    // {
    //     $seatopen = $this->seatopenreportService->getData($request);
    //     return $this->successResponse($seatopen,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }

    public function getData(Request $request)
    {
        $seatopen = $this->seatopenreportRepository->getData($request);
        return $this->successResponse($seatopen,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }





}