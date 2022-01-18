<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TicketInformationService;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;


class TicketInformationController extends Controller
{
    use ApiResponser;   
    protected $ticketInformationService;
    
    public function __construct(TicketInformationService $ticketInformationService)
    {
        $this->ticketInformationService = $ticketInformationService;        
    }

    public function getpnrdetails(Request $request)
    {
      // return $request;
         $pnr_details = $this->ticketInformationService->getpnrdetails($request);
        return $this->successResponse($pnr_details,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 

    public function cancelticket(Request $request)
    {
      // return $request;
         $pnr_details = $this->ticketInformationService->cancelticket($request);
        return $this->successResponse($pnr_details,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }  

    public function cancelticketdata(Request $request)
    {
      // return $request;
         $pnr_details = $this->ticketInformationService->cancelticketdata($request);
        return $this->successResponse($pnr_details,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 
     public function adjustticketdata(Request $request)
    {
      // return $request;
         $pnr_details = $this->ticketInformationService->adjustticketdata($request);
        return $this->successResponse($pnr_details,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 

    public function adjustticket(Request $request)
    {
      // return $request;
         $pnr_details = $this->ticketInformationService->adjustticket($request);
        return $this->successResponse($pnr_details,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }    

}