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
    public function getPnrDetailsForSms(Request $request)
    {
         $pnr_details = $this->ticketInformationService->getPnrDetailsForSms($request);
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
    
    public function getDetailsSms(Request $request)
    {
        $sms_details = $this->ticketInformationService->getDetailsSms($request);
        return $this->successResponse($sms_details,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 

    public function getBookingID(Request $request)
    {
        $BookingID = $this->ticketInformationService->getBookingID($request);
        return $this->successResponse($BookingID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 

    public function save_customSMS(Request $request)
    {
        $savedata = $this->ticketInformationService->save_customSMS($request);
        return $this->successResponse($savedata,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 
}