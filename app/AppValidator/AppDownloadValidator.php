<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppDownloadValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'mobileno' => 'required|max:12',
        ];      
      
        $AppDownloadValidation = Validator::make($data, $rules);
        return $AppDownloadValidation;
    }

}