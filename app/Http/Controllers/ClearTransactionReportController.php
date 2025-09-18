<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Services\ClearTransactionReportService;
use App\Repositories\ClearTransactionReportRepository;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ClearTransactionReportController extends Controller
{
    use ApiResponser;
   
    // protected $cleartransactionreportService; 
    protected $cleartransactionreportRepository;   
    
    public function __construct(//ClearTransactionReportService $cleartransactionreportService,
                                  ClearTransactionReportRepository $cleartransactionreportRepository)
    {
        //$this->cleartransactionreportService = $cleartransactionreportService;  
        $this->cleartransactionreportRepository = $cleartransactionreportRepository;      
    }

    // public function getAll()
    // {
    //     $extraseatopen = $this->cleartransactionreportService->getAll();
    //     return $this->successResponse($extraseatopen,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }
     public function getAll()
    {
        $extraseatopen = $this->cleartransactionreportRepository->getAll();
        return $this->successResponse($extraseatopen,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}