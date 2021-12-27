<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusOwnerFareValidator 
{   

    public function validate($data) { 
        
        $rules = [
            //'operator_id' => 'required',
            // 'bus_id' => 'required',
            'date' => 'required',
            'created_by' => 'required',
            'reason' => 'required'
        ];      
      
        $busOwnerFareValidation = Validator::make($data, $rules);
        return $busOwnerFareValidation;
    }

}