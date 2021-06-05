<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Seats;

class BusSeatLayout extends Model
{
    use HasFactory;
    protected $table = 'bus_seat_layout';
    // public $timestamps = false;
    protected $fillable = [
        'name'
    ];
    public function seats()
    {
        return $this->hasMany(Seats::class);
        
    } 
}
