<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class OwnerPaymentValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'bus_operator_id' => 'required',
            'date' => 'required',
            'amount' => 'required',
            'remark' => 'required',
            'created_by' => 'required'
            
        ];      
      
        $ownerpaymentValidation = Validator::make($data, $rules);
        return $ownerpaymentValidation;
    }

}