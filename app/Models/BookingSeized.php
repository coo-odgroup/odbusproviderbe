<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Location;
use App\Models\Bus;


class BookingSeized extends Model
{
    use HasFactory;
    protected $table = 'booking_seized';
    protected $fillable = ['location_id ','seize_booking_minute'];


    public function location()
    {
    	 return $this->belongsTo(Location::class);
    }

    public function bus()
    {
    	return $this->belongsTo(Bus::class);
    }
    
}