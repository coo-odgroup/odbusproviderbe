<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CancelTicketReportService;

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
   
    protected $cancelticketreportService;    
    
    public function __construct(CancelTicketReportService $cancelticketreportService)
    {
        $this->cancelticketreportService = $cancelticketreportService;
        
    }


    public function getAll()
    {
        $cancelticketData = $this->cancelticketreportService->getAll();
        return $this->successResponse($cancelticketData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}