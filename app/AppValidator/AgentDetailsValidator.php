<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgentDetailsValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'userId' => 'required',
            'password' => 'required',
            'location' => 'required',
            'adhar_no' => 'required',
            'pancard_no' => 'required', 
            'email'  =>'required|unique:user,email' 
        ];      
      
        $agentDetailsValidator = Validator::make($data, $rules);
        return $agentDetailsValidator;
    }

}