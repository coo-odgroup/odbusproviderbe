<?php

namespace App\Repositories;


use App\Models\Contact;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class ContactReportRepository
{
    protected $contact;
   
    public function __construct(Contact $contact)
    {              
        $this->contact = $contact;       
    }    
    
    public function getData($request)
    {
     // Log::info($request);
        $paginate = $request->rows_number;
        $operator_id = $request->bus_operator_id ;
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
        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }

        $data= $this->contact->with('BusOperator')->where('status', 1)
                             ->orderBy('id',"DESC");

        if (!empty($start_date) && !empty($end_date)) {
            $data = $data->whereBetween('created_at', [$start_date, $end_date]);            
        }
        
        if($operator_id!= null)
        {
           $data = $data->Where('bus_operator_id', $operator_id);
        }

        $data=$data->paginate($paginate); 

        return $data;

    } 

    public function deleteData($id)
    {
        $contact = $this->contact->find($id);
        $contact->status = 2;
        $contact->update();

        return $contact;      

    }
    
}

