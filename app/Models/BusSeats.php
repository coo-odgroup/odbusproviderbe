<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bus;
use App\Models\BusStoppage;
use App\Models\Seats;
use App\Models\TicketPrice;


class BusSeats extends Model
{
    use HasFactory;
    protected $table = 'bus_seats';
    protected $fillable = ['bus_id', 'category','seat_type','berth_type','source_id','destination_id','seat_number','duration','created_by'];
    public function bus()
    {
    	return $this->belongsTo(Bus::class);
    }
    public function busStoppage()
    {
    	return $this->belongsTo(BusStoppage::class);
    }
    public function seats()
    {
        return $this->belongsTo(Seats::class);
    }
    public function ticketPrice()
    {
        return $this->belongsTo(TicketPrice::class);        
    }
}
