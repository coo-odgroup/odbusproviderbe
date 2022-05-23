<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\Booking;
use App\Models\Location;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class ApiClientReportRepository
{
    protected $booking;
    protected $location;
    protected $bus;

    public function __construct(Booking $booking ,Location $location ,Bus $bus)
    {       
        $this->booking = $booking;       
        $this->location = $location;       
        $this->bus = $bus;       
    }    
    
    public function getAllData($request)
    {
        return $request;      

    }



    public function getAllCancelData($request)
    {
        return $request;      

    }


    public function datewiseroute($request)
    {
        // log::info($request->source_id);
        // exit;
        // return $request;  
        /////// first check the seat is booked or on hold before cancelling pnr and insert new record to booking table
        $dt = '';
        $client = new \GuzzleHttp\Client();       

        $api_url = Config::get('constants.CONSUMER_API_URL');

        ////////// get access token first 

        $access_token_url = $api_url.'ClientLogin';  

          $API_RESP_TOKEN = $client->request('POST', $access_token_url,  [
            'verify' => false,
            'form_params' => [
                'client_id' => "odbusSasAdminApi",
                'password' => "Admin@2010"
            ]
        ]);

        $access_token_res = json_decode($API_RESP_TOKEN->getBody());

        $access_token = $access_token_res->data;

        if($request->date)
        {
               $dt=date("d-m-Y", strtotime($request->date));  
        }
        $url = $api_url.'Listing?source='.$request->source_id.'&destination='.$request->destination_id.'&entry_date='.$dt ;          
             
        $API_RESP = $client->request('GET', $url,  [
            'headers'=> ['Authorization' =>   "Bearer " . $access_token],
            'verify' => false,
           
        ]);
        // Log::info($url);
        // Log::info($API_RESP->getBody());
            
       return $response = json_decode($API_RESP->getBody());
            

    }
    
}