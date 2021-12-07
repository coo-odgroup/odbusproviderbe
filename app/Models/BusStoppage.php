<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bus;
use App\Models\User;
use App\Models\BusStoppageTiming;
use App\Models\BusOperator;
use App\Models\BusSeats;
use App\Models\BookingSeized;


class BusStoppage extends Model
{
    use HasFactory;
    protected $table = 'ticket_price';
    protected $fillable = ['bus_id', 'bus_operator_id','user_id','source_id','destination_id','base_seat_fare',
    'base_sleeper_fare', 'dep_time','arr_time','j_day','created_by'];
    public function bus()
    {
    	return $this->belongsTo(Bus::class);
    }
    public function user()
    {
    	return $this->belongsTo(User::class);
    }
    public function stoppage()
    {
        return $this->hasMany(BusStoppageTiming::class);
    }
    public function getBusSeats()
    {
        return $this->hasMany(BusSeats::class);
    }

     public function bookingSeized()
    {
        return $this->belongsTo(BookingSeized::class);
    }
}
