<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusSequenceValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'sequence' => 'required',
        ];      
      
        $bussequenceValidation = Validator::make($data, $rules);
        return $bussequenceValidation;
    }

}