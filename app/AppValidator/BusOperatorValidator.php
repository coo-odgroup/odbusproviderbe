<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusOperatorValidator 
{   

    public function validate($data) { 
        
        $rules = [
            //'EmailId' => 'required|email|max:50|unique:bus_operator,email_id',
            'email_id' => 'required|email|max:50',
            'password' => 'required|min:3',
            'operator_name' => 'required|min:2|max:50',
            'contact_number' => 'required|min:10|max:50',
            'organisation_name' => 'required|min:2|max:100',
            'location_name' => 'required|min:2|max:50',
            'created_by' => 'required',
        ];      
      
        $BusOperatorValidation = Validator::make($data, $rules);
        return $BusOperatorValidation;
    }

}