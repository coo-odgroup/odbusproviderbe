<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OwnerPaymentReportService;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OwnerPaymentReportController extends Controller
{
    use ApiResponser;   
   
    protected $ownerpaymentreportService;    
    
    public function __construct(OwnerPaymentReportService $ownerpaymentreportService)
    {
        $this->ownerpaymentreportService = $ownerpaymentreportService;        
    }

    public function getData(Request $request)
    {
        $ownerpayment = $this->ownerpaymentreportService->getData($request);
        return $this->successResponse($ownerpayment,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}