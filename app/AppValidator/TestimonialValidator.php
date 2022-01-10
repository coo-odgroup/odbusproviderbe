<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestimonialValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'posted_by'=>'required',
            'testinmonial_content'=>'required',
            'travel_date'=>'required',
            'user_id'=>'required',
            'destination'=>'required',
            'source'=>'required',
            'designation'=>'required'
            // 'designation '=>'required',
        ];      
      
        $testimonialValidator = Validator::make($data, $rules);
        return $testimonialValidator;
    }

}