<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusSpecialFareValidator 
{   

    public function validate($data) { 
        
        $rules = [
            //'operator_id' => 'required',
            //'bus_id' => 'required',
            'date' => 'required',
            'seater_price' => 'required',
            'sleeper_price' => 'required',
            'created_by' => 'required',
            'reason' => 'required'
        ];         
      
        $busSpecialFareValidation = Validator::make($data, $rules);
        return $busSpecialFareValidation;
    }

}