<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiUserCommissionValidator 
{ 
    public function validate($data) { 
        
        $rules = [
          'user_id'       => 'required',  
          'starting_fare' => 'required',
          'upto_fare'     => 'required',
          'commision'     => 'required'
        ];      
      
        $apiUserCommissionValidation = Validator::make($data, $rules);
        return $apiUserCommissionValidation;
    }

}