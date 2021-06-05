<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\BusScheduleDate;

class BusSchedule extends Model
{
    use HasFactory;
    protected $table = 'bus_schedule';
    protected $fillable = ['bus_id','created_by','status'];
    public function bus()
    {
    	return $this->belongsTo(Bus::class);
    }
    public function busStoppage()
    {
    	return $this->belongsTo(BusStoppage::class);
    }
    public function busScheduleDate()
    {        
        return $this->hasMany(BusScheduleDate::class);        
    } 
}