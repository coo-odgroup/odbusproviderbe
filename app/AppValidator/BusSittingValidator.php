<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusSittingValidator 
{   

    public function validate($data) { 
        
        $rules = [
          
          'name' => 'required|max:25',
        ];      
      
        $busSittingValidation = Validator::make($data, $rules);
        return $busSittingValidation;
    }

}