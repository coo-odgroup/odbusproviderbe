<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusStoppageValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'bus_id' => 'required|exists:bus,id',
            'user_id' => 'required',
            'source_id' => 'required',
            'destination_id' => 'required',
            'base_seat_fare' => 'required',
            'base_sleeper_fare' => 'required',
            'dep_time' => 'required',
            'arr_time' => 'required',
            'j_day' => 'required',
            'created_by' => 'required',
        ];      
      
        $BusStoppageValidation = Validator::make($data, $rules);
        return $BusStoppageValidation;
    }

}