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
            'adhar_no' => 'required|max:12|min:12',
            'pancard_no' => [
                'required',
                'regex:/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/',
                'unique:user,pancard_no'
            ], 
            'email'  =>'required|unique:user,email' 
        ];      
      
        $agentDetailsValidator = Validator::make($data, $rules);
        return $agentDetailsValidator;
    }

}