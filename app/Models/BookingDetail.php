<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bus;
use App\Models\Booking;
use App\Models\BusSeats;

class BookingDetail extends Model
{
    use HasFactory;
    protected $table = 'booking_detail';
    protected $fillable = ['booking_id','pnr','jrny_dt','j_day','bus_id','seat_no',
                            'passenger_name','passenger_gender','passenger_age',
                            'created_by'];

    public function Bus()
    {
        return $this->belongsTo(Bus::class);
    }
    public function BusSeats()
    {
        return $this->belongsTo(BusSeats::class);
    }
    
    public function busSeat()
    {
        return $this->belongsTo(BusSeats::class, 'bus_seats_id', 'id');
    }

    public function seat()
    {
        return $this->hasOneThrough(
            Seats::class,
            BusSeats::class,
            'bus_seats.id',
            'seats.id',
            'bus_seats_id',
            'seats_id'
        )->select([
            'seats.id',
            'seats.berthType',
            'seats.seatText',
        ]);
    }



    // public function Booking()
    // {
    //       return $this->belongsTo(Booking::class);
    // }

      public function booking()
      {
            return $this->belongsTo(Booking::class, 'booking_id');
      }
}
