<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgentValidator 
{   

    public function validate($data) { 
        
        $rules = [
         'email' => 'required|unique:user,email',
         'phone' => 'required|unique:user,phone',
        ];      
      
        $agentFeeValidation = Validator::make($data, $rules);
        return $agentFeeValidation;
    }

}