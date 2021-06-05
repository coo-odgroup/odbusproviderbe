<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BusStoppage;
use App\Models\BusSeats;

class BusStoppageAdditionalFare extends Model
{
    use HasFactory;
    protected $table = 'bus_stoppage_additional_fare';
    protected $fillable = ['ticket_price_id','bus_seats_id','additional_fare','created_by'];

    public function BusStoppage()
    {
    	return $this->belongsTo(BusStoppage::class);
    }
    public function BusSeats()
    {
    	return $this->belongsTo(BusSeats::class);
    }
    
}
