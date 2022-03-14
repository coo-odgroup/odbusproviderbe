<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponTypeValidator 
{   
    public function validate($data) { 
        
        $rules = [          
          'coupon_type_name' => 'required|max:75',         
        ];      
      
        $typeValidation = Validator::make($data, $rules);
        return $typeValidation;
    }

}