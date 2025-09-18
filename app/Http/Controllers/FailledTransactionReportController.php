<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Services\FailledTransactionReportService;
use App\Repositories\FailledTransactionReportRepository;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class FailledTransactionReportController extends Controller
{
    use ApiResponser;
   
    //protected $failledtransactionreportService;  
    protected $failledtransactionreportrepository;  
    
    public function __construct(//FailledTransactionReportService $failledtransactionreportService
                                FailedTransactionReportRepository $failledtransactionreportService)
    {
        //$this->failledtransactionreportService = $failledtransactionreportService;
        $this->failledtransactionreportrepository = $failledtransactionreportrepository;
    }
    public function getData(Request $request)
    {
        $failledtransactionData = $this->failledtransactionreportrepository->getData($request);
        return $this->successResponse($failledtransactionData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}