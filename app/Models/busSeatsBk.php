<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class busSeatsBk extends Model
{
    use HasFactory;

    protected $table = 'bus_seats_bk';
    protected $fillable = ['data'];
}
