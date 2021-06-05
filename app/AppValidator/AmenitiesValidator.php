<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AmenitiesValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'name' => 'required|max:50',
            'icon' => 'required',
            'created_by' => 'required',
        ];      
      
        $AmenitiesValidation = Validator::make($data, $rules);
        return $AmenitiesValidation;
    }

}