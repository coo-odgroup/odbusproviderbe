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
    
    public function getData($request)
    {      
        $paginate = $request->rows_number;
        $bus_operator_id = $request->bus_operator_id;
        $payment_id = $request->payment_id;
        $pnr = $request->pnr;
        $date_type = $request->date_type;
        $source_id = $request->source_id;
        $destination_id = $request->destination_id;
        $start_date  =  $request->rangeFromDate;
        $end_date  =  $request->rangeToDate;
        $bus_id = $request->bus_id;
        $hasGst = $request->hasGst;
        $apiUser = $request->apiUser;
       
        // 'api_pnr','bus_name','bus_number'       ,'seat_name'
        $data= $this->booking->select('id','pnr', 'transaction_id', 'user_id' , 'users_id','bus_id','source_id','destination_id','journey_dt','boarding_point','dropping_point','boarding_time','dropping_time','origin','app_type','total_fare','owner_fare','odbus_gst_charges','odbus_gst_amount','odbus_charges','customer_gst_percent','customer_gst_number','customer_gst_business_name','customer_gst_business_email','customer_gst_business_address','customer_gst_amount','coupon_code','coupon_discount','payable_amount','transactionFee','additional_owner_fare','additional_special_fare','additional_festival_fare','agent_commission','updated_at','api_pnr','bus_name','bus_number')->with('User.role')

                            ->with(['BookingDetail' => function($query) {
                                        $query->select('id','booking_id','bus_seats_id','passenger_name','passenger_gender','passenger_age','seat_name') 
                                            ->with(['BusSeats' => function($quer) {
                                                   $quer->select('id','bus_id','ticket_price_id','ticket_price_id','seats_id')
                                       
                                            ->with(['ticketPrice' => function($que) {
                                                   $que->select('id','bus_id','source_id','destination_id','base_seat_fare','base_sleeper_fare','dep_time','arr_time');
                                            }]);

                                            $quer->with(['seats' => function($qu) {
                                                   $qu->select('id','seatText','rowNumber','berthType');
                                            }]);
                                       }]);
                                    }])
                            
                            ->with(['bus' => function($query) {
                                        $query->select('id','bus_operator_id','name','bus_number') 
                                            ->with(['busstoppage' => function($quer) {
                                        $quer->select('id','bus_id','source_id','destination_id','dep_time','arr_time');
                                       }]);
                                    }])
                            ->with(['Users' => function($query) {
                                        $query->select('id','name','email','phone') ;
                                    }])
                            ->with(['CustomerPayment' => function($query) {
                                        $query->select('id','booking_id','name','amount','order_id','razorpay_id') ;
                                    }])
                             ->where('status',1)
                             ->orderBy('id','DESC');

            // $u->with(["bookingDetail" => function($b){
            //     $b->with(["busSeats" => function($s){
            //         $s->with("seats");
            //       }]);
            // }]); 

        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) {
            $paginate = 10 ;
        }

        if(!empty($pnr))
        {
           $data=$data->where('pnr', $pnr );
        }  


        if(!empty($apiUser))
        {
           $data=$data->where('origin', $apiUser);
        } 

        if(!empty($hasGst) && $hasGst == true)
        {
           $data=$data->where('customer_gst_status', 1 )->where('customer_gst_number','!=', null );
        }

        if(!empty($bus_id))
        {
           $data=$data->where('bus_id', $bus_id );
        }       

        if(!empty($bus_operator_id))
        {
           $data=$data->whereHas('bus.busOperator', function ($query) use ($bus_operator_id) {$query->where('id', $bus_operator_id );});
        }

        if(!empty($payment_id))
        {
            $data=$data->whereHas('CustomerPayment', function ($query) use ($payment_id)  {$query->where('razorpay_id', $payment_id )->where('payment_done', '1' );})
                      ->orwhereHas('CustomerPayment', function ($query) use ($payment_id) {$query->where('order_id', $payment_id )->where('payment_done', '1' );});
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
            if($start_date == $end_date){
                $data =$data->where('created_at','like','%'.$start_date.'%')
                        ->orderBy('created_at','DESC');
                       
            }else{
                $data =$data->whereBetween('created_at', [$start_date, $end_date])
                        ->orderBy('created_at','DESC');
            }            
        }
        else if($date_type == 'journey' && $start_date == null && $end_date == null)
        {
            $data =$data->where('journey_dt', date('Y-m-d'))->orderBy('journey_dt','DESC');
        }
         else if($date_type == 'journey' && $start_date != null && $end_date != null)
        {
             if($start_date == $end_date){
                $data =$data->where('journey_dt', 'like','%'.$start_date.'%')
                        ->orderBy('journey_dt','DESC');
            }else{
                 $data =$data-> whereBetween('journey_dt', [$start_date, $end_date])
                        ->orderBy('journey_dt','DESC');
            }
        }
        $data=$data->paginate($paginate);   
        // log::info($data); 
        
        $totalfare = 0;
        $totalPayableAmount = 0;
        $owner_fare = 0;
        $totalAgentComission = 0;
        $totalSeats = 0;
        $journey = '';
        $current_dt = date("Y-m-d");
        $current_tt = date("h:i:s");
   
   // log::info($current_dt);
   // log::info($current_tt);


        if($data){
            foreach($data as $key=>$v){
                if($v->journey_dt == $current_dt){
                    if($v->boarding_time <  $current_tt){
                         $journey = 'Over';
                    }else{
                         $journey = 'Upcoming';
                    }
                }
                elseif($v->journey_dt > $current_dt){
                    $journey = 'Upcoming';
                }
                else{
                    $journey = 'Over';
                }

              $totalSeats = $totalSeats +  count($v->BookingDetail);               
               $totalfare = $totalfare + $v->total_fare;
               $totalAgentComission = $totalAgentComission + $v->agent_commission;
               $totalPayableAmount = $totalPayableAmount + $v->payable_amount;
            
               $owner_fare = $owner_fare + $v->owner_fare;
               $v['from_location']=$this->location->select('name')->where('id', $v->source_id)->get();
               $v['to_location']=$this->location->select('name')->where('id', $v->destination_id)->get();
               $v['journey']=$journey;

               $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get(); // where('status',1)
                $stoppages['source']=[];
                $stoppages['destination']=[]; 

                if(count($stoppage)>0){
                   foreach ($stoppage[0]['ticketPrice'] as $k => $a) 
                   {
                       $stoppages['source'][$k]=$this->location->select('name')->where('id', $a->source_id)->get();
                       $stoppages['destination'][$k]=$this->location->select('name')->where('id', $a->destination_id)->get(); 
                   }
                }

              
                $v['source']= $stoppages['source'];
                $v['destination']= $stoppages['destination'];
            }
        }


        $totalReceivedAmount = $totalPayableAmount - $totalAgentComission;

        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
             "totalSeats" => $totalSeats,
             "totalfare"=> number_format($totalfare, 2, ".", ""),
             "totalPayableAmount"=>number_format($totalReceivedAmount, 2, ".", ""),
             "owner_fare"=>number_format($owner_fare, 2, ".", ""),
            "data" => $data
           );  

          

           return $response;      

    }

    //Created By Chakra 26-04-2022 11:56 AM
    public function getPendingPNR($request)
    {
        // log::info($request);
      
        $paginate = $request->rows_number;
        $bus_operator_id = $request->bus_operator_id;
        $payment_id = $request->payment_id;
        $pnr = $request->pnr;
        $date_type = $request->date_type;
        $source_id = $request->source_id;
        $destination_id = $request->destination_id;
        $start_date  =  $request->rangeFromDate;
        $end_date  =  $request->rangeToDate;
       

        $data= $this->booking->with('BookingDetail.BusSeats.seats',
                                    'BookingDetail.BusSeats.ticketPrice',
                                    'Bus','Users','CustomerPayment','User.role')
                             ->with('bus.busstoppage')
                             ->where('status',0)
                             // ->whereHas('CustomerPayment', function ($query) {$query->where('payment_done', '1' );})
                             ->orderBy('id','DESC');
        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) {
            $paginate = 10 ;
        }

        if(!empty($pnr))
        {
           $data=$data->where('pnr', $pnr );
        }

       

        if(!empty($bus_operator_id))
        {
           $data=$data->whereHas('bus.busOperator', function ($query) use ($bus_operator_id) {$query->where('id', $bus_operator_id );});
        }

        if(!empty($payment_id))
        {
            $data=$data->whereHas('CustomerPayment', function ($query) use ($payment_id)  {$query->where('razorpay_id', $payment_id )->where('payment_done', '1' );})
                      ->orwhereHas('CustomerPayment', function ($query) use ($payment_id) {$query->where('order_id', $payment_id )->where('payment_done', '1' );});
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
            if($start_date == $end_date){
                $data =$data->where('created_at','like','%'.$start_date.'%')
                        ->orderBy('created_at','DESC');
                       
            }else{
                $data =$data->whereBetween('created_at', [$start_date, $end_date])
                        ->orderBy('created_at','DESC');
            }
            
        }
        else if($date_type == 'journey' && $start_date == null && $end_date == null)
        {
            $data =$data->where('journey_dt', date('Y-m-d'))->orderBy('journey_dt','DESC');
        }
         else if($date_type == 'journey' && $start_date != null && $end_date != null)
        {
             if($start_date == $end_date){
                $data =$data->where('journey_dt', 'like','%'.$start_date.'%')
                        ->orderBy('journey_dt','DESC');
            }else{
                 $data =$data-> whereBetween('journey_dt', [$start_date, $end_date])
                        ->orderBy('journey_dt','DESC');
            }
        }
        $data=$data->paginate($paginate); 

       
        $totalfare = 0;
        $totalPayableAmount = 0;
        $owner_fare = 0;
        $totalAgentComission = 0;
   
        if($data){
            foreach($data as $key=>$v){
                // log::info($v->agent_commission);
               
               $totalfare = $totalfare + $v->total_fare;
               $totalAgentComission = $totalAgentComission + $v->agent_commission;
               $totalPayableAmount = $totalPayableAmount + $v->payable_amount;
            
               $owner_fare = $owner_fare + $v->owner_fare;
               $v['from_location']=$this->location->where('id', $v->source_id)->get();
               $v['to_location']=$this->location->where('id', $v->destination_id)->get();

               $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get(); // where('status',1)
                $stoppages['source']=[];
                $stoppages['destination']=[];

            if(count($stoppage)>0){
               foreach ($stoppage[0]['ticketPrice'] as $k => $a) 
                {

                    $stoppages['source'][$k]=$this->location->where('id', $a->source_id)->get();
                    $stoppages['destination'][$k]=$this->location->where('id', $a->destination_id)->get(); 
                }
            }

                $v['source']= $stoppages['source'];
                $v['destination']= $stoppages['destination'];
            }
        }


          $totalReceivedAmount = $totalPayableAmount - $totalAgentComission ;
      
        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
             "totalfare"=> number_format($totalfare, 2, ".", ""),
             "totalPayableAmount"=>number_format($totalReceivedAmount, 2, ".", ""),
             "owner_fare"=>number_format($owner_fare, 2, ".", ""),
            "data" => $data
           );  

          

           return $response;      

    }
    
}