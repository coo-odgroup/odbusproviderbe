<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SafetyValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'name' => 'required|max:50'
        ];      
      
        $safetyValidation = Validator::make($data, $rules);
        return $safetyValidation;
    }

}