<?php

namespace App\Repositories;

// use App\Models\Bus;
use App\Models\SeatOpen;
use App\Models\SeatOpenSeats;
use App\Models\ExtraSeatOpen;
use App\Models\Bus;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class ExtraSeatOpenReportRepository
{
    
    protected $seatOpen;
    protected $extraseatOpen;
    protected $bus;

    
    public function __construct(SeatOpen $seatOpen , SeatOpenSeats $seatsOpenSeats,ExtraSeatOpen $extraseatOpen,Bus $bus)
    {
        $this->seatOpen = $seatOpen;
        $this->bus = $bus;
        $this->seatOpenSeats = $seatsOpenSeats;
        $this->extraseatOpen = $extraseatOpen;
    }   


    public function getAll()
    {

       
        $bus = $this->bus->with('busSeats.seats')
                    ->whereHas('busSeats', function ($query) {$query->where('duration', '>','0 ');})
                    ->whereHas('busSeats', function ($query) {$query->where('status', '1');})->get();

        return $bus;
     

    }
}