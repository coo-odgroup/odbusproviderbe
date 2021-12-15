<?php
namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusValidator 
{   

    public function validate($data) { 
        
        $rules = [
            'user_id' => 'required',
            'bus_operator_id' => 'required',
            'name' => 'required|max:50',
            'via' => 'required|max:150',
            'bus_number' => 'required',
            'bus_type_id' => 'required',
            'bus_sitting_id' => 'required',
            'amenities' => 'required|array|min:1',
            'cancellationslabs_id' => 'required',
            'bus_seat_layout_id' => 'required',
            'max_seat_book' => 'required',
            'created_by' => 'required',  
            'safety' => 'required|array|min:1', 
            'bus_seat_layout_data' => 'required|array|min:1', 
            'busRoutes' => 'required|array|min:1',            
            'busRoutes.*.source_id' => 'required',
            'busRoutes.*.sequence' => 'required',
            'busRoutes.*.sourceBoarding' => 'required|array|min:1',
            'busRoutes.*.sourceBoarding.*.sourceLocation' => 'required',
            'busRoutes.*.sourceBoarding.*.boarding_droping_id' => 'required',
            'busRoutes.*.sourceBoarding.*.sourceTime' => 'required',
            'busRoutesInfo' => 'required|array|min:1',            
            'busRoutesInfo.*.from_location' => 'required',            
            'busRoutesInfo.*.to_location' => 'required',            
            'busRoutesInfo.*.arr_days' => 'required',            
            'busRoutesInfo.*.dep_days' => 'required',            
            'busRoutesInfo.*.booking_seized' => 'required',            
            'busRoutesInfo.*.seater_fare' => 'required',            
            'busRoutesInfo.*.sleeper_fare' => 'required' 
        ];      
      
        $busValidation = Validator::make($data, $rules);
        return $busValidation;
    }

}