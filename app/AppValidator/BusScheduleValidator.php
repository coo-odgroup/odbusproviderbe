<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusScheduleValidator 
{   

    public function validate($data) { 
        
        $rules = [
            //'bus_operator_id' => 'required',
            'bus_id' => 'required',
            //'bus_stoppage_id' => 'required',
            //'journey_date' => 'required',
            //'customer_journey_date' => 'required',
            //'entry_date' => 'required',
            'created_by' => 'required',
        ];      
      
        $busScheduleValidation = Validator::make($data, $rules);
        return $busScheduleValidation;
    }

}