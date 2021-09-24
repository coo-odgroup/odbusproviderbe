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
        // Log:: info($request); exit;
        $paginate = $request->paginate ;
        // Log:: info($paginate);exit;

        if (empty($paginate)) 
        {
            $paginate = 5;
        }
        $data_arr = array();        

        $data= $this->booking->with('BookingDetail.BusSeats.seats',
                                    'BookingDetail.BusSeats.ticketPrice',
                                    'Bus','Users','CustomerPayment')
                             ->with('bus.busstoppage')
                             ->whereHas('CustomerPayment', function ($query) {$query->where('payment_done', '1' );})
                             ->orderBy('id','DESC')
                             ->paginate($paginate); 

        // foreach($data as $key=>$v)
        // {
        //     $data_arr[]=$v->toArray();
        //     $data_arr[$key]['from_location']=$this->location->where('id', $v->source_id)->get();
        //     $data_arr[$key]['to_location']=$this->location->where('id', $v->destination_id)->get();

        //      $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get();
            
           
        //     foreach ($stoppage[0]['ticketPrice'] as $k => $a) 
        //     {                          
        //         $data_arr[$key]['source'][$k]=$this->location->where('id', $a['source_id'])->get();
        //         $data_arr[$key]['destination'][$k]=$this->location->where('id', $a['destination_id'])->get(); 
        //     }
        // } 
        

        // return $data_arr;


        
        if($data){
            foreach($data as $key=>$v){

               $v['from_location']=$this->location->where('id', $v->source_id)->get();
               $v['to_location']=$this->location->where('id', $v->destination_id)->get();

               $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get();
               // $v['source']=[];
               // $v['destination']=[];
               foreach ($stoppage[0]['ticketPrice'] as $k => $a) 
                {                          
                    $stoppages['source'][$key][$k]=$this->location->where('id', $a->source_id)->get();
                    $stoppages['destination'][$key][$k]=$this->location->where('id', $a->destination_id)->get(); 
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