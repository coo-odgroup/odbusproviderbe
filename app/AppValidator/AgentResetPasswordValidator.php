<?php
namespace App\AppValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgentResetPasswordValidator 
{   

    public function validate($data) { 
        
        $rules = [
         'email' => 'required',
         'password' => 'required'
        ];      
      
        $res = Validator::make($data, $rules);
        return $res;
    }

}