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
use DB;

class TicketInformationController extends Controller
{
    use ApiResponser;   
    protected $ticketInformationService;
    
    public function __construct(TicketInformationService $ticketInformationService)
    {
        $this->ticketInformationService = $ticketInformationService;        
    }

    public function failedticketadjust(Request $request)
    {
        $pnr_details = $this->ticketInformationService->failedticketadjust($request);
        return $this->successResponse($pnr_details,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    
    public function failedticketadjustdata(Request $request)
    {
        $pnr_details = $this->ticketInformationService->failedticketadjustdata($request);
        return $this->successResponse($pnr_details,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 

    public function getpnrdetails(Request $request)
    {        
        $pnr_details = $this->ticketInformationService->getpnrdetails($request);
        return $this->successResponse($pnr_details,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function getApiPnrDetails(Request $request)
    {        
        $pnr_details = $this->ticketInformationService->getApiPnrDetails($request);
        return $this->successResponse($pnr_details,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function getPnrDetailsForSms(Request $request)
    {
        $pnr_details = $this->ticketInformationService->getPnrDetailsForSms($request);
        return $this->successResponse($pnr_details,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 

    public function apicancelticket(Request $request)
    {
        $pnr_details = $this->ticketInformationService->apicancelticket($request);
        return $this->successResponse($pnr_details,"Ticket cancelled and Amount Refunded",Response::HTTP_OK);
    }

    public function cancelticket(Request $request)
    {
        $pnr_details = $this->ticketInformationService->cancelticket($request);
        return $this->successResponse($pnr_details,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }  

    public function cancelticketdata(Request $request)
    {
        $pnr_details = $this->ticketInformationService->cancelticketdata($request);
        return $this->successResponse($pnr_details,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function adjustticketdata(Request $request)
    {
        $pnr_details = $this->ticketInformationService->adjustticketdata($request);
        return $this->successResponse($pnr_details,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 

    public function adjustticket(Request $request)
    {
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

    public function GetCancelSmsToCustomer(Request $request)
    {
        $getdata = $this->ticketInformationService->GetCancelSmsToCustomer($request);
        return $this->successResponse($getdata,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 

    public function GetCancelSmsToCMO(Request $request)
    {
        $getdata = $this->ticketInformationService->GetCancelSmsToCMO($request);
        return $this->successResponse($getdata,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 

    public function save_CancelcustomSMSToCustomer(Request $request)
    {
        $savedata = $this->ticketInformationService->save_CancelcustomSMSToCustomer($request);
        return $this->successResponse($savedata,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 

    public function save_CancelcustomSMSToCMO(Request $request)
    {
        $savedata = $this->ticketInformationService->save_CancelcustomSMSToCMO($request);
        return $this->successResponse($savedata,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 

    public function getEmailID(Request $request)
    {
        $EmailID = $this->ticketInformationService->getEmailID($request);
        return $this->successResponse($EmailID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function sendEmailToBooking(Request $request)
    {
        $result = $this->ticketInformationService->sendEmailToBooking($request);
        return $this->successResponse($result,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function sendEmailToCustomer(Request $request)
    {
        $result = $this->ticketInformationService->sendEmailToCustomer($request);
        return $this->successResponse($result,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function sendCancelEmailToSupport(Request $request)
    {
        $result = $this->ticketInformationService->sendCancelEmailToSupport($request);
        return $this->successResponse($result,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function PaytmBookingCancel($pnr)
    {
        $curl = curl_init();

        $bdt=DB::table('booking as b')->select('b.journey_dt','bs.bus_operator_id')->leftjoin('bus as bs','b.bus_id','=','bs.id')->where('pnr',$pnr)->first();

        $rr=[
            "pnr"=> "'$pnr'",
            "doj"=> "'$bdt->journey_dt'",
            "operator_id"=> "'$bdt->bus_operator_id'",
            "operator_pnr"=> "'$pnr'",
            "primary_passenger"=> null
       ];
       
        curl_setopt_array($curl, array(
          CURLOPT_URL => env('PAYTM_PNR_CANCEL_URL'),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
            "pnr_list": [
                {
                          "pnr": "'.$pnr.'",
                          "doj": "'.$bdt->journey_dt.'",
                          "operator_id": "'.$bdt->bus_operator_id.'",
                          "operator_pnr": "'.$pnr.'",
                          "primary_passenger": null
                }
            ]
        }',
          CURLOPT_HTTPHEADER => array(
            'VerifyKey: 6632596ff74049b8ad8c4a923e4a76c9',
            'UserId: 68',
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

       
        dd($response,$rr);
    }

    

  
}