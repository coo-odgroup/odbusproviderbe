<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BannerValidator 
{   
    public function validate($data) { 

        $rules = [
            'user_id' => 'required',
            'heading' => 'required',
            'occassion' => 'required',
            'banner_img' => 'required',
            'alt_tag' => 'required',
            'start_date' => 'required',
            'start_time' => 'required',
            'end_date' => 'required',
            'end_time' => 'required',
        ];      
        
        $bannerValidation = Validator::make($data, $rules);
     
        return $bannerValidation;
    }

}