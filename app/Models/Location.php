<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BoardingDroping;
use App\Models\BusStoppageTiming;
class Location extends Model
{
    use HasFactory;
    protected $table = 'location';
    //public $timestamps = false;
    protected $fillable = ['name','synonym','created_by','status'];
    public function locationcode()
    {
        return $this->hasMany(Locationcode::class);
        
    } 
    public function boardingDropping()
    {
        return $this->hasMany(BoardingDroping::class)->where('status','!=',2);
        
    } 
    public function busStoppageTiming()
    {
        return $this->hasMany(BusStoppageTiming::class);
        
    } 
    // protected $hidden = [
    //     'created_at',
    //     'updated_at',
        
    // ];
}
