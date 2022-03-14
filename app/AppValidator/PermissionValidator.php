<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionValidator 
{   

    public function validate($data) { 
        
        $rules = [          
          'name' => 'required|max:25',         
        ];      
      
        $permissionValidation = Validator::make($data, $rules);
        return $permissionValidation;
    }

}