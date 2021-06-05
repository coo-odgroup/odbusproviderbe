<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CancellationSlabValidator 
{   
    public function validate($data) { 
        $rules = [
            'api_id' => 'integer|required',
            'rule_name' => 'required',
            'duration' => 'required',
            'deduction' => 'required',
            //'status' => 'required',
        ];      
        $CancellationSlabValidation = Validator::make($data, $rules);
        return $CancellationSlabValidation;
    }

}