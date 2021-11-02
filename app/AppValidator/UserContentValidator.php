<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserContentValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'password' => 'required',
            'bus_operator_id' => 'required'
        ];      
      
        $usercontentValidator = Validator::make($data, $rules);
        return $usercontentValidator;
    }

}