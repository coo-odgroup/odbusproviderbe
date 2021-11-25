<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OdbusChargesValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'bus_operator_id' => 'required|unique:odbus_charges,bus_operator_id',
            'payment_gateway_charges' => 'required',
            'email_sms_charges' => 'required',
            'odbus_gst_charges' => 'required',
            'advance_days_show' => 'required',
            'support_email' => 'required',
            'booking_email' => 'required',
            'request_email' => 'required',
            'mobile_no_1' => 'required',
            'mobile_no_2' => 'required',
            'mobile_no_3' => 'required'          
        ];      
      
        $odbusChargesValidation = Validator::make($data, $rules);
        return $odbusChargesValidation;
    }

}