<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompleteReportValidator 
{   

    public function validate($data) { 
        
        $rules = [

            'date_type' => 'required',
            'rows_number' => 'required',
        ];      
      
        $completeReportValidation = Validator::make($data, $rules);
        return $completeReportValidation;
    }

}
