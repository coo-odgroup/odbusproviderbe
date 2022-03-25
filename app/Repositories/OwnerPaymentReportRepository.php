<?php

namespace App\Repositories;


use App\Models\OwnerPayment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class OwnerPaymentReportRepository
{
    
    public function __construct(OwnerPayment $ownerPayment)
    {
        $this->ownerPayment = $ownerPayment;
    }   

    public function getData($request)
    {    	
    	$paginate = $request->rows_number;
        $bus_operator_id = $request->bus_operator_id;
        $start_date  =  $request->rangeFromDate;
        $end_date  =  $request->rangeToDate;

        

        $data = $this->ownerPayment->with('busOperator')->orderBy('id','DESC');
        if($paginate=='all') 
        {
            $paginate = "";
        }
        if($bus_operator_id!=null)
        {
        	 $data=$data->whereHas('busOperator', function ($query) use ($bus_operator_id) {$query->where('id', $bus_operator_id );});
        }

	    if(!empty($start_date) && !empty($end_date))
	    {
            if($start_date == $end_date)
            {
                  $data =$data->where('payment_date', $start_date);
            }
            else
            {
	    	    $data =$data->whereBetween('payment_date', [$start_date, $end_date]);
            }
	    }
	    $data=$data->paginate($paginate);
	    $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
	     
           return $response;  
   }
}