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
        $start_date  =  $request->rangeFromDate;
        $end_date  =  $request->rangeToDate; 

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
            if($start_date == $end_date)
            {
                  $data =$data->where('created_at','like','%'.$start_date.'%');
            }
            else
            {
              $data = $data->whereBetween('created_at', [$start_date, $end_date]);        
            }         
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

