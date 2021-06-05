<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusContactsValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'bus_id' => 'required|exists:bus,id',
            'type' => 'required',
            'phone' => 'required|max:10|min:10',
            'booking_sms_send' => 'required',
            'cancel_sms_send' => 'required',
            'created_by' => 'required',
        ];      
      
        $BusContactsValidation = Validator::make($data, $rules);
        return $BusContactsValidation;
    }

}