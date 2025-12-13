<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteWiseBooking extends Model
{
    use HasFactory;

    protected $table = "route_wise_booking";
    protected $guarded;
}
