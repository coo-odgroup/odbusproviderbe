<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SocialMediaValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'user_id' => 'required',
            'facebook_link' => 'required',
            'twitter_link' => 'required',
            'instagram_link' => 'required',
            'googleplus_link' => 'required',
            'linkedin_link' => 'required',
           
        ];      
      
        $socialmediaValidation = Validator::make($data, $rules);
        return $socialmediaValidation;
    }

}