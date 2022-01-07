<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\busSafety;
use App\Models\BusOperator;
use App\Models\User;

class OdbusCharges extends Model
{
    use HasFactory;
    protected $table = 'odbus_charges';
    protected $fillable = [
        'bus_operator_id','payment_gateway_charges','email_sms_charges','odbus_gst_charges','advance_days_show','support_email','booking_email','request_email','other_email','mobile_no_1','mobile_no_2','mobile_no_3','mobile_no_4','logo','favIcon'
    ];
    public function busOperator()
    {
    	return $this->belongsTo(BusOperator::class);
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
