<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bus;


class BusClass extends Model
{
    use HasFactory; 
    protected $table = 'bus_class';
    protected $fillable = [];
    
    
}
