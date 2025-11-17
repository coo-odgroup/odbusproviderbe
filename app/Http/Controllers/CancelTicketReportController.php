<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    
        protected $cancelticketreportRepository;


    public function __construct(CancelTicketReportRepository $cancelticketreportRepository)
    {
       
         $this->cancelticketreportRepository = $cancelticketreportRepository;

    }
    public function getData(Request $request)
    {
       
        $cancelticketData = $this->cancelticketreportRepository->getData($request);
        return $this->successResponse($cancelticketData, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

}
