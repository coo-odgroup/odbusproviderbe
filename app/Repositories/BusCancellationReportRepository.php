<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\BusCancelled;
use App\Models\Location;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class BusCancellationReportRepository
{
    
    protected $seatOpen;
    protected $extraseatOpen;
    
    public function __construct(BusCancelled $busCancelled,Bus $bus,Location $location)
    {
        $this->busCancelled = $busCancelled;
        $this->bus = $bus;       
        $this->location = $location;
    }   
    public function getData($request)
    {
        $paginate = $request->rows_number;
        $bus_operator_id = $request->bus_operator_id;
        $date_range = $request->date_range;
        $bus_id = $request->bus_id;
        $start_date  =  $request->rangeFromDate;
        $end_date  =  $request->rangeToDate;

                 
        $data=  $this->busCancelled
                     ->with('bus.BusSitting','bus.BusType','bus.busOperator','bus.ticketPrice','busCancelledDate')
                     ->where('status', 1)
                     ->orderBy('id','DESC');

        if($request['USER_BUS_OPERATOR_ID']!="")
        {
            $data=$data->where('bus_operator_id',$request['USER_BUS_OPERATOR_ID']);
        }
        
        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate== null) {
            $paginate = 10 ;
        }


        if($bus_operator_id!=null)
        {
           $data=$data->whereHas('bus.busOperator', function ($query) use ($bus_operator_id) {$query->where('id', $bus_operator_id );});
        }
        if($bus_id!=null)
        {
            $data=$data->whereHas('bus', function ($query) use ($bus_id) {$query->where('id', $bus_id );});
        }
        if (!empty($start_date) && !empty($end_date)) {
            
            
            if($start_date == $end_date)
            {
                  $data = $data->whereHas('busCancelledDate', function ($query) use ($start_date) {$query->where('cancelled_date', $start_date );});
            }
            else
            {
                 $data = $data->whereHas('busCancelledDate', function ($query) use ($start_date,$end_date) {$query->whereBetween('created_at', [$start_date, $end_date]);});



                 // whereBetween('created_at', [$start_date, $end_date]);
            }
            
        }


        $data=$data->paginate($paginate); 

        foreach($data as $key=>$v)
        {     
            $v['from_location']=$this->location->where('id', $v->bus->ticketPrice[0]['source_id'])->get();
            $v['to_location']=$this->location->where('id', $v->bus->ticketPrice[0]['destination_id'])->get();
        }


        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           ); 

           // Log::info($response);     
           return $response;  
                    


    }


}