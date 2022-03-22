<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PermissionToRoleValidator
{   
    public function validate($data) 
    { 
        $rules = [
            'permission_id' => 'required',
            'role_id' => 'required|array|min:1'
        ];      
      
        $PTRValidator = Validator::make($data, $rules);
        return $PTRValidator;
    }

}