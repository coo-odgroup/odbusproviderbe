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

      // public function Booking()
      // {
      //       return $this->belongsTo(Booking::class);
      // }
      
}
