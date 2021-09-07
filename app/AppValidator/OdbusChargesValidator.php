<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OdbusChargesValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'payment_gateway_charges' => 'required',
            'email_sms_charges' => 'required',
            'odbus_gst_charges' => 'required'
        ];      
      
        $odbusChargesValidation = Validator::make($data, $rules);
        return $odbusChargesValidation;
    }

}