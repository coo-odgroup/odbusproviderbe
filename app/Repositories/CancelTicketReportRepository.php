<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\Booking;
use App\Models\Location;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class CancelTicketReportRepository
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
        $data= $this->booking->with('BookingDetail.BusSeats.seats',
                                    'BookingDetail.BusSeats.ticketPrice',
                                    'Bus','Users','CustomerPayment')
                             ->with('bus.busstoppage')
                             ->where('status', '2')
                             // ->whereHas('CustomerPayment', function ($query) {$query->where('payment_done', '1' );})
                             ->get() ; 

        $data_arr = array();
        foreach($data as $key=>$v)
        {
            $data_arr[]=$v->toArray();
            $data_arr[$key]['from_location']=$this->location->where('id', $v->source_id)->get();
            $data_arr[$key]['to_location']=$this->location->where('id', $v->destination_id)->get();

             $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get();
            
           
            foreach ($stoppage[0]['ticketPrice'] as $k => $a) 
            {                          
                $data_arr[$key]['source'][$k]=$this->location->where('id', $a['source_id'])->get();
                $data_arr[$key]['destination'][$k]=$this->location->where('id', $a['destination_id'])->get(); 
            }
        } 
       log::info($data_arr);
        return $data_arr;     
    }

}