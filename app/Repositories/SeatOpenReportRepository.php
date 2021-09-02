<?php

namespace App\Repositories;

// use App\Models\Bus;
use App\Models\SeatOpen;
use App\Models\SeatOpenSeats;

// use App\Models\TicketPrice;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;
use App\Models\Location;



/*Priyadarshi to Review*/
class SeatOpenReportRepository
{
    
    protected $seatOpen;
    protected $location;


    
    public function __construct(SeatOpen $seatOpen , SeatOpenSeats $seatsOpenSeats , Location $location)
    {
        $this->seatOpen = $seatOpen;
        $this->seatOpenSeats = $seatsOpenSeats;
        $this->location = $location;        

       
    }    
    public function getAll()
    {
        $data = $this->seatOpen ->with('seatOpenSeats.seats')->with('bus','bus.busOperator','bus.ticketPrice.location')->get();

        $data_arr = array();
        foreach ($data as $key => $v)
        {
            $data_arr[]=$v->toArray(); 
            foreach ($data_arr as $e) 
            {
                $counter=0;
                foreach ($e['bus']['ticket_price'] as $sub=>$a) 
                {
                    $counter++;
                    if($counter==1)
                    {
                        $data_arr[$key]['base_from_location']=$this->location->where('id', $a['source_id'])->get();

                        $data_arr[$key]['base_to_location']=$this->location->where('id', $a['destination_id'])->get();
                    }
                    $data_arr[$key]['from_location'][$sub]=$this->location->where('id', $a['source_id'])->get();

                    $data_arr[$key]['to_location'][$sub]=$this->location->where('id', $a['destination_id'])->get();
                }                           
            }           
        }
        return $data_arr;

    }
    
    
   

}