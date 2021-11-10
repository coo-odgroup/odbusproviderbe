<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgentNotificationValidator 
{   
    public function validate($data) { 
        
        $rules = [
            'subject' => 'required',
            'notification' => 'required'           
        ];            
        $agentNotificationValidator = Validator::make($data, $rules);
        return $agentNotificationValidator;
    }
}
