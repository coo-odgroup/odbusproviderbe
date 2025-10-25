<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FailledTransactionReportService;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class FailledTransactionReportController extends Controller
{
    use ApiResponser;
   
    protected $failledtransactionreportService;    
    
    public function __construct(FailledTransactionReportService $failledtransactionreportService)
    {
        $this->failledtransactionreportService = $failledtransactionreportService;
        
    }
    public function getData(Request $request)
    {
        $failledtransactionData = $this->failledtransactionreportService->getData($request);
        return $this->successResponse($failledtransactionData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}