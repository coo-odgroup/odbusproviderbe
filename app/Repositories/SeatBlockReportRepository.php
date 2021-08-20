<?php

namespace App\Repositories;

// use App\Models\Bus;
use App\Models\SeatBlock;
use App\Models\SeatBlockSeats;

// use App\Models\TicketPrice;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class SeatBlockReportRepository
{
    
    protected $seatBlock;

    
    public function __construct(SeatBlock $seatBlock , SeatBlockSeats $seatsBlockSeats)
    {
        $this->seatBlock = $seatBlock;
        $this->seatBlockSeats = $seatsBlockSeats;
       
    }    
    public function getAll()
    {
        return $this->seatBlock ->with('seatBlockSeats.seats')->with('bus','bus.busOperator')->get();
    }

}