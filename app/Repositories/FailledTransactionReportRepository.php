<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\Booking;
use App\Models\Location;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;

use Razorpay\Api\Api;
use App\Models\Credentials;
use Razorpay\Api\Errors\SignatureVerificationError;


/*Priyadarshi to Review*/
class FailledTransactionReportRepository
{
    protected $booking;
    protected $location;
    protected $bus;
    protected $credentials;

    public function __construct(Booking $booking ,Location $location ,Bus $bus,Credentials $credentials)
    {       
        $this->booking = $booking;       
        $this->location = $location;       
        $this->bus = $bus;    
        $this->credentials = $credentials;   
    }  

      public function getRazorpayKey(){
          return $this->credentials->first()->razorpay_key;
      }

      public function getRazorpaySecret(){
        return $this->credentials->first()->razorpay_secret;
    }  

    public function getData($request)
    {    
        $paginate = $request->rows_number;
        $bus_operator_id = $request->bus_operator_id;
        $payment_id = $request->payment_id;
        $date_type = $request->date_type;
        $pnr = $request->pnr;
        $source_id = $request->source_id;
        $destination_id = $request->destination_id;


        $start_date  =  $request->rangeFromDate;
        $end_date  =  $request->rangeToDate;

        $data= $this->booking->with('BookingDetail.BusSeats.seats',
                                    'BookingDetail.BusSeats.ticketPrice',
                                    'Bus','Users','CustomerPaymentData')
                             ->with('bus.busstoppage')
                             ->whereHas('CustomerPaymentData', function ($query) {$query->where('payment_done', '0' );})
                             ->orderBy('id','DESC');
        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate== null) {
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
           $data=$data->whereHas('CustomerPaymentData', function ($query) use ($payment_id)        {$query->where('razorpay_id', $payment_id );})
                      ->orwhereHas('CustomerPaymentData', function ($query) use ($payment_id) {$query->where('order_id', $payment_id );});
        }

         if(!empty($source_id) && !empty($destination_id))
        {
            $data=$data->where('source_id',$source_id)->where('destination_id',$destination_id);
        }

        if($date_type == 'booking' && $start_date == null && $end_date == null)
        {
            $data =$data->where('journey_dt', date('Y-m-d'))->orderBy('created_at','DESC');
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
            $data =$data->orderBy('journey_dt','DESC');
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
          
          $key = $this->getRazorpayKey();
          $secretKey = $this->getRazorpaySecret();
   

        $api = new Api($key, $secretKey); 
        if($data){
            foreach($data as $key=>$v){
              //   $res = $api->order->fetch($v->CustomerPayment->order_id)->payments();
              //   if($res->items)
              //   {
              //     foreach ($res->items as $value) 
              //     {
              //       $paymentStatus = $value->status;   
                       
              //       if($paymentStatus == 'captured'){ //captured(Live), authorized(testing).
              //          $v['razerPayStatus']=$paymentStatus;
              //          $v['razerPayPaymentId']=$value->id;
              //          break;
              //       } 
              //     }                  
              // }
               $v['from_location']=$this->location->where('id', $v->source_id)->get();
               $v['to_location']=$this->location->where('id', $v->destination_id)->get();

               $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get();
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
        // Log:: info($response['data']); 
        
           return $response;      

    }



}