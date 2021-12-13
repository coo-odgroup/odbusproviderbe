<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BoardingDropingValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'location_id' => 'required',
            'boarding_point' => 'required|max:400',
            //'dropping_point' => 'required|max:200',
            'created_by' => 'required',
        ];      
      
        $boardingdropingValidation = Validator::make($data, $rules);
        return $boardingdropingValidation;
    }

}