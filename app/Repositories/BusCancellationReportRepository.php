<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\BusCancelled;
use App\Models\BusCancelledDate;
use App\Models\BusOperator;
use App\Models\BusStoppage;
use App\Models\Location;


use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class BusCancellationReportRepository
{
    
    protected $seatOpen;
    protected $extraseatOpen;

    
    public function __construct(BusCancelled $busCancelled,Bus $bus,BusOperator $busOperator,BusStoppage $busStoppage,Location $location,BusCancelledDate $busCancelledDate)
    {
        $this->busCancelled = $busCancelled;
        $this->bus = $bus;
        $this->busOperator = $busOperator;
        $this->busStoppage = $busStoppage;
        $this->location = $location;
        $this->busCancelledDate = $busCancelledDate;
    }   

   
    public function getAll()
    {
         $data= $this->busCancelled->with('bus.BusSitting','bus.BusType','bus.busOperator','bus.ticketPrice','busCancelledDate')->where('status', 1)->get();
        $data_arr = array();
        foreach($data as $key=>$v)
        {
            $data_arr[]=$v->toArray();            

            $data_arr[$key]['from_location']=$this->location->where('id', $v->bus->ticketPrice[0]['source_id'])->get();
            $data_arr[$key]['to_location']=$this->location->where('id', $v->bus->ticketPrice[0]['destination_id'])->get();
        } 
       
        return $data_arr;     

    }

    public function getData($request)
    {
        // Log::info($request);exit;

        $start_date="";
        $end_date="";
        $paginate = $request->rows_number;
        $bus_operator_id = $request->bus_operator_id;
        $date_range = $request->date_range;
        $bus_id = $request->bus_id;
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
                 
        $data=  $this->busCancelled
                     ->with('bus.BusSitting','bus.BusType','bus.busOperator','bus.ticketPrice','busCancelledDate')
                     ->where('status', 1)
                     ->orderBy('id','DESC');


        if($paginate=='all') 
        {
            $paginate = "";
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
            $data = $data->whereBetween('created_at', [$start_date, $end_date]);
            
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