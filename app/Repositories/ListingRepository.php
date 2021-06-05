<?php

namespace App\Repositories;

use App\Models\Bus;
// use App\Models\Amenities;
// use App\Models\BusType;
// use App\Models\BusSitting;
// use App\Models\BusSeatLayout;

class ListingRepository
{
    
    protected $bus;
    // protected $amenities;
    // protected $busType;
    // protected $busSitting;
    // protected $busSeatLayout;

    
    public function __construct(Bus $bus)
    {
        $this->bus = $bus;
        //$this->amenities = $amenities;
    }    
    public function getAll()
    {            
        $data_arr[]= $this->bus->with('busOperator')->with('busAmenities')->with('BusType')->with('BusSitting')->get();
        return $data_arr;
    }

    
    

}