<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusGalleryValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'bus_id' => 'required|max:50',
            'icon' => 'required',
            'created_by' => 'required',
        ];      
      
        $busGalleryValidation = Validator::make($data, $rules);
        return $busGalleryValidation;
    }

}