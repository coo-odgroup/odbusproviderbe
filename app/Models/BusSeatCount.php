<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusSeatCount extends Model
{
    use HasFactory;

    protected $table = 'bus_seat_count';

    protected $fillable = [
        'bus_id',
        'total_seat',
        'date',
        'updated_by',
    ];


    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id');
    }
}