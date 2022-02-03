<?php
namespace App\AppValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgentVerifyOtpValidator 
{   

    public function validate($data) { 
        
        $rules = [
         'email' => 'required',
         'otp' => 'required'
        ];      
      
        $res = Validator::make($data, $rules);
        return $res;
    }

}