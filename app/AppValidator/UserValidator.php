<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'name' => 'required',
            'phone' => 'required',
            'user_type' => 'required',
        ];      
      
        $userValidator = Validator::make($data, $rules);
        return $userValidator;
    }

}