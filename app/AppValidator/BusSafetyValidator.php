<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusSafetyValidator 
{   

    public function validate($data) { 
        
        // $rules = [
        //     'name' => 'required|max:50'
        // ];      
      
        $busSafetyValidation = Validator::make($data, $rules);
        return $busSafetyValidation;
    }

}