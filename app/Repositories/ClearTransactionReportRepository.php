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
class ClearTransactionReportRepository
{
    
    protected $seatOpen;
    protected $extraseatOpen;

    
    public function __construct(SeatOpen $seatOpen , SeatOpenSeats $seatsOpenSeats,ExtraSeatOpen $extraseatOpen)
    {
        $this->seatOpen = $seatOpen;
        $this->seatOpenSeats = $seatsOpenSeats;
        $this->extraseatOpen = $extraseatOpen;
    }   

    // public function getAll()
    // {
    //     return $this->seatOpen ->with('seatOpenSeats.seats')->with('bus','bus.busOperator')->get();
    // }
    public function getAll()
    {
    	return "Working Fine";
        // return $this->seatOpen ->with('seatOpenSeats.seats')->with('bus','bus.busOperator')->get();

        // return "wait";
     // return $this->extraseatOpen->get();
   

    }
}