<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgentValidator 
{   

    public function validate($data) { 
        
        $rules = [
         'email' => 'required',
         'phone' => 'required',
        ];      
      
        $agentFeeValidation = Validator::make($data, $rules);
        return $agentFeeValidation;
    }

}