<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BoardingDroping;
use App\Models\BusStoppageTiming;
class State extends Model
{
    use HasFactory;
    protected $table = 'state';
    protected $fillable = ['state_name','state_url','created_by','status'];
   
    public function location()
    {
        return $this->hasMany(Location::class);
        
    } 
}
