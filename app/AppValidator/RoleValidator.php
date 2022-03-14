<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleValidator 
{   

    public function validate($data) { 
        
        $rules = [          
          'name' => 'required|max:25',         
        ];      
      
        $roleValidation = Validator::make($data, $rules);
        return $roleValidation;
    }

}