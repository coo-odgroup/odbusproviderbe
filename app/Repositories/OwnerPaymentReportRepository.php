<?php

namespace App\Repositories;


use App\Models\OwnerPayment;


// use App\Models\TicketPrice;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class OwnerPaymentReportRepository
{
    
    public function __construct(OwnerPayment $ownerPayment)
    {
        $this->ownerPayment = $ownerPayment;
    }   

  
    public function getAll()
    {
    	return $this->ownerPayment->with('busOperator')->get();   

    }
    public function getData($request)
    {
    	
    	// Log::info($request);
    	$paginate = $request->rows_number;
        $bus_operator_id = $request->bus_operator_id;
        $date_range = $request->date_range;

        $data = $this->ownerPayment->with('busOperator')->orderBy('id','DESC');
        if($paginate=='all') 
        {
            $paginate = "";
        }
        if($bus_operator_id!=null)
        {
        	 $data=$data->whereHas('busOperator', function ($query) use ($bus_operator_id) {$query->where('id', $bus_operator_id );});
        }

	    if($date_range!=null)
	    {

	    	$data =$data->where('payment_date', $date_range );
	    }

	    $data=$data->paginate($paginate);

	     $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
	     // Log::info($response);
           return $response;  
   }
}