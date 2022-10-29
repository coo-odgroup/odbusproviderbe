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

        // $data= $this->booking->where('user_id',$request->user_id)->where('status',1)->get();
        // log::info($data);
        $paginate = $request->rows_number;
        $date_type = $request->date_type;
        $start_date  =  $request->rangeFromDate;
        $end_date  =  $request->rangeToDate;
        $user_id  =  $request->user_id;

        $data= $this->booking->with('BookingDetail.BusSeats.seats',
                                    'BookingDetail.BusSeats.ticketPrice','Users','User')
                             ->with(["Bus" => function($bs){
                                        $bs->with('cancellationslabs.cancellationSlabInfo');
                                        $bs->with('busstoppage');                
                                        $bs->with('busContacts');
                                      } ] )  
                             ->where('user_id', $user_id )
                             ->where('status', 1 )
                             ->orderBy('id','DESC');

         if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) {
            $paginate = 10 ;
        }

        if($date_type == 'booking' && $start_date == null && $end_date == null)
        {
            $data =$data->orderBy('created_at','DESC');
        }
        else if($date_type == 'booking' && $start_date != null && $end_date != null)
        {         
            if($start_date == $end_date){
                $data =$data->where('created_at','like','%'.$start_date.'%')
                        ->orderBy('created_at','DESC');
                       
            }else{
                $data =$data->whereBetween('created_at', [$start_date, $end_date])
                        ->orderBy('created_at','DESC');
            }
            
        }
        else if($date_type == 'journey' && $start_date == null && $end_date == null)
        {
            $data =$data->orderBy('journey_dt','DESC');
        }
        else if($date_type == 'journey' && $start_date != null && $end_date != null)
        {
             if($start_date == $end_date){
                $data =$data->where('journey_dt', 'like','%'.$start_date.'%')
                        ->orderBy('journey_dt','DESC');
            }else{
                 $data =$data-> whereBetween('journey_dt', [$start_date, $end_date])
                        ->orderBy('journey_dt','DESC');
            }
        }
        
        $data=$data->paginate($paginate); 

        if($data){
            foreach($data as $key=>$v){

               $v['from_location']=$this->location->where('id', $v->source_id)->get();
               $v['to_location']=$this->location->where('id', $v->destination_id)->get();

               $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get();
               // $v['source']=[];
               // $v['destination']=[];

               $stoppages['source']=[];
               $stoppages['destination']=[];
                if(count($stoppage)>0){
                   foreach ($stoppage[0]['ticketPrice'] as $k => $a) 
                    {                          
                        $stoppages['source'][$k]=$this->location->where('id', $a->source_id)->get();
                        $stoppages['destination'][$k]=$this->location->where('id', $a->destination_id)->get(); 
                    }
                }
                $v['source']= $stoppages['source'];
                $v['destination']= $stoppages['destination'];
            }
        }
           $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   

      
           return $response; 

    }

    public function getAllCancelData($request)
    {
        $paginate = $request->rows_number;
        $date_type = $request->date_type;
        $start_date  =  $request->rangeFromDate;
        $end_date  =  $request->rangeToDate;
        $user_id  =  $request->user_id;

        $data= $this->booking->with('BookingDetail.BusSeats.seats',
                                    'BookingDetail.BusSeats.ticketPrice','Users','User')
                             ->with(["Bus" => function($bs){
                                        $bs->with('cancellationslabs.cancellationSlabInfo');
                                        $bs->with('busstoppage');                
                                        $bs->with('busContacts');
                                      } ] )  
                             ->where('user_id', $user_id )
                             ->where('status', 2 )
                             ->orderBy('id','DESC');

         if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) {
            $paginate = 10 ;
        }

        if($date_type == 'booking' && $start_date == null && $end_date == null)
        {
            $data =$data->orderBy('created_at','DESC');
        }
        else if($date_type == 'booking' && $start_date != null && $end_date != null)
        {         
            if($start_date == $end_date){
                $data =$data->where('created_at','like','%'.$start_date.'%')
                        ->orderBy('created_at','DESC');
                       
            }else{
                $data =$data->whereBetween('created_at', [$start_date, $end_date])
                        ->orderBy('created_at','DESC');
            }
            
        }
        else if($date_type == 'journey' && $start_date == null && $end_date == null)
        {
            $data =$data->orderBy('journey_dt','DESC');
        }
        else if($date_type == 'journey' && $start_date != null && $end_date != null)
        {
             if($start_date == $end_date){
                $data =$data->where('journey_dt', 'like','%'.$start_date.'%')
                        ->orderBy('journey_dt','DESC');
            }else{
                 $data =$data-> whereBetween('journey_dt', [$start_date, $end_date])
                        ->orderBy('journey_dt','DESC');
            }
        }
        
        $data=$data->paginate($paginate); 

        if($data){
            foreach($data as $key=>$v){

               $v['from_location']=$this->location->where('id', $v->source_id)->get();
               $v['to_location']=$this->location->where('id', $v->destination_id)->get();

               $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get();
               // $v['source']=[];
               // $v['destination']=[];

               $stoppages['source']=[];
               $stoppages['destination']=[];
                if(count($stoppage)>0){
                   foreach ($stoppage[0]['ticketPrice'] as $k => $a) 
                    {                          
                        $stoppages['source'][$k]=$this->location->where('id', $a->source_id)->get();
                        $stoppages['destination'][$k]=$this->location->where('id', $a->destination_id)->get(); 
                    }
                }
                $v['source']= $stoppages['source'];
                $v['destination']= $stoppages['destination'];
            }
        }
           $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   

      
           return $response;       
    }


    public function datewiseroute($request)
    {
         
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