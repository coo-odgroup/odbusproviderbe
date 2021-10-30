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
    	// Log::info($request);
        $start_date="";
        $end_date="";
    	$paginate = $request->rows_number;
        $bus_operator_id = $request->bus_operator_id;
        $rangeFromDate  =  $request->rangeFromDate;
        $rangeToDate  =  $request->rangeToDate;

         if(!empty($rangeFromDate))
        {
            if(strlen($rangeFromDate['month'])==1)
            {
                $rangeFromDate['month']="0".$rangeFromDate['month'];
            }
            if(strlen($rangeFromDate['day'])==1)
            {
                $rangeFromDate['day']="0".$rangeFromDate['day'];
            }

            $start_date = $rangeFromDate['year'].'-'.$rangeFromDate['month'].'-'.$rangeFromDate['day'] ;     
        }

        if(!empty($rangeToDate))
        {
            if(strlen($rangeToDate['month'])==1)
            {
                $rangeToDate['month']="0".$rangeToDate['month'];
            }
            if(strlen($rangeToDate['day'])==1)
            {
                $rangeToDate['day']="0".$rangeToDate['day'];
            }

            $end_date = $rangeToDate['year'].'-'.$rangeToDate['month'].'-'.$rangeToDate['day'] ;     
        }

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
	    	$data =$data->whereBetween('payment_date', [$start_date, $end_date]);
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