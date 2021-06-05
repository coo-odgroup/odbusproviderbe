<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenities extends Model
{
    use HasFactory;
    protected $table = 'amenities';
    // public $timestamps = false;
    protected $fillable = [
        'name','icon', 'reason'
    ];
    protected $appends = ['Disabled'];
    public function busAmenities()
    {
        return $this->hasMany(BusAmenities::class);
        
    } 
    public function getDisabledAttribute()
    {
        return "false";
    }
}
