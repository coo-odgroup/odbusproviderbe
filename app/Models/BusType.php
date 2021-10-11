<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bus;
use App\Models\BusClass;


class BusType extends Model
{
    use HasFactory;
    protected $table = 'bus_type';
    // public $timestamps = false;
    protected $fillable = [
        'name'
    ];

    public function Bus()
    {
    	return $this->hasOne(Bus::class);
    }
     public function BusClass()
    {
        return $this->belongsTo(BusClass::class);
    }
}
