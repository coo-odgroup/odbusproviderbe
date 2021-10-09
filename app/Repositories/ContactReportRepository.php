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
    public function getAll()
    {
        $data = $this->contact->where('status', 1)->orderBy('id',"DESC")->get() ;
       
        return $data;     
    }


    public function getData($request)
    {
        $paginate = $request->rows_number; 
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

        $data= $this->contact->where('status', 1)
                             ->orderBy('id',"DESC");

        if (!empty($start_date) && !empty($end_date)) {
            $data = $data->whereBetween('created_at', [$start_date, $end_date]);
            
        }

                 $data=$data->paginate($paginate); 

        // $response = array(
        //      "count" => $data->count(), 
        //      "total" => $data->total(),
        //     "data" => $data
        //    ); 

           // Log::info($response);     
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

