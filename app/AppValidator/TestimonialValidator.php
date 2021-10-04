<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestimonialValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'posted_by'=>'required',
            'testinmonial_content '=>'required',
            'location'=>'required',
            'designation '=>'required',
        ];      
      
        $testimonialValidator = Validator::make($data, $rules);
        return $testimonialValidator;
    }

}