<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FaqValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'title' => 'required',
            'content' => 'required',
           
        ];      
      
        $faqValidator = Validator::make($data, $rules);
        return $faqValidator;
    }

}