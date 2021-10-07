<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SliderValidator 
{   
    public function validate($data) { 

        $rules = [
            'occassion' => 'required',
            'slider_img' => 'required',
            'alt_tag' => 'required',
            'start_date' => 'required',
            'start_time' => 'required',
            'end_date' => 'required',
            'end_time' => 'required',
        ];      
        
        $sliderValidation = Validator::make($data, $rules);
     
        return $sliderValidation;
    }

}