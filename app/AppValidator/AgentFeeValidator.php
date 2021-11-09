<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgentFeeValidator 
{   

    public function validate($data) { 
        
        $rules = [
          'range_from' => 'price_from',
          'range_to' => 'price_to',
          'comission_per_seat' => 'max_comission'
        ];      
      
        $agentFeeValidation = Validator::make($data, $rules);
        return $agentFeeValidation;
    }

}