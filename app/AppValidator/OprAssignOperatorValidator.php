<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class OprAssignOperatorValidator
{   
    public function validate($data) { 
        // Log::info($data);
        $rules = [
            'user_id' => 'required',
            'operator_id' => 'required|array|min:1'
        ];      
      
        $OprValidator = Validator::make($data, $rules);
        return $OprValidator;
    }

}