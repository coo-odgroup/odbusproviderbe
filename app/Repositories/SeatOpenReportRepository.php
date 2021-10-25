<?php

namespace App\Repositories;

// use App\Models\Bus;
use App\Models\SeatOpen;
use App\Models\SeatOpenSeats;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;
use App\Models\Location;




/*Priyadarshi to Review*/
class SeatOpenReportRepository
{
    
    protected $seatOpen;
    protected $location;


    
    public function __construct(SeatOpen $seatOpen , SeatOpenSeats $seatsOpenSeats , Location $location)
    {
        $this->seatOpen = $seatOpen;
        $this->seatOpenSeats = $seatsOpenSeats;
        $this->location = $location; 
        $this->bus_operator_id = Config::get('constants.BUS_OPERATOR_ID');       

       
    }    
  

    public function getData($request)
    {
       // Log::info($request);

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
                 
        $data= $this->seatOpen
                    ->with('seatOpenSeats.seats')
                    ->with('bus','bus.busOperator','bus.ticketPrice')
                    ->where('status','1')
                    ->orderBy('id','DESC');

        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) {
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
            $data = $data->whereBetween('date_applied', [$start_date, $end_date]);
            
        }  

         $data=$data->paginate($paginate); 
         foreach ($data as $key => $v)
        {
            $data_arr[]=$v->toArray(); 
            $data_arr[$key]['date_applied']=date('j M Y',strtotime($v->date_applied));
            foreach ($data_arr as $e) 
            {
                $counter=0;
                foreach ($e['bus']['ticket_price'] as $sub=>$a) 
                {
                    $counter++;
                    if($counter==1)
                    {
                        $v['base_from_location']=$this->location->where('id', $a['source_id'])->get();

                        $v['base_to_location']=$this->location->where('id', $a['destination_id'])->get();
                    }
                    $data_arr[$key]['from_location'][$sub]=$this->location->where('id', $a['source_id'])->get();

                    $data_arr[$key]['to_location'][$sub]=$this->location->where('id', $a['destination_id'])->get();
                }     
                    $v['from_location'] = $data_arr[$key]['from_location'] ;
                    $v['to_location'] = $data_arr[$key]['to_location'] ;                     
            }           
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