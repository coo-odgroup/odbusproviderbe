<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusValidator 
{   

    public function validate($data) { 
        
        $rules = [
            //'user_id' => 'required',
            //'bus_operator_id' => 'required',
            //'name' => 'required|max:50',
            //'via' => 'required|max:150',
            //'bus_number' => 'required',
            //'bus_description' => 'required',
            //'bus_type_id' => 'required',
            //'bus_sitting_id' => 'required',
            //'amenities_id' => 'required',
            //'cancellationslabs_id' => 'required',
            //'bus_seat_layout_id' => 'required',
            //'running_cycle' => 'required',
            //'popularity' => 'required',
            //'admin_notes' => 'required',
            //'has_return_bus' => 'required',
            //'return_bus_id' => 'required',
            //'cancelation_points' => 'required',
            //'created_by' => 'required',

        ];      
      
        $busValidation = Validator::make($data, $rules);
        return $busValidation;
    }

}