<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgentWalletValidator 
{   
    public function validate($data) { 
        
        $rules = [
            'transaction_id' => 'required|unique:agent_wallet,transaction_id',
            'payment_via' => 'required',
            'amount' => 'required',
            'user_id' => 'required',           
        ];            
        $agentWalletValidation = Validator::make($data, $rules);
        return $agentWalletValidation;
    }
}
