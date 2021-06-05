<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BusSeatLayout;

class Seats extends Model
{
    use HasFactory;
    protected $table = 'seats';
    //public $timestamps = false;
    protected $fillable = ['bus_seat_layout_id','seatType', 'seatText','rowNumber','colNumber','berthType'];
    public function BusSeatLayout()
    {
    	return $this->belongsTo(BusSeatLayout::class);
    }
    protected $hidden = [
        'created_at',
        'updated_at',
        'created_by',
        'status',
    ];

}
