<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssociationValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'name' => 'required',
            'email' => 'required',
            'short_nm' => 'required',
            'support_email' => 'required',
            'support_contact' => 'required',
            'phone' => 'required',
            'password' => 'required',
            'location' => 'required'
        ];      
      
        $associationValidator = Validator::make($data, $rules);
        return $associationValidator;
    }

}