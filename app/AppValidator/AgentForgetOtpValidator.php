<?php
namespace App\AppValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgentForgetOtpValidator 
{   

    public function validate($data) { 
        
        $rules = [
         'email' => 'required'
        ];      
      
        $res = Validator::make($data, $rules);
        return $res;
    }

}