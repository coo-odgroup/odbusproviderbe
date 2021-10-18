<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SeoSettingValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'page_url' => 'required',
        ];      
      
        $seosettingValidator = Validator::make($data, $rules);
        return $seosettingValidator;
    }

}