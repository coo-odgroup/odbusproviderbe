<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\Booking;
use App\Models\Location;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class CompleteReportRepository
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
    public function getAll()
    {
        $data= $this->booking->with('BookingDetail','Bus','Users')
                             ->with('bus.ticketPrice','bus.busstoppage')
                             ->get() ; 

        $data_arr = array();
        foreach($data as $key=>$v)
        {
            $data_arr[]=$v->toArray();
            $data_arr[$key]['from_location']=$this->location->where('id', $v->source_id)->get();
            $data_arr[$key]['to_location']=$this->location->where('id', $v->destination_id)->get();
            $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get();
  
                foreach ($stoppage as $k => $a) 
                {           
              
                     $data_arr[$key]['source'][$k]=$this->location->where('id', $a->ticketPrice[$k]['source_id'])->get();

                     $data_arr[$key]['destination'][$k]=$this->location->where('id', $a->ticketPrice[$k]['destination_id'])->get(); 

                }
        } 
        
        return $data_arr;     
    }

}