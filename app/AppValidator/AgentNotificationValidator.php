<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgentNotificationValidator 
{   
    public function validate($data) { 
        
        $rules = [
            'subject' => 'required|unique:agent_wallet,transaction_id',
            'notification' => 'required',
            'user_id' => 'required',           
        ];            
        $agentNotificationValidator = Validator::make($data, $rules);
        return $agentNotificationValidator;
    }
}
