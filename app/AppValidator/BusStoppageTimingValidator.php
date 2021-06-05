<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusStoppageTimingValidator 
{   
    public function validate($data) { 
        $rules = [
            'bus_id' => 'required',
            'boarding_droping_name' => 'required',
            'journey_time' => 'required',
            'created_by' => 'required'
        ];
        $BusStoppageTimingValidation = Validator::make($data, $rules);
        return $BusStoppageTimingValidation;
    }

}