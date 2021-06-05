<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusSeatLayoutValidator 
{   

    public function validate($data) { 
        
        $rules = [
          
            'name' => 'required|max:50',
            'layout_data' => 'required',
        ];      
      
        $busSeatLayoutValidation = Validator::make($data, $rules);
        return $busSeatLayoutValidation;
    }

}