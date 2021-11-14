<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\Booking;
use App\Models\Location;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/

class AgentCommissionReportRepository
{
    protected $booking;
    protected $location;
    protected $bus;

    public function __construct(Booking $booking ,Location $location ,Bus $bus)
    {       
        $this->booking = $booking;       
        $this->location = $location;       
        $this->bus = $bus;       
    }    
    
    public function getData($request)
    {
        $start_date="";
        $end_date="";
        $paginate = $request->rows_number;
        $bus_operator_id = $request->bus_operator_id;
        $payment_id = $request->payment_id;
        $date_type = $request->date_type;
        $source_id = $request->source_id;
        $destination_id = $request->destination_id;

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

        $data= $this->booking->with('BookingDetail.BusSeats.seats',
                                    'BookingDetail.BusSeats.ticketPrice',
                                    'Bus','CustomerPayment')
                             ->with('bus.busstoppage')
                             ->whereHas('CustomerPayment', function ($query) {$query->where('payment_done', 1 );})
                             ->where('user_id', 2 )
                             ->orderBy('id','DESC');
        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) {
            $paginate = 10 ;
        }

        if(!empty($bus_operator_id))
        {
           $data=$data->whereHas('bus.busOperator', function ($query) use ($bus_operator_id) {$query->where('id', $bus_operator_id );});
        }

        if(!empty($payment_id))
        {
            $data=$data->whereHas('CustomerPayment', function ($query) use ($payment_id) {$query->where('razorpay_id', $payment_id );});
        }

        if(!empty($source_id) && !empty($destination_id))
        {
            $data=$data->where('source_id',$source_id)->where('destination_id',$destination_id);
        }


        if($date_type == 'booking' && $start_date == null && $end_date == null)
        {
            $data =$data->orderBy('created_at','DESC');
        }
        else if($date_type == 'booking' && $start_date != null && $end_date != null)
        {
            $data =$data->whereBetween('created_at', [$start_date, $end_date])
                        ->orderBy('created_at','DESC');
        }
        else if($date_type == 'journey' && $start_date == null && $end_date == null)
        {
            $data =$data->orderBy('journey_dt','DESC');
        }
         else if($date_type == 'journey' && $start_date != null && $end_date != null)
        {                 
             $data =$data-> whereBetween('journey_dt', [$start_date, $end_date])
                        ->orderBy('journey_dt','DESC');
        }
        $data=$data->paginate($paginate); 
        
        
   
        if($data){
            foreach($data as $key=>$v){

               $v['from_location']=$this->location->where('id', $v->source_id)->get();
               $v['to_location']=$this->location->where('id', $v->destination_id)->get();

               $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get();
               // $v['source']=[];
               // $v['destination']=[];
               foreach ($stoppage[0]['ticketPrice'] as $k => $a) 
                {                          
                    $stoppages['source'][$k]=$this->location->where('id', $a->source_id)->get();
                    $stoppages['destination'][$k]=$this->location->where('id', $a->destination_id)->get(); 
                }
                $v['source']= $stoppages['source'];
                $v['destination']= $stoppages['destination'];
            }
        }

      
        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   

      
           return $response;      

    }
    
}