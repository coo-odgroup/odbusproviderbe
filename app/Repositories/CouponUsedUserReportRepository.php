<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\Booking;
use App\Models\Location;


// use App\Models\TicketPrice;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class CouponUsedUserReportRepository
{
    
    // protected $seatOpen;
    // protected $extraseatOpen;

    
    public function __construct(Booking $booking ,Location $location ,Bus $bus)
    {
        $this->booking = $booking;       
        $this->location = $location;       
        $this->bus = $bus;   
    }   

    public function getAll()
    {
      return "Working Fine";
       
    }
}