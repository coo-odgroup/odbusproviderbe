<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Seats;




class SeatOpenSeats extends Model
{
    use HasFactory;
    protected $table = 'seat_open_seats';
    protected $fillable = ['seat_open_id','seat_id'];


	public function seats()
    {
    	return $this->belongsTo(Seats::class);
    }

   

}

