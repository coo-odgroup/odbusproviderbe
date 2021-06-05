<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusTypeValidator 
{   

    public function validate($data) { 
        
        $rules = [
          'type' => 'required',
          'name' => 'required|max:25',
        ];      
      
        $busTypeValidation = Validator::make($data, $rules);
        return $busTypeValidation;
    }

}