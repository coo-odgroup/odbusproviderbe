<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BoardingDroping;
use App\Models\BusStoppage;
use App\Models\Location;

class BusStoppageTiming extends Model
{
    use HasFactory;
    protected $table = 'bus_stoppage_timing';
    protected $fillable = ['bus_id','location_id','stoppage_name','stoppage_time','created_by','boarding_droping_id'];
    public function busStoppage()
    {
    	return $this->belongsTo(BusStoppage::class);
    }
    public function location()
    {
    	return $this->belongsTo(Location::class);
    }
    public function boardingDroping()
    {
    	return $this->belongsTo(BoardingDroping::class);
    }
}
