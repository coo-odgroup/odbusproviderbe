<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CancelBusValidator 
{   

    public function validate($data) { 
        
        $rules = [
            //'bus_id' => 'required',
            'bus_operator_id' => 'required',
            //'cancelled_date' => 'required',
            'reason' => 'required',
            'cancelled_by' => 'required',
            'reason' => 'required',
        ];      
        
        $cancelBusvalidation = Validator::make($data, $rules);
        return $cancelBusvalidation;
    }

}