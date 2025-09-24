<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiUserCancelTicketReportService;
use App\Repositories\ApiUserCancelTicketReportRepository;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiUserCancelTicketReportController extends Controller
{
    use ApiResponser;
   
    protected $apiusercancelticketreportService;  
      protected $apiusercancelticketreportRepository;    
    
    public function __construct(ApiUserCancelTicketReportService $apiusercancelticketreportService,
                                ApiUserCancelTicketReportRepository $apiusercancelticketreportRepository
    )
    {
        $this->apiusercancelticketreportService = $apiusercancelticketreportService;
        $this->apiusercancelticketreportRepository = $apiusercancelticketreportRepository;
        
    } 
    public function getData(Request $request)
    {
       // $cancelticketData = $this->apiusercancelticketreportService->getData($request);
       $cancelticketData = $this->apiusercancelticketreportRepository->getData($request);
        return $this->successResponse($cancelticketData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}