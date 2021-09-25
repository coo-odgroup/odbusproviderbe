<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\Booking;
use App\Models\Location;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class CompleteReportRepository
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
    public function getAll()
    {
        $data= $this->booking->with('BookingDetail.BusSeats.seats',
                                    'BookingDetail.BusSeats.ticketPrice',
                                    'Bus','Users','CustomerPayment')
                             ->with('bus.busstoppage')
                             ->whereHas('CustomerPayment', function ($query) {$query->where('payment_done', '1' );})
                             ->orderBy('id','DESC')
                             ->get() ; 

        $data_arr = array();
        foreach($data as $key=>$v)
        {
            $data_arr[]=$v->toArray();
            $data_arr[$key]['from_location']=$this->location->where('id', $v->source_id)->get();
            $data_arr[$key]['to_location']=$this->location->where('id', $v->destination_id)->get();

            $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get();
            
            foreach ($stoppage[0]['ticketPrice'] as $k => $a) 
            {                          
                $data_arr[$key]['source'][$k]=$this->location->where('id', $a['source_id'])->get();
                $data_arr[$key]['destination'][$k]=$this->location->where('id', $a['destination_id'])->get(); 
            }
        } 
       
        return $data_arr;     
    }


    public function getData($request)
    {
        $extqry = "";
        // Log:: info($request); exit;rozorpay_id,order_id,  where('created_at','Like',$current_date.'%')
        $paginate = $request->rows_number;
        $bus_operator_id = $request->bus_operator_id;
        $date_range = $request->date_range;
        $payment_id = $request->payment_id;
        $date_type = $request->date_type;

        $data= $this->booking->with('BookingDetail.BusSeats.seats',
                                    'BookingDetail.BusSeats.ticketPrice',
                                    'Bus','Users','CustomerPayment')
                             ->with('bus.busstoppage')
                             ->whereHas('CustomerPayment', function ($query) {$query->where('payment_done', '1' );})
                             ->orderBy('id','DESC');
        if($paginate=='all') 
        {
            $paginate = "";
        }

        if($bus_operator_id)
        {
           $data=$data->whereHas('bus.busOperator', function ($query) use ($bus_operator_id) {$query->where('id', $bus_operator_id );});
        }

        if($payment_id)
        {
            $data=$data->whereHas('CustomerPayment', function ($query) use ($payment_id) {$query->where('razorpay_id', $payment_id );});
        }


        if($date_type == 'booking' && $date_range =="")
        {
            $date =$data->orderBy('created_at','DESC');
        }
        else if($date_type == 'booking' && $date_range !="")
        {
            $date =$data->where('created_at','Like', $date_range."%" )
                        ->orderBy('created_at','DESC');
        }
        else if($date_type == 'journey' && $date_range =="")
        {
            $date =$data->orderBy('journey_dt','DESC');
        }
         else if($date_type == 'journey' && $date_range !="")
        {
             $date =$data->where('journey_dt', $date_range )
                        ->orderBy('journey_dt','DESC');
        }

        // if($date_range)
        // {
        //     $date
        // }

        
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