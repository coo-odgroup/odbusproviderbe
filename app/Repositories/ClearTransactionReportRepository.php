<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\Booking;
use App\Models\Location;


// use App\Models\TicketPrice;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class ClearTransactionReportRepository
{
    
    protected $seatOpen;
    protected $extraseatOpen;

    
    public function __construct(Booking $booking ,Location $location ,Bus $bus)
    {
        $this->booking = $booking;       
        $this->location = $location;       
        $this->bus = $bus;   
    }   

    public function getAll()
    {
        $new_time = strtotime('-7 minutes');       
        
        $check_time = date('Y-m-d H:i:s', $new_time);
        
        $current_date =date('Y-m-d');

       return $data= $this->booking->with('bus.busstoppage')
                             ->where('status','0')
                             ->where('journey_dt',$current_date)
                             ->where('created_at','<',$check_time)
                             ->get() ; 

        // $data_arr = array();
        // foreach($data as $key=>$v)
        // {
        //     $data_arr[]=$v->toArray();
        //     $data_arr[$key]['from_location']=$this->location->where('id', $v->source_id)->get();
        //     $data_arr[$key]['to_location']=$this->location->where('id', $v->destination_id)->get();

        //      $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get();
            
           
        //     foreach ($stoppage[0]['ticketPrice'] as $k => $a) 
        //     {                          
        //         $data_arr[$key]['source'][$k]=$this->location->where('id', $a['source_id'])->get();
        //         $data_arr[$key]['destination'][$k]=$this->location->where('id', $a['destination_id'])->get(); 
        //     }
        // } 
        // return $data_arr;     
    }
}