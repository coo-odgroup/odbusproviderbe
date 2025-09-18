<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Services\OwnerPaymentReportService;
use App\Repositories\OwnerPaymentReportRepository;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OwnerPaymentReportController extends Controller
{
    use ApiResponser;   
   
    //protected $ownerpaymentreportService;
    protected $ownerpaymentreporRepository;    
    
    public function __construct(//OwnerPaymentReportService $ownerpaymentreportService 
        OwnerPaymentReportRepository $ownerpaymentreportRepository)
    {
        //$this->ownerpaymentreportService = $ownerpaymentreportService; 
        $this->ownerpaymentreportService = $ownerpaymentreportRepository;       
    }

    // public function getData(Request $request)
    // {
    //     $ownerpayment = $this->ownerpaymentreportService->getData($request);
    //     return $this->successResponse($ownerpayment,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }


     public function getData(Request $request)
    {
        $ownerpayment = $this->ownerpaymentreportRepository->getData($request);
        return $this->successResponse($ownerpayment,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
}