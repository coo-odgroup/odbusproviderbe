<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TicketFareSlabValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'bus_operator_id' => 'required'            
        ];      
      
        $LocationValidation = Validator::make($data, $rules);
        return $LocationValidation;
    }

}