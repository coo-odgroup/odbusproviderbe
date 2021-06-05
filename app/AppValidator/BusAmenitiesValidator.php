<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusAmenitiesValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'bus_id' => 'required|max:50',
            'amenities_id' => 'required',
            'created_by' => 'required|max:50'
        ];      
      
        $busAmenitiesValidation = Validator::make($data, $rules);
        return $busAmenitiesValidation;
    }

}