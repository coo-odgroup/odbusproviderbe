<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CancellationSlabValidator 
{   
    public function validate($data) { 
        $rules = [
            'rule_name' => 'required',
            'user_id' => 'required'
            // 'deduction' => 'required',
            //'status' => 'required',
        ];      
        $CancellationSlabValidation = Validator::make($data, $rules);
        return $CancellationSlabValidation;
    }

}