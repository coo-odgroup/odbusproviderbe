<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiUserValidator 
{   
    public function validate($data) { 
        
        $rules = [
         'email' => 'required|email:rfc,dns',
         'phone' => 'required',
        ];      
      
        $apiUserValidation = Validator::make($data, $rules);
        return $apiUserValidation;
    }

}