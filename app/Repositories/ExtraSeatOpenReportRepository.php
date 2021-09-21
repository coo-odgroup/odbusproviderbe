<?php

namespace App\Repositories;

// use App\Models\Bus;
use App\Models\SeatOpen;
use App\Models\SeatOpenSeats;
use App\Models\ExtraSeatOpen;

// use App\Models\TicketPrice;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class ExtraSeatOpenReportRepository
{
    
    protected $seatOpen;
    protected $extraseatOpen;

    
    public function __construct(SeatOpen $seatOpen , SeatOpenSeats $seatsOpenSeats,ExtraSeatOpen $extraseatOpen)
    {
        $this->seatOpen = $seatOpen;
        $this->seatOpenSeats = $seatsOpenSeats;
        $this->extraseatOpen = $extraseatOpen;
    }   


    public function getAll()
    {

        return "WORK IN PROGRESS";
        
     // return $this->extraseatOpen->get();
   

    }
}