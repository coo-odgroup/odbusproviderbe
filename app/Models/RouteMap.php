<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteMap extends Model
{
    use HasFactory;
    
    protected $table = "mst_route_map";
    protected $guarded = [];
}
