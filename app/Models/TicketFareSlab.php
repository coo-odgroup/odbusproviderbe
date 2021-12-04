<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketFareSlab extends Model
{
    use HasFactory;
    protected $table = 'ticket_fare_slab';
    //public $timestamps = false;
    protected $fillable = ['bus_operator_id ','starting_fare','upto_fare','odbus_commision'];
 
    
}
