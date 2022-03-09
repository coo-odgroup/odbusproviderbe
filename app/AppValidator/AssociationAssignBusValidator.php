<?php
namespace App\AppValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class AssociationAssignBusValidator
{   

    public function validate($data) { 
        
        $rules = [
            'user_id' => 'required',
            'operator_id' => 'required|array|min:1'
        ];      
      
        $associationValidator = Validator::make($data, $rules);
        return $associationValidator;
    }

}