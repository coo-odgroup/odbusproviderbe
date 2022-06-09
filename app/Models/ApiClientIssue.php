<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\BusSeats;
// use App\Models\BusAmenities;
// use App\Models\BusSafety;

// use App\Models\BusSeatLayout;
// use App\Models\bus_seats;

class ApiClientIssue extends Model
{
    use HasFactory; 
    protected $table = 'apiclientissue';
    protected $fillable = [   ];
    // public function busAmenities()
    // {
    //     return $this->hasMany(BusAmenities::class);        
    // } 

   

}
