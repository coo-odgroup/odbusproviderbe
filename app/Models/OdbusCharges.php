<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\busSafety;

class OdbusCharges extends Model
{
    use HasFactory;
    protected $table = 'odbus_charges';
    // public $timestamps = false;
    protected $fillable = [
        'payment_gateway_charges','email_sms_charges','odbus_gst_charges'
    ];
    
    

}
