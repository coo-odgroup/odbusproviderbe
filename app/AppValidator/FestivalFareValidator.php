<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FestivalFareValidator 
{   

    public function validate($data) { 
        
        $rules = [
            // 'operator_id' => 'required',
            // 'bus_id' => 'required',
            'date' => 'required',
            'seater_price' => 'required',
            'sleeper_price' => 'required',
            'created_by' => 'required',
            'reason' => 'required'
        ];      
      
        $festivalFareValidation = Validator::make($data, $rules);
        return $festivalFareValidation;
    }

}