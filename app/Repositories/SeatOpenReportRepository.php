<?php

namespace App\Repositories;

// use App\Models\Bus;
use App\Models\SeatOpen;
use App\Models\SeatOpenSeats;

// use App\Models\TicketPrice;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class SeatOpenReportRepository
{
    
    protected $seatOpen;

    
    public function __construct(SeatOpen $seatOpen , SeatOpenSeats $seatsOpenSeats)
    {
        $this->seatOpen = $seatOpen;
        $this->seatOpenSeats = $seatsOpenSeats;
       
    }    
    public function getAll()
    {
        return $this->seatOpen ->with('seatOpenSeats.seats')->with('bus','bus.busOperator')->get();

    }
    
    
   

}