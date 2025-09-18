<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Services\BusCancellationReportService;
use App\Repositories\BusCancellationReportRepository;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;


//created by Subhasis mohanty in 18 sep 2025


class BusCancellationReportController extends Controller
{
    use ApiResponser;
   
    //protected $buscancellationreportService;  
    protected $buscancellationreportRepository;  
    
    public function __construct(//BusCancellationReportService $buscancellationreportService
                                BusCancellationReportRepository $buscancellationreportService)
    {
        //$this->buscancellationreportService = $buscancellationreportService;
        $this->buscancellationreportRepository = $buscancellationreportService;        
    }

    // public function getData(Request $request)
    // {
    //     $buscancel = $this->buscancellationreportService->getData($request);
    //     return $this->successResponse($buscancel,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }

    public function getData(Request $request)
    {
        $buscancel = $this->buscancellationreportRepository->getData($request);
        return $this->successResponse($buscancel,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}