<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginValidator 
{   

    public function validate($data) { 
        
        $rules = [
            // 'email' => 'required',
            // 'user_type' => 'required',
            // 'password' => 'required'
        ];      
      
        $loginValidator = Validator::make($data, $rules);
        return $loginValidator;
    }

}