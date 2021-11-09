<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgentCommissionValidator 
{   

    public function validate($data) { 
        
        $rules = [
          'range_from' => 'required',
          'range_to' => 'required',
          'comission_per_seat' => 'required'
        ];      
      
        $agentCommissionValidation = Validator::make($data, $rules);
        return $agentCommissionValidation;
    }

}