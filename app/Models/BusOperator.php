<?php

namespace App\Models;
use App\Models\Bus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OdbusCharges;

class BusOperator extends Model
{
    use HasFactory;
    protected $table = 'bus_operator';
    // public $timestamps = false;
    protected $fillable = [
        'email_id','password','operator_name','contact_number','organisation_name','location_name'
    ];
    public function bus()
    {        
        return $this->hasMany(Bus::class);        
    } 
    public function odbusCharges()
    {
        return $this->hasMany(OdbusCharges::class);
        
    } 
}
