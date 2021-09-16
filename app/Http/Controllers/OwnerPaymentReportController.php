<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OwnerPaymentReportService;

use Illuminate\Support\Facades\Validator;
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

    public function getAll()
    {
        $extraseatopen = $this->ownerpaymentreportService->getAll();
        return $this->successResponse($extraseatopen,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}