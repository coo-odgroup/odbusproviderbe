<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExtendedBusClosingHourValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'bus_id' => 'required',
            'city_id' => 'required',
            'dep_time' => 'required',
            'closing_hours' => 'required',
            

        ];      
        $extendBusClosingHourValidation = Validator::make($data, $rules);
        return $extendBusClosingHourValidation;
    }

}