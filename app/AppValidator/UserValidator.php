<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'phone' => 'required|min:10|max:10|digits:10',
            
        ];      
      
        $userValidator = Validator::make($data, $rules);
        return $userValidator;
    }

}