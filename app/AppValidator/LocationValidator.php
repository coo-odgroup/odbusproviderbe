<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'name' => 'required|max:50',
            'synonym' => '',
            'created_by' => 'required',
        ];      
      
        $LocationValidation = Validator::make($data, $rules);
        return $LocationValidation;
    }

}