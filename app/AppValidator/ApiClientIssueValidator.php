<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiClientIssueValidator 
{   

    public function validate($data) { 
        $rules = [
         'issueType_id' => 'required',
         'issueSubType_id' => 'required',
         'message' => 'required',
         'user_id' => 'required',
         'created_by' => 'required',
        ];      
      
        $agentValidation = Validator::make($data, $rules);
        return $agentValidation;
    }
}