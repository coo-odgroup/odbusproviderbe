<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;


class UserBooking extends Model
{
    use HasFactory;
    protected $table = 'user_booking';
    protected $fillable = ['user_id ','booking_id '];

     public function booking()
      {
            return $this->belongsTo(Booking::class);
      }
}
