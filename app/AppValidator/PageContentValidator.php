<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageContentValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'page_name' => 'required',
            'page_url' => 'required',
            'page_description' => 'required',
            'bus_operator_id' => 'required'
        ];      
      
        $pagecontentValidator = Validator::make($data, $rules);
        return $pagecontentValidator;
    }

}