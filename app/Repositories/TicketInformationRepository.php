<?php
namespace App\Repositories;
use App\Models\Users;
use App\Models\Bus;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\BusContacts;
use App\Models\CustomerPayment;
use App\Models\Location;
use App\Models\AgentWallet;
use App\Models\ManageSMS;
use App\Models\CustomSMS;
use App\Models\ApiClientWallet;
use App\Models\User;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use DB;
use App\Jobs\SendCancelTicketEmailJob;
use App\Jobs\SendCancelAdjTicketEmailJob;
use App\Repositories\ChannelRepository;

use App\Jobs\SendingEmailToSupportJob;
use App\Jobs\SendCancelEmailToSupportJob;
use App\Jobs\SendEmailToCustomerJob;

use App\Jobs\SendEmailToApiClientJob;
use App\Jobs\SendEmailToSupportJob;

use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Carbon\Carbon;
use DateTime;

class TicketInformationRepository
{  
    protected $users; 
    protected $location;
    protected $booking;
    protected $customerPayment;
    protected $bus;
    protected $channelRepository;
    protected $AgentWallet;
    protected $manageSMS;
    protected $customSMS;
    protected $bookingDetail;
    protected $apiClientWallet;
    protected $user;

    public function __construct(AgentWallet $AgentWallet,Location $location, Bus $bus,Users $users,Booking $booking , CustomerPayment $customerPayment,ChannelRepository $channelRepository,ManageSMS $manageSMS,CustomSMS $customSMS,BookingDetail $bookingDetail,ApiClientWallet $apiClientWallet,User $user)
    {
        $this->users = $users;
        $this->user = $user;
        $this->location = $location;
        $this->bus = $bus;
        $this->booking = $booking;
        $this->customerPayment = $customerPayment;
        $this->channelRepository = $channelRepository;
        $this->AgentWallet = $AgentWallet;
        $this->manageSMS = $manageSMS;
        $this->customSMS = $customSMS;
        $this->bookingDetail = $bookingDetail;
        $this->apiClientWallet = $apiClientWallet;


    }

    public function failedticketadjust($request)
    {
        $data = $this->booking->with('CustomerPaymentData','BookingDetail')->find($request->id);
        $data->status = 1;
        $data->update();

        $bookdet= $this->bookingDetail->where('booking_id',$request->id)->update(['status' => 1]);

        if($data->CustomerPaymentData!=null)
        {
            $cpd= $this->customerPayment->find($data->CustomerPaymentData->id);
            $cpd->payment_done = 1;
            $cpd->update();
        }     
       
    }

    public function failedticketadjustdata($request)
    {
        $paginate = $request->rows_number;
        $bus_operator_id = $request->bus_operator_id;
        // $payment_id = $request->payment_id;
        $pnr = $request->pnr;

        $data= $this->booking->with('BookingDetail.BusSeats.seats',
                                    'BookingDetail.BusSeats.ticketPrice',
                                    'Bus','Users','CustomerPaymentData')
                             ->with('bus.busstoppage')
                             ->whereNotNull('adjust_pnr')->where('booking_type','Adjust')
                             ->where('status','0')->where('updated_at','like','%'.date('Y-m-d').'%')
                             // ->whereHas('CustomerPaymentData', function ($query) {$query->where('payment_done', '0' );})
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

        // if(!empty($payment_id))
        // {
        //    $data=$data->whereHas('CustomerPaymentData', function ($query) use ($payment_id)        {$query->where('razorpay_id', $payment_id );})
        //               ->orwhereHas('CustomerPaymentData', function ($query) use ($payment_id) {$query->where('order_id', $payment_id );});
        // }

        //  if(!empty($source_id) && !empty($destination_id))
        // {
        //     $data=$data->where('source_id',$source_id)->where('destination_id',$destination_id);
        // }

            
        $data=$data->paginate($paginate); 

        if($data){
            foreach($data as $key=>$v){
               $v['from_location']=$this->location->where('id', $v->source_id)->get();
               $v['to_location']=$this->location->where('id', $v->destination_id)->get();
                  
                $stoppages['source']=[];
                $stoppages['destination']=[]; 

               $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get();
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

      
        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           ); 
        
        return $response;      

    }


    public function getPnrDetailsForSms($request)
    {
        $date = date('Y-m-d',strtotime("-1 days"));
        
        $pnr_Details = $this->booking->with('BookingDetail.BusSeats.seats',
                                    'BookingDetail.BusSeats.ticketPrice',
                                    'Bus','Users','User','CustomerPayment')
                             ->with('bus.busstoppage')
                             ->with('bus.BusType')
                             ->with('bus.BusSitting')
                             ->with('bus.busContacts')
                             ->with('bus.BusType.BusClass')
                             ->where('pnr',$request[0])
                             ->whereIn('status', [1,2])
                             ->orderBy('id','DESC')->get();
        
          if($pnr_Details){
            foreach($pnr_Details as $key=>$v){
               $v['from_location']=$this->location->where('id', $v->source_id)->get();
               $v['to_location']=$this->location->where('id', $v->destination_id)->get();
               }
           }
        return $pnr_Details;
    }

    public function getApiPnrDetails($request)
    {
        $date = date('Y-m-d',strtotime("-1 days"));
        
        $pnr_Details = $this->booking->with('BookingDetail.BusSeats.seats',
                                    'BookingDetail.BusSeats.ticketPrice',
                                    'Bus','Users','User','CustomerPayment')
                             ->with('bus.busstoppage')
                             ->with('bus.BusType')
                             ->with('bus.BusSitting')
                             ->with('bus.busContacts')
                             ->with('bus.BusType.BusClass')
                             ->where('pnr',$request[0])
                             ->where('status',1)
                             ->where('app_type','CLNTWEB')
                             ->orderBy('id','DESC')->get();
        
          if($pnr_Details){
            foreach($pnr_Details as $key=>$v){
               $v['from_location']=$this->location->where('id', $v->source_id)->get();
               $v['to_location']=$this->location->where('id', $v->destination_id)->get();
               }
           }
        return $pnr_Details;
    }


    public function getpnrdetails($request)
    {
        $date = date('Y-m-d',strtotime("-1 days"));
        
        $pnr_Details = $this->booking->with('BookingDetail.BusSeats.seats',
                                    'BookingDetail.BusSeats.ticketPrice',
                                    'Bus','Users','User','CustomerPayment')
                             ->with('bus.busstoppage')
                             ->with('bus.BusType')
                             ->with('bus.BusSitting')
                             ->with('bus.busContacts')
                             ->with('bus.BusType.BusClass')
                             ->where('pnr',$request[0])
                             ->where('status',1)
                             ->where('journey_dt','>',$date)
                             ->orderBy('id','DESC')->get();
        
          if($pnr_Details){
            foreach($pnr_Details as $key=>$v){
               $v['from_location']=$this->location->where('id', $v->source_id)->get();
               $v['to_location']=$this->location->where('id', $v->destination_id)->get();

               if($v->app_type=='AGENT' && $v->user_id!=0){

                 $balance = $this->AgentWallet->where('user_id',$v->user_id)->where('status',1)->orderBy('id','DESC')->limit(1)->first();
                 $v['agent_wallet_balance']=$balance;

               }



               }
           }
        return $pnr_Details;
    }

    public function apicancelticket($request){
      $id=$request->id ;

      $cancelticket = $this->booking->find($id);     
      $cancelticket->refund_amount = $request['refund_amount'];
      $cancelticket->cancel_reason = $request['reason'];             
      $cancelticket->created_by = $request['cancelled_by'];
      $cancelticket->status = 2;
      $cancelticket->update();

      $bus_details = DB::table('bus')->where('id',$cancelticket->bus_id)->first();    

      $transactionId = date('YmdHis') . gettimeofday()['usec'];
      $balance = $this->apiClientWallet->where('user_id',$cancelticket->user_id)->where('status',1)->orderBy('id','DESC')->limit(1)->get();
      
      // Adde By Lima :: 7-Jun-2025
      $checkIfRefund=DB::table("client_wallet")->where("type","Refund")->where("user_id",$cancelticket->user_id)->where("booking_id",$id)->where("amount",$request['refund_amount'])->first();
       
      if($checkIfRefund){
        return "Refund is successful";
      }
      /////////////////////////////
      $newBalance= $request['refund_amount'] +  $balance[0]->balance;   

      $ApiClientWallet = new $this->apiClientWallet;
      $ApiClientWallet->balance = number_format((float)$newBalance, 2, '.', '');
      $ApiClientWallet->amount = $request['refund_amount'] ;
      $ApiClientWallet->user_id = $cancelticket->user_id;
      $ApiClientWallet->type= "Refund";
      $ApiClientWallet->payment_via= "";
      $ApiClientWallet->status= 1;
      $ApiClientWallet->booking_id= $id;
      $ApiClientWallet->transaction_type= 'c';
      $ApiClientWallet->created_by = $request->cancelled_by;
      $ApiClientWallet->transaction_id = $transactionId;
      $ApiClientWallet->save();

      $client = $this->user->find($cancelticket->user_id);
      // $to_user = 'bishal.seofied@gmail.com';
      $to_user = $client->alternate_email;
      $to_support = 'support@odbus.in';
      
      $data= array(
          'user' => $client->name,
          'pnr'  => $cancelticket->pnr,
          'refundAmount' => $request->refund_amount,
          'reason' =>$request['reason'],
      );

    
      $subject = "TICKET CANCELLATION BY ODBUS - PNR : ".$cancelticket->pnr; 

      if($to_user!=null){
        SendEmailToApiClientJob::dispatch($to_user, $subject, $data);
      }
      

      SendEmailToSupportJob::dispatch($to_support, $subject, $data);

      /// send sms to conductor/owner
        $PNR_Details = $this->CancelPNRDetails($cancelticket->pnr); 
        $PNR_Details =$PNR_Details[0];
        $source_name = $this->location->where('id', $PNR_Details->source_id)->first();
        $destination_name = $this->location->where('id', $PNR_Details->destination_id)->first();

        $all_seats = '';
        if($PNR_Details->BookingDetail){
            foreach($PNR_Details->BookingDetail as $k=>$v)
            {
              $all_seats.= $v->BusSeats->seats->seatText.',';
            }
        }                   

        $all_seats = rtrim($all_seats, ','); 

        $smsData = array(
                        'phone' =>  $PNR_Details->users->phone,
                        'PNR' => $cancelticket->pnr,
                        'busdetails' => $bus_details->name.'-'.$bus_details->bus_number,
                        'doj' => date('d-m-Y',strtotime($PNR_Details->journey_dt)), 
                        'route' => $source_name->name.'-'.$destination_name->name,
                        'seat' => explode(',',$all_seats),
                        'refundAmount' =>$request->refund_amount
                    );

           Log::Info($smsData); 
           
           $busContactDetails = BusContacts::where('bus_id',$cancelticket->bus_id)
                                            ->where('status','1')
                                            ->where('cancel_sms_send','1')
                                            ->get('phone');

            if($busContactDetails->isNotEmpty()){
                $contact_number = collect($busContactDetails)->implode('phone',',');
                $this->channelRepository->sendSmsTicketCancelCMO($smsData,$contact_number);
            } 

      if($cancelticket->user_id == env('MANTIS_ID'))
      {
       $client = new \GuzzleHttp\Client();       
       $api_url = 'https://event.iamgds.com/provevents/odbus';

       // $access_token_url = $api_url.'ClientLogin';  

        $mantish_API= $client->request('POST', $api_url,  [
          'verify' => false,
          'form_params' => [
              "pnr" => $cancelticket->pnr,
              "status" => "cancelled",
              "cancel_reason" => $request['reason'],
              "refund_amount" => $request->refund_amount
          ]
        ]);
     
        log::info('TravelYari call-back URL has been Executed');

         // added b Lima :: 7-Jun-2025
         $ins['api_url']=$api_url;
         $ins['request']=json_encode([
             "pnr" => $cancelticket->pnr,
             "status" => "cancelled",
             "cancel_reason" => $request['reason'],
             "refund_amount" => $request->refund_amount
         ]);
         $ins['response']=json_encode($mantish_API);
         $ins['pnr']=$cancelticket->pnr;
         $ins['user_id']=$cancelticket->user_id;
         $ins['created_by']=$request->cancelled_by;
 
         DB::table("callback_api")->insert($ins);
         ///////////////////////////////////////////////////
         

      }
      elseif($cancelticket->user_id == env('PAYTM_ID'))
      {

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => env('PAYTM_PNR_CANCEL_URL'),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
            "pnr_list": [
                {
                    "pnr": "'.$cancelticket->pnr.'",
                          "doj": "'.$cancelticket->journey_dt.'",
                          "operator_id": "'.$bus_details->bus_operator_id.'",
                          "operator_pnr": "'.$cancelticket->pnr.'",
                          "primary_passenger": null
                }
            ]
        }',
          CURLOPT_HTTPHEADER => array(
            'VerifyKey: 6632596ff74049b8ad8c4a923e4a76c9',
            'UserId: 68',
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        log::info($response);
        log::info('PAYTM call-back URL has been Executed -- '.$cancelticket->pnr);

        // added b Lima :: 7-Jun-2025
        $ins['api_url']= env('PAYTM_PNR_CANCEL_URL');
        $ins['request']=json_encode([
            "pnr"=> $cancelticket->pnr,
            "doj"=> $cancelticket->journey_dt,
            "operator_id"=> $bus_details->bus_operator_id,
            "operator_pnr"=> $cancelticket->pnr,
            "primary_passenger"=> null
        ]);
        $ins['response']=json_encode($response);
        $ins['pnr']=$cancelticket->pnr;
        $ins['user_id']=$cancelticket->user_id;
        $ins['created_by']=$request->cancelled_by;

        DB::table("callback_api")->insert($ins);
        ///////////////////////////////////////////////////


      }
       

      return;

    }

    public function cancelticket($request)
    {   



        $res_sts = '';
        $pnr = $request->pnr;
        $id=$request->id ;
        $user_id=$request->user_id ;

        
            $cancelticket = $this->booking->find($id);   
            // log::info($cancelticket); 

                if( $cancelticket->origin == 'DOLPHIN'){
                     $client = new \GuzzleHttp\Client();       

                    $api_url = Config::get('constants.CONSUMER_API_URL');

                     $access_token_url = $api_url.'ClientLogin';  

                      $API_RESP_TOKEN = $client->request('POST', $access_token_url,  [
                        'verify' => false,
                        'form_params' => [
                            'client_id' => "odbusSasAdminApi",
                            'password' => "Admin@2010"
                        ]
                    ]);

                    $access_token_res = json_decode($API_RESP_TOKEN->getBody());

                    $access_token = $access_token_res->data;
        
                    $pnr_data['pnr']= $cancelticket->pnr; /////this for the cancellation of dolphine seat.

                     $res = $client->request('POST', $api_url.'CancelDolphinSeat',  [
                    'verify' => false,
                    'headers'=> ['Authorization' =>   "Bearer " . $access_token],
                    'form_params' => $pnr_data
                 ]);

                      $response = json_decode($res->getBody());
                      //log::info($response);
                      $res_sts = $response->data;
                }
                else
                {
                    $res_sts = 'success';
                }


            if($res_sts == 'success'){

                if($user_id!= NULL && $user_id>0)
                {
                    $transactionId = date('YmdHis') . gettimeofday()['usec'];

                    $balance = $this->AgentWallet->where('user_id',$user_id)->where('status',1)->orderBy('id','DESC')->limit(1)->get();
                 
                    $newBalance= $request['refund_amount'] +  $balance[0]->balance;

                     $chk_duplicate = $this->AgentWallet->where('user_id',$user_id)->where('amount',$request['refund_amount'])->where('type',"Refund")->where('booking_id',$id)->first();
                    if($chk_duplicate){
                    }
                    else{                    
                      $AgentWallet =  new $this->AgentWallet();
                      $AgentWallet->balance = number_format((float)$newBalance, 2, '.', '');
                      $AgentWallet->amount = $request['refund_amount'] ;
                      $AgentWallet->user_id = $user_id;
                      $AgentWallet->type= "Refund";
                      $AgentWallet->status= 1;
                      $AgentWallet->booking_id= $id;
                      $AgentWallet->transaction_type= 'c';
                      $AgentWallet->created_by = $request->cancelled_by;
                      $AgentWallet->transaction_id = $transactionId;
                      $AgentWallet->save();
                    }
                }
         
                $cancelticket->deduction_percent = $request['percentage_deduct'];
                $cancelticket->refund_amount = $request['refund_amount'];
                $cancelticket->cancel_reason = $request['reason'];             
                $cancelticket->cancel_by = $request['cancelled_by'];
                $cancelticket->status = $request['status'];
                $cancelticket->update();

                $PNR_Details = $this->CancelPNRDetails($pnr);            

                    $current_date_time = date("Y-m-d H:i:s");
                    
                    $all_seats = '';
                    if( $cancelticket->origin == 'DOLPHIN'){
                        foreach($PNR_Details[0]->BookingDetail as $k=>$v)
                        {
                             $all_seats.= $v->seat_name.',';
                        }   
                    }
                    else{
                        foreach($PNR_Details[0]->BookingDetail as $k=>$v)
                        {
                             $all_seats.= $v->BusSeats->seats->seatText.',';
                        }   
                    }
                       

                    $all_seats = rtrim($all_seats, ','); 
                    $source_name = $this->location->where('id', $PNR_Details[0]->source_id)->get();
                    $destination_name = $this->location->where('id', $PNR_Details[0]->destination_id)->get();

                    ///// send sms to customer
                    $getBus_id = $this->booking->with('Bus')->where("pnr",$pnr)->get();

                    $bus_id = $getBus_id[0]->bus_id;

                    if( $cancelticket->origin == 'DOLPHIN'){
                        $busName = $getBus_id[0]->bus_name;
                        $busNumber = $getBus_id[0]->bus_number;
                    }
                    else{
                        $busName = $getBus_id[0]->Bus->name;
                        $busNumber = $getBus_id[0]->Bus->bus_number;
                    }
                    

                    $smsData = array(
                        'phone' =>  $PNR_Details[0]->users->phone,
                        'PNR' => $pnr,
                        'busdetails' => $busName.'-'.$busNumber,
                        'doj' => date('d-m-Y',strtotime($PNR_Details[0]->journey_dt)), 
                        'route' => $source_name[0]->name.'-'.$destination_name[0]->name,
                        'seat' => explode(',',$all_seats),
                        'refundAmount' =>$request->refund_amount
                    );

                    //Send SMS To Customer
                    $this->channelRepository->sendSmsTicketCancel($smsData,$PNR_Details[0]->users->phone);

                    //Send SMS to CMO
                    if($cancelticket->origin != 'DOLPHIN'){
                        $busContactDetails = BusContacts::where('bus_id',$bus_id)
                                            ->where('status','1')
                                            ->where('cancel_sms_send','1')
                                            ->get('phone');

                        if($busContactDetails->isNotEmpty()){
                            $contact_number = collect($busContactDetails)->implode('phone',',');
                            $this->channelRepository->sendSmsTicketCancelCMO($smsData,$contact_number);
                        }
                    }


                    $data= array(
                        'contactNo' => $PNR_Details[0]->users->phone,
                        'pnr' => $pnr,
                        'journeydate' => date('d-m-Y',strtotime($PNR_Details[0]->journey_dt)), 
                        'route' => $source_name[0]->name.'-'.$destination_name[0]->name,
                        'seat_no' => explode(',',$all_seats),
                        'cancellationDateTime' => $current_date_time,
                        'deductionPercentage' => $request->percentage_deduct,
                        'refundAmount' => $request->refund_amount,
                        'totalfare' => $PNR_Details[0]->total_fare,
                    );

                    $subject = "TICKET CANCELLATION FROM ODBUS PNR ".$pnr; 

                    //Send Email To Customer
                    if($request['email'] != '')
                    {
                        $to_user = $request['email']; 
                        SendCancelAdjTicketEmailJob::dispatch($to_user, $subject, $data);
                    } 
                    /////// send email to odbus support 
                    SendCancelAdjTicketEmailJob::dispatch('support@odbus.in', $subject, $data);

                    
                  
                    //    $to_user = 'chakra.seoinfotechsolution@gmail.com';
                    //    //$to_user = $request['email'];
                    
                    //     $subject = "Ticket Cancel( Pnr.no-".$request->pnr." )";
                    //     $data= ['pnr'=>$request['pnr'],
                    //             'refund_amount' => $request['refund_amount'],
                    //             'deduction_percent'=> $request['percentage_deduct']
                    //           ] ;
                    //    //Send Email To Customer
                    //    SendCancelTicketEmailJob::dispatch($to_user, $subject, $data);

                return $cancelticket;
            }

    }

    public function CancelPNRDetails($pnr)
    {
        $pnr_Details = $this->booking->with('BookingDetail.BusSeats.seats',                                            
                                            'Bus','Users')
                                    ->where('pnr',$pnr)
                                    ->orderBy('id','DESC')->get();       
     
        return $pnr_Details;
    }

    

    public function cancelticketdata($request)
    {       
        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;

        $data= $this->booking->with('BookingDetail.BusSeats.seats',
                                    'BookingDetail.BusSeats.ticketPrice','Bus','Users',
                                    'CustomerPayment')
                             ->where('status',2)
                             ->whereNotNull('cancel_by')
                             ->orderBy('id','DESC');      

        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }

        if($name!=null)
        {
           $data = $data->where('pnr',$name );
                        
        }     

        $data=$data->paginate($paginate);

         if($data){
            foreach($data as $key=>$v){

               $v['from_location']=$this->location->where('id', $v->source_id)->get();
               $v['to_location']=$this->location->where('id', $v->destination_id)->get();

               //   $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get();
 
               // foreach ($stoppage[0]['ticketPrice'] as $k => $a) 
               //  {                          
               //      $stoppages['source'][$k]=$this->location->where('id', $a->source_id)->get();
               //      $stoppages['destination'][$k]=$this->location->where('id', $a->destination_id)->get(); 
               //  }
               //  $v['source']= $stoppages['source'];
               //  $v['destination']= $stoppages['destination'];
               }
           }


        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
        return $response;         
    } 

    public function adjustticketdata($request)
    {
       // log::info($request);
       // exit;
        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;
        $start_date  =  $request['rangeFromDate'];
        $end_date  =  $request['rangeToDate'];

        $data= $this->booking->with('BookingDetail.BusSeats.seats',
                                    'BookingDetail.BusSeats.ticketPrice','Bus','Users',
                                    'CustomerPayment')
                             ->where('status',1)
                             ->where('booking_type','Adjust')
                             //->whereHas('CustomerPayment', function ($query) {$query->where('payment_done', '1' );})
                             ->orderBy('id','DESC');      

        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }
        if($start_date != null && $end_date != null)
        {         
            if($start_date == $end_date){
                $data =$data->where('updated_at','like','%'.$start_date.'%')
                        ->orderBy('updated_at','DESC');
                       
            }else{
                $data =$data->whereBetween('updated_at', [$start_date, $end_date])
                        ->orderBy('updated_at','DESC');
            }            
        }

        if($name!=null)
        {
           $data = $data->where('pnr', $name  );
        }     

        $data=$data->paginate($paginate);

         if($data){
            $new_pnr = '';
            foreach($data as $key=>$v){
                    
                    $new_pnr   = $this->booking->with('BookingDetail.BusSeats.seats','Bus')
                             ->where('status',2)
                             ->where('pnr',$v->adjust_pnr)->get();
                   
                   $v['from_location']=$this->location->where('id', $v->source_id)->get();
                   $v['to_location']=$this->location->where('id', $v->destination_id)->get();
                   $v['new_pnr'] = $new_pnr ;
               }
           }


        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
        return $response; 
          
        
    }

    public function adjustticket($request)
    {
        /////// first check the seat is booked or on hold before cancelling pnr and insert new record to booking table

        $client = new \GuzzleHttp\Client();       

        $api_url = Config::get('constants.CONSUMER_API_URL');

        ////////// get access token first /////////////////

        $access_token_url = $api_url.'ClientLogin';  

          $API_RESP_TOKEN = $client->request('POST', $access_token_url,  [
            'verify' => false,
            'form_params' => [
                'client_id' => "odbusSasAdminApi",
                'password' => "Admin@2010"
            ]
        ]);

        $access_token_res = json_decode($API_RESP_TOKEN->getBody());

        $access_token = $access_token_res->data;
        ///////////////////////////////
        $checkSeat = "";
        if($request['bookingInfo']['origin'] == 'DOLPHIN')
        {
             $checkSeat = "SEAT AVAIL";
        }else
        {
            $url = $api_url.'CheckSeatStatus';   
             
            $API_RESP = $client->request('POST', $url,  [
                'headers'=> ['Authorization' =>   "Bearer " . $access_token],
                'verify' => false,
                'form_params' => [
                    'entry_date' => $request['bookingInfo']['journey_dt'],
                    'busId' => $request['bookingInfo']['bus_id'],
                    'sourceId' => $request['bookingInfo']['source_id'],
                    'destinationId' => $request['bookingInfo']['destination_id'],
                    'seatIds' => $request['bookingInfo']['seat_ids'],
                    "origin" =>  $request['bookingInfo']['origin'],
                    "ReferenceNumber" =>  $request['bookingInfo']['ReferenceNumber'],
                ]
            ]);

            $response = json_decode($API_RESP->getBody());
            $checkSeat = $response->data;
        }

        if($checkSeat =='SEAT AVAIL'){ // allow to pnr cancel and new booking record insert to booking table

             /////////////// insert to booking table with status 4 (Seat Hold) ///
            $bookingDetailarr=[];
            if(count($request['bookingInfo']['bookingDetail']) >0){

                foreach($request['bookingInfo']['bookingDetail'] as $bd){

                    $bookingDetailarr[]=array("bus_seats_id"=> $bd['bus_seats_id'],
                    "passenger_name"=> $bd['passenger_name'],
                    "passenger_gender"=> $bd['passenger_gender'],
                    "passenger_age"=> $bd['passenger_age'],
                    "created_by"=> $bd['created_by']);

                }

            }
            
            if($request['bookingInfo']['user_id']==0){

                $BookTicketBody = [ "customerInfo" => [
                    "email" => $request['customerInfo']['email'],
                    "phone"=> $request['customerInfo']['phone'],
                    "name"=> $request['customerInfo']['name']
                 ],
                  "bookingInfo" => [
                    "coupon_code"=>'', 
                    "user_id"=>$request['bookingInfo']['user_id'],
                    "bus_id"=> $request['bookingInfo']['bus_id'],
                    "source_id"=> $request['bookingInfo']['source_id'],
                    "destination_id"=>  $request['bookingInfo']['destination_id'],
                    "journey_date"=>  $request['bookingInfo']['journey_dt'],
                    "boarding_point"=>   $request['bookingInfo']['boarding_point'],
                    "dropping_point"=>   $request['bookingInfo']['dropping_point'],
                    "boarding_time"=>  date('H:i',strtotime($request['bookingInfo']['boarding_time'])),
                    "dropping_time"=>  date('H:i',strtotime($request['bookingInfo']['dropping_time'])),
                    "app_type"=>  $request['bookingInfo']['app_type'],
                    "typ_id"=>  $request['bookingInfo']['typ_id'],
                    "total_fare"=>  $request['bookingInfo']['total_fare'],
                    "specialFare"=>  $request['bookingInfo']['specialFare'],
                    "addOwnerFare"=>  $request['bookingInfo']['addOwnerFare'],
                    "festiveFare"=>  $request['bookingInfo']['festiveFare'],
                    "owner_fare"=>  $request['bookingInfo']['owner_fare'],
                    "transactionFee"=> $request['bookingInfo']['odbus_gst'],
                    "odbus_service_Charges"=>  $request['bookingInfo']['odbus_service_Charges'],
                    "adj_note"=>  $request['bookingInfo']['adj_note'],
                    "adjust_pnr"=>  $request['bookingInfo']['pnr'],
                    "status"=>  '4',
                    "booking_type" =>'Adjust',
                    "created_by"=>  $request['bookingInfo']['created_by'],
                    "bookingDetail" => $bookingDetailarr,
                    "origin" =>  $request['bookingInfo']['origin'],
                    "ReferenceNumber" =>  ($request['bookingInfo']['ReferenceNumber']!=NULL)? $request['bookingInfo']['ReferenceNumber'] : '',
                    "CompanyID"=> ($request['bookingInfo']['CompanyID']!=NULL)? $request['bookingInfo']['CompanyID'] : '',
                    "PickupID"=> ($request['bookingInfo']['PickupID']!=NULL)? $request['bookingInfo']['PickupID'] : '',
                    "DropID"=> ($request['bookingInfo']['DropID']!=NULL)? $request['bookingInfo']['DropID'] : '',
                    "RouteTimeID"=> ($request['bookingInfo']['RouteTimeID']!=NULL)? $request['bookingInfo']['RouteTimeID'] : '',
                  
                  ],              
                ];


                $url = $api_url.'BookTicket';
            }else{

                $BookTicketBody = [ 

                "agentInfo" => [
                    "email" => $request['bookingInfo']['agent_email'],
                    "phone"=> $request['bookingInfo']['agent_number'],
                    "name"=> $request['bookingInfo']['agent_name']
                ],     
                    
                "customerInfo" => [
                    "email" => $request['customerInfo']['email'],
                    "phone"=> $request['customerInfo']['phone'],
                    "name"=> $request['customerInfo']['name']
                ],
                  "bookingInfo" => [
                    "bus_id"=> $request['bookingInfo']['bus_id'],
                    "source_id"=> $request['bookingInfo']['source_id'],
                    "destination_id"=>  $request['bookingInfo']['destination_id'],
                    "journey_dt"=>  $request['bookingInfo']['journey_dt'],
                    "boarding_point"=>   $request['bookingInfo']['boarding_point'],
                    "dropping_point"=>   $request['bookingInfo']['dropping_point'],
                    "boarding_time"=>  date('H:i',strtotime($request['bookingInfo']['boarding_time'])),
                    "dropping_time"=>  date('H:i',strtotime($request['bookingInfo']['dropping_time'])),
                    "app_type"=>  $request['bookingInfo']['app_type'],
                    "typ_id"=>  $request['bookingInfo']['typ_id'],
                    "total_fare"=>  $request['bookingInfo']['total_fare'],
                    "specialFare"=>  $request['bookingInfo']['specialFare'],
                    "addOwnerFare"=>  $request['bookingInfo']['addOwnerFare'],
                    "festiveFare"=>  $request['bookingInfo']['festiveFare'],
                    "owner_fare"=>  $request['bookingInfo']['owner_fare'],
                    "transactionFee"=> $request['bookingInfo']['odbus_gst'],
                    "odbus_service_Charges"=>  $request['bookingInfo']['odbus_service_Charges'],
                    "adj_note"=>  $request['bookingInfo']['adj_note'],
                    "adjust_pnr"=>  $request['bookingInfo']['pnr'],
                    "status"=>  '4',
                    "booking_type" =>'Adjust',
                    "created_by"=>  $request['bookingInfo']['created_by'],
                    "bookingDetail" => $bookingDetailarr,
                    "origin" =>  $request['bookingInfo']['origin'],
                    "ReferenceNumber" =>  ($request['bookingInfo']['ReferenceNumber']!=NULL)? $request['bookingInfo']['ReferenceNumber'] : '',
                    "CompanyID"=> ($request['bookingInfo']['CompanyID']!=NULL)? $request['bookingInfo']['CompanyID'] : '',
                    "PickupID"=> ($request['bookingInfo']['PickupID']!=NULL)? $request['bookingInfo']['PickupID'] : '',
                    "DropID"=> ($request['bookingInfo']['DropID']!=NULL)? $request['bookingInfo']['DropID'] : '',
                    "RouteTimeID"=> ($request['bookingInfo']['RouteTimeID']!=NULL)? $request['bookingInfo']['RouteTimeID'] : '',
                  ],              
                ];

                // Log::info($BookTicketBody);

                $url = $api_url.'AgentBooking';

                //////////// update agent wallet table if there is any rest amount 

                if($request['bookingInfo']['rest_bal'] !=0 ){

                    $AgentWallet =  new $this->AgentWallet();
                    $AgentWallet->balance = $request['bookingInfo']['agent_wallet_balance']+ $request['bookingInfo']['rest_bal'] ;
                    $AgentWallet->amount = $request['bookingInfo']['rest_bal']  ;
                    $AgentWallet->user_id = $request['bookingInfo']['user_id'];
                    $AgentWallet->type= "Ticket Adjust";
                    $AgentWallet->status= 1;
                    $AgentWallet->booking_id= $request['bookingInfo']['id'];
                    $AgentWallet->transaction_type= ($request['bookingInfo']['rest_bal']>0) ? 'c' : 'd';
                    $AgentWallet->created_by =$request['bookingInfo']['created_by'];
                    $AgentWallet->transaction_id = time();
                    $AgentWallet->save();

                }
            }

             
             $res = $client->request('POST', $url,  [
                'verify' => false,
                'headers'=> ['Authorization' =>   "Bearer " . $access_token],
                'form_params' => $BookTicketBody
            ]);

            $get_booking_data = json_decode($res->getBody());
         

           if(isset($get_booking_data->data->id)){
            /////////////////////// this is for DOLPHIN //////////////////////////////
             if( $request['bookingInfo']['origin'] == 'DOLPHIN'){
                    $dolphin_block_data['transaction_id'] = $get_booking_data->data->transaction_id;

                 $res = $client->request('POST', $api_url.'BlockDolphinSeat',  [
                'verify' => false,
                'headers'=> ['Authorization' =>   "Bearer " . $access_token],
                'form_params' => $dolphin_block_data
            ]);

            $get_block_status= json_decode($res->getBody());
            if($get_block_status->data->Status != 1){
                $ret_data['status']=0;
                $ret_data['Message']=$get_block_status->data->Message;
                return $ret_data;
            }
           }

             /////////////// cancel pnr /////////////////////////
                $id=$request['bookingInfo']['id'] ;
                $cancelticket = $this->booking->find($id);
                $cancelticket->cancel_reason = $request['bookingInfo']['reason'];             
                $cancelticket->cancel_by = $request['bookingInfo']['created_by'];
                $cancelticket->cancel_type = "BOOKING ADJUSTMENT";
                $cancelticket->status = 2 ;
                $cancelticket->update();

                

                 if( $cancelticket->origin == 'DOLPHIN'){
                $pnr_data['pnr']= $cancelticket->pnr; /////this for the cancellation of dolphine seat.

                 $res = $client->request('POST', $api_url.'CancelDolphinSeat',  [
                'verify' => false,
                'headers'=> ['Authorization' =>   "Bearer " . $access_token],
                'form_params' => $pnr_data
                 ]);

                }

                if($request['bookingInfo']['user_id']==0){

                    /////// update customer payment table with adjust keywork concat for 3 payment columns
                    $customer_payment_id=$request['bookingInfo']['customer_payment_id'] ;
                    $customerPayment=$this->customerPayment->find($customer_payment_id);
                    $customerPayment->order_id  = 'ADJUST_'.time().'_'.$request['bookingInfo']['razorpay_order_id'];             
                    $customerPayment->razorpay_id  = 'ADJUST_'.time().'_'.$request['bookingInfo']['razorpay_payment_id'];
                    $customerPayment->razorpay_signature = 'ADJUST-'.time().'_'.$request['bookingInfo']['razorpay_signature'];
                    $customerPayment->update();
                    ////////// insert latest booking id to customer payment table /////
                    
                    $user_pay = new $this->customerPayment();
                    $user_pay->name = $request['customerInfo']['name'];
                    $user_pay->booking_id = $get_booking_data->data->id;
                    $user_pay->amount = $request['bookingInfo']['total_fare'];
                    $user_pay->order_id = $request['bookingInfo']['razorpay_order_id'];
                    $user_pay->razorpay_id  = $request['bookingInfo']['razorpay_payment_id'];
                    $user_pay->razorpay_signature = $request['bookingInfo']['razorpay_signature'];
                    $user_pay->payment_done = 1;                 
                    $user_pay->save();
                    
                }


               $pnr= $request['bookingInfo']['pnr'];
                ///// send sms to customer

                if($cancelticket->origin == 'ODBUS')
                {
                    $getBus_id=$this->booking->with('Bus')->where("pnr",$pnr)->get();

                    // $bus_id= $getBus_id[0]->bus_id;
                    $busName= $getBus_id[0]->Bus->name;
                    $busNumber= $getBus_id[0]->Bus->bus_number;
                }
                else if($cancelticket->origin == 'DOLPHIN'){

                    $getBus_id=$this->booking->where("pnr",$pnr)->get();
                    
                    $busName= $getBus_id[0]->bus_name;
                    $busNumber= $getBus_id[0]->bus_number;
                }

                $bus_id= $getBus_id[0]->bus_id;
                $smsData = array(
                    'phone' => $request['customerInfo']['phone'],
                    'PNR' => $pnr,
                    'busdetails' => $busName.'-'.$busNumber,
                    'doj' => $cancelticket->journey_dt, 
                    'route' => $request['bookingInfo']['source_name'].'-'.$request['bookingInfo']['destination_name'],
                    'seat' => $request['bookingInfo']['seat_names'],
                    'refundAmount' =>0
                );

                $this->channelRepository->sendSmsTicketCancel($smsData,$request['customerInfo']['phone']);

                //////////// send sms to CMO

                if( $request['bookingInfo']['origin'] == 'ODBUS'){

                $busContactDetails = BusContacts::where('bus_id',$bus_id)
                ->where('status','1')
                ->where('cancel_sms_send','1')
                ->get('phone');
                if($busContactDetails->isNotEmpty()){
                    $contact_number = collect($busContactDetails)->implode('phone',',');
                    $this->channelRepository->sendSmsTicketCancelCMO($smsData,$contact_number);
                }
            }

                 ///// send email

                 $subject = "TICKET CANCELLATION FROM ODBUS PNR ".$pnr; 

                 
                 $current_date_time = date("Y-m-d H:i:s"); 

                 
                 $data= array(
                    'contactNo' => $request['customerInfo']['phone'],
                    'pnr' => $pnr,
                    'journeydate' => $cancelticket->journey_dt, 
                    'route' => $request['bookingInfo']['source_name'].'-'.$request['bookingInfo']['destination_name'],
                    'seat_no' => $request['bookingInfo']['seat_names'],
                    'cancellationDateTime' => $current_date_time,
                    'deductionPercentage' => 100,
                    'refundAmount' => 0,
                    'totalfare' => $request['bookingInfo']['payable_amount'],
                );

                    if($request['customerInfo']['email']!= ''){
                        $to_user = $request['customerInfo']['email']; 
                        SendCancelAdjTicketEmailJob::dispatch($to_user, $subject, $data);
                    } 
                        ////////

                        /////// send email to odbus support 
                        SendCancelAdjTicketEmailJob::dispatch('support@odbus.in', $subject, $data);


                       
              

                 ///////////////////// final ticket booking and email/sms sending //////////////////////
           $booking_date = date("d-m-Y");
           $journey_date = date("d-m-Y",strtotime($request['bookingInfo']['journey_dt']));

           //Log::info($request);

           if($request['bookingInfo']['user_id']==0){

            $final_arr=  [
                "transaction_id"=> $get_booking_data->data->transaction_id,
                "razorpay_payment_id" => $request['bookingInfo']['razorpay_payment_id'],
                 "razorpay_order_id" => $request['bookingInfo']['razorpay_order_id'],
                 "razorpay_signature" => $request['bookingInfo']['razorpay_signature']
            ]; 
 
            $url = $api_url.'UpdateAdjustStatus';
            $resp = $client->request('POST', $url,  [
               'verify' => false,
               'headers'=> ['Authorization' =>   "Bearer " . $access_token],
               'form_params' => $final_arr
           ]);
            return 'Booking is successful';

           }else{

            $final_arr=  [
                "transaction_id"=> $get_booking_data->data->transaction_id, 
                "customer_comission" =>$request['bookingInfo']['customer_comission']

            ]; 

 
            // log::info($final_arr);

            $url = $api_url.'AgentPaymentStatus';
            $resp = $client->request('POST', $url,  [
               'verify' => false,
               'headers'=> ['Authorization' =>   "Bearer " . $access_token],
               'form_params' => $final_arr
           ]);


            return 'Booking is successful';


           }

           }
           else
           {
            return 'ERROR OCCURRED';
           }

        }else{
            return 'SEAT NOT AVAIL';
        }     
    }  

    public function getDetailsSms($request)
    {
        $pnr = $request['pnr'] ;
        $action = $request['action'];     

        if($action == 'smsToCustomer')
        {
            $type = 'customer';

            $sms_Details = $this->manageSMS->where('pnr',$pnr)                                       
                                           ->where('type',$type)
                                           ->get();  
            if($sms_Details == '[]')                                           
            {
                $data = $this->booking->with('Bus','Users','bus.busContacts','BookingDetail.BusSeats.seats')->where("pnr",$pnr)->get();

                $all_seats = '';
                
                $nameList = "";
                $genderList = "";
                $passengerDetails = $data[0]->BookingDetail;
                $i = 0;
                $m = 0;
                $f = 0;
                $O = 0;

                foreach($passengerDetails as $pDetail)
                {
                    if($i==0){
                      $nameList = "{$nameList},{$pDetail->passenger_name}";
                    }
                   
                    $i++;
                    switch($pDetail->passenger_gender)
                    {
                        case("M"):
                            $m++;
                        break;
                        case("F"):
                            $f++;
                        break;
                        case("O"):
                            $O++;
                        break;
                    }

                    $all_seats.= $pDetail->BusSeats->seats->seatText.',';
                } 
              
                if($m>0 && $f>0 && $O > 0){
                    $genderList = "{$m}M/{$f}F/{$O}O";
                }
        
                else if($m>0 && $f>0 && $O == 0){
                    $genderList = "{$m}M/{$f}F";
                }
        
                else if($m>0 && $f==0 && $O > 0){
                    $genderList = "{$m}M/{$O}O";
                }
        
                else if($m==0 && $f>0 && $O > 0){
                    $genderList = "{$f}F/{$O}O";
                }
        
                else if($m>0 && $f==0 && $O == 0){
                    $genderList = "{$m}M";
                }
        
                else if($m==0 && $f>0 && $O == 0){
                    $genderList = "{$f}F";
                }
        
                else if($m==0 && $f==0 && $O > 0){
                    $genderList = "{$O}O";
                }
        
                if(count($passengerDetails) > 1)
                {
                    $restNo = count($passengerDetails) -1 ;            
                    $nameList = "{$nameList}+{$restNo}";         
                }               
                $nameList = substr($nameList,1);
                $all_seats = rtrim($all_seats, ','); 
                //log::info($genderList); exit;        
                
                $source_name = $this->location->where('id', $data[0]->source_id)->get();
                $destination_name = $this->location->where('id', $data[0]->destination_id)->get();
    
                $bus_id = $data[0]->bus_id;
                $busName = $data[0]->Bus->name;
                $busNumber = $data[0]->Bus->bus_number;    
                $conductor_mobile = $data[0]->Bus->BusContacts[0]->phone;
                $customer_mobile = $data[0]->Users->phone;

                $smsData = array(                   
                    'PNR' => $pnr,
                    'busdetails' => $busName.'-'.$busNumber,
                    'DOJ' => date('d-m-Y',strtotime($data[0]->journey_dt)), 
                    'route' => $source_name[0]->name.'-'.$destination_name[0]->name,
                    'dep' => $data[0]->boarding_time,
                    'Name' => $nameList,
                    'Gender' => $genderList,
                    'seat' => $all_seats,
                    'fare' => $data[0]->total_fare,   
                    'contactmob' => $conductor_mobile,  
                    'customermobile'=>$customer_mobile   
                ); 
                
                $sms_Details =  $this->channelRepository->createBookingTktFormatToCustomer($smsData); 

                //log::info($SMS); exit;     
            }                                         
        }
        else if($action == 'smsToConductor')
        {            
            $type = 'cmo';

            $sms_Details = $this->manageSMS->where('pnr',$pnr)                                       
                                           ->where('type',$type)
                                           ->get(); 

            if($sms_Details == '[]')                                           
            {
                $data = $this->booking->with('Bus','Users','bus.busContacts','BookingDetail.BusSeats.seats')->where("pnr",$pnr)->get();

                //log::info($data);exit;

                $all_seats = '';                
                $nameList = "";
                $genderList = "";
                $passengerDetails = $data[0]->BookingDetail;
                $i = 0;
                $m = 0;
                $f = 0;
                $O = 0;

                foreach($passengerDetails as $pDetail)
                {
                    if($i==0){
                        $nameList = "{$nameList},{$pDetail->passenger_name}";
                    }
                    
                    $i++;
                    switch($pDetail->passenger_gender)
                    {
                        case("M"):
                            $m++;
                        break;
                        case("F"):
                            $f++;
                        break;
                        case("O"):
                            $O++;
                        break;
                    }

                    $all_seats.= $pDetail->BusSeats->seats->seatText.',';
                } 
                
                if($m>0 && $f>0 && $O > 0){
                    $genderList = "{$m}M/{$f}F/{$O}O";
                }
        
                else if($m>0 && $f>0 && $O == 0){
                    $genderList = "{$m}M/{$f}F";
                }
        
                else if($m>0 && $f==0 && $O > 0){
                    $genderList = "{$m}M/{$O}O";
                }
        
                else if($m==0 && $f>0 && $O > 0){
                    $genderList = "{$f}F/{$O}O";
                }
        
                else if($m>0 && $f==0 && $O == 0){
                    $genderList = "{$m}M";
                }
        
                else if($m==0 && $f>0 && $O == 0){
                    $genderList = "{$f}F";
                }
        
                else if($m==0 && $f==0 && $O > 0){
                    $genderList = "{$O}O";
                }
        
                if(count($passengerDetails) > 1)
                {
                    $restNo = count($passengerDetails) -1 ;            
                    $nameList = "{$nameList}+{$restNo}";         
                }               
                $nameList = substr($nameList,1);
                $all_seats = rtrim($all_seats, ','); 
                //log::info($genderList); exit;        
                
                $source_name = $this->location->where('id', $data[0]->source_id)->get();
                $destination_name = $this->location->where('id', $data[0]->destination_id)->get();
    
                $bus_id = $data[0]->bus_id;
                $busName = $data[0]->Bus->name;
                $busNumber = $data[0]->Bus->bus_number;    
                $conductor_mobile = $data[0]->Bus->BusContacts[0]->phone;
                $customer_mobile = $data[0]->Users->phone;

                $busContactDetails = BusContacts::where('bus_id',$bus_id)
                                                ->where('status','1')
                                                ->where('booking_sms_send','1')
                                                ->get('phone');
                
                if($busContactDetails->isNotEmpty())
                {
                    $CMO_mobile = collect($busContactDetails)->implode('phone',',');                                    
                }                       

                $smsData = array(                   
                    'PNR' => $pnr,
                    'busdetails' => $busName.'-'.$busNumber,
                    'DOJ' => date('d-m-Y',strtotime($data[0]->journey_dt)), 
                    'route' => $source_name[0]->name.'-'.$destination_name[0]->name,
                    'dep' => $data[0]->boarding_time,
                    'Name' => $nameList,
                    'Gender' => $genderList,
                    'seat' => $all_seats,                     
                    'contactmob' => $customer_mobile,  
                    'CMO_mobile'=>$CMO_mobile   
                ); 

                //log::info($smsData);exit;

                $sms_Details =  $this->channelRepository->createBookingTktFormatToCMO($smsData); 
            }                                           
        }                     
            
        return $sms_Details;
    }

    public function getBookingID($request)
    {
        $pnr = $request['pnr'];
        $BookingID = $this->booking->select('id')
                                 ->where('pnr',$pnr)
                                 ->get();                                 
        return $BookingID;                                 
    }

    public function getEmailID($request)
    {
        $pnr = $request['pnr'];
        $action = $request['action'];        

        if($action == 'emailToCustomer' || $action == 'cancelemailToCustomer')
        {
            $result = $this->booking->with('users')
                                 ->where('pnr',$pnr)
                                 ->get();                                             
        }
        else  if($action == 'emailToBooking')
        {
            $data = $this->booking->with('Bus','Users','bus.busContacts','BookingDetail.BusSeats.seats')->where("pnr",$pnr)->get();

            $all_seats = '';
            $all_customers = '';

            foreach($data[0]->BookingDetail as $k=>$v)
            {
                //log::info($v->BusSeats->seats->seatText);
                $all_seats.= $v->BusSeats->seats->seatText.',';
                $all_customers .= $v->passenger_name.'('.$v->passenger_gender.'),';
            }  

            $all_seats = rtrim($all_seats, ','); 
            $all_customers = rtrim($all_customers, ', '); 
            $source_name = $this->location->where('id', $data[0]->source_id)->get();
            $destination_name = $this->location->where('id', $data[0]->destination_id)->get();

            $bus_id = $data[0]->bus_id;
            $busName = $data[0]->Bus->name;
            $busNumber = $data[0]->Bus->bus_number;

            $conductor_mobile = $data[0]->Bus->BusContacts[0]->phone;

            $smsData = array(
                'contactmob' => $data[0]->users->phone,
                'PNR' => $pnr,
                'busdetails' => $busName.'-'.$busNumber,
                'DOJ' => $data[0]->journey_dt, 
                'routedetails' => $source_name[0]->name.'-'.$destination_name[0]->name,
                'dep' => date('h:i A', strtotime($data[0]->boarding_time)),
                'seat' => $all_seats,
                'fare' => $data[0]->total_fare,
                'conmob'=>$conductor_mobile,
                'name'  => $all_customers
            );
        
            $result = $this->channelRepository->createSMSTktFormatToBooking($smsData); 
        }        

        return $result;                                 
    }

    public function getModel($data, CustomSMS $customSMS)
    {
        $customSMS->pnr = $data['pnr'];
        $customSMS->booking_id  = $data['booking_id'];    
        $customSMS->type = $data['type'];    
        $customSMS->mobile_no = $data['mobile_no'];
        $customSMS->contents = $data['contents'];
        $customSMS->reason = $data['reason'];
        $customSMS->added_by = $data['added_by'];
        return $customSMS;
    }

    public function save_customSMS($data)
    {           
        $smsdata = array(
                        'mobile_no'=>$data['mobile_no'],
                        'message'=>$data['contents']
                   );      

        $SMS = $this->channelRepository->sendSmsTicket($smsdata);

        if($SMS)
        {
            $customSMS = new $this->customSMS;
            $customSMS = $this->getModel($data,$customSMS);
            $customSMS->save();
            return $customSMS;
        }       
    }

    public function save_CancelcustomSMSToCustomer($data)
    {           
        $smsdata = array(
                        'mobile_no'=>$data['mobile_no'],
                        'message'=>$data['contents']
                   );      

        $SMS = $this->channelRepository->sendCancelSmsToCustomer($smsdata);

        if($SMS)
        {
            $customSMS = new $this->customSMS;
            $customSMS = $this->getModel($data,$customSMS);
            $customSMS->save();
            return $customSMS;
        }       
    }

    public function save_CancelcustomSMSToCMO($data)
    {           
        $smsdata = array(
                        'mobile_no'=>$data['mobile_no'],
                        'message'=>$data['contents']
                   );      

         $SMS = $this->channelRepository->sendCancelSmsToCMO($smsdata);

        if($SMS)
        {
            $customSMS = new $this->customSMS;
            $customSMS = $this->getModel($data,$customSMS);
            $customSMS->save();
            return $customSMS;
        }       
    }

    public function GetCancelSmsToCustomer($request)
    {
        $pnr = $request['pnr'];

        $data = $this->booking->with('Bus','Users','BookingDetail.BusSeats.seats')->where("pnr",$pnr)->get();

         $all_seats = '';
         foreach($data[0]->BookingDetail as $k=>$v)
         {
              //log::info($v->BusSeats->seats->seatText);
              $all_seats.= $v->BusSeats->seats->seatText.',';
         }         
        $all_seats = rtrim($all_seats, ','); 
        $source_name = $this->location->where('id', $data[0]->source_id)->get();
        $destination_name = $this->location->where('id', $data[0]->destination_id)->get();

        $bus_id= $data[0]->bus_id;
        $busName= $data[0]->Bus->name;
        $busNumber= $data[0]->Bus->bus_number;

        $smsData = array(
            'phone' => $data[0]->users->phone,
            'PNR' => $pnr,
            'busdetails' => $busName.'-'.$busNumber,
            'doj' => $data[0]->journey_dt, 
            'route' => $source_name[0]->name.'-'.$destination_name[0]->name,
            'seat' => $all_seats,
            'refundAmount' => $data[0]->refund_amount
        );
       
        $result = $this->channelRepository->createCancelTktFormatToCustomer($smsData);    
        return $result ;
    }

    public function GetCancelSmsToCMO($request)
    {
        $pnr = $request['pnr'];
        $data = $this->booking->with('Bus','Users','User','BookingDetail.BusSeats.seats')->where("pnr",$pnr)->get(); 

        $seats = '';      

        $all_seats = '';
        foreach($data[0]->BookingDetail as $k=>$v)
        {
              //log::info($v->BusSeats->seats->seatText);
              $all_seats.= $v->BusSeats->seats->seatText.',';
        }         
        $all_seats = rtrim($all_seats, ','); 

        $source_name = $this->location->where('id', $data[0]->source_id)->get();
        $destination_name = $this->location->where('id', $data[0]->destination_id)->get();

        $bus_id= $data[0]->bus_id;
        $busName= $data[0]->Bus->name;
        $busNumber= $data[0]->Bus->bus_number;

        $busContactDetails = BusContacts::where('bus_id',$data[0]->bus_id)
                        ->where('status','1')
                        ->where('cancel_sms_send','1')
                        ->get('phone');                  

        if($busContactDetails->isNotEmpty()){
            $contact_number = collect($busContactDetails)->implode('phone',',');
        }

        $smsData = array(
            'phone' => $contact_number,
            'PNR' => $pnr,
            'busdetails' => $busName.'-'.$busNumber,
            'doj' => $data[0]->journey_dt, 
            'route' => $source_name[0]->name.'-'.$destination_name[0]->name,
            'seat' => $all_seats,
            'refundAmount' => $data[0]->refund_amount
        );

        //log::info($smsData);exit;

        $result = $this->channelRepository->createCancelTktFormatToCMO($smsData);    
        return $result ;
    }

    public function getBookingDetails($mobile,$pnr)
    { 
        return $this->users->where('phone',$mobile)->with(["booking" => function($u) use($pnr){
            $u->where('booking.pnr', '=', $pnr);            
            $u->with(["bus" => function($bs){
                $bs->with('cancellationslabs.cancellationSlabInfo');
                $bs->with('BusType.busClass');
                $bs->with('BusSitting');                
                $bs->with('busContacts');
            } ] );             
            $u->with(["bookingDetail" => function($b){
                $b->with(["busSeats" => function($s){
                    $s->with("seats");
                }]);
                }]); 
            }])->get();
    }

    public function GetLocationName($location_id){
        return $this->location->where('id',$location_id)->get();
    }

    public function getPassengerDetails($mobile,$pnr)
    { 
       return $this->users->where('phone',$mobile)->with(["booking" => function($u) use($pnr){
                                                $u->where('booking.pnr', '=', $pnr);
                                                $u->with(["bookingDetail" => function($b){
                                                    $b->with(["busSeats" => function($s){
                                                        $s->with("seats");
                                                    } ]);
                                                } ]);
                                            }])->get();
       
    }

    public function sendEmailToBooking($request)
    {
        SendingEmailToSupportJob::dispatch($request);

        return 'success';
    }

    public function sendEmailToCustomer($request)
    {        
        $b = $this->getBookingDetails($request['mobile'],$request['pnr']);

        if($b && isset($b[0]))
        {

            $main_source='';
            $main_destination='';

            $b=$b[0];
            $seat_arr=[];
            $seat_no='';
            $passengerDetails = [];
            $i = 0;
            foreach($b->booking[0]->bookingDetail as $bd)
            {
                array_push($seat_arr,$bd->busSeats->seats->seatText);                 
                
                $passengerDetails[$i]['passenger_name'] = $bd->passenger_name;
                $passengerDetails[$i]['passenger_gender'] = $bd->passenger_gender;
                $passengerDetails[$i]['passenger_age'] = $bd->passenger_age;
                $i++;
            }  

            if($b->booking[0]->origin=='ODBUS') {


                $ticketPrice= DB::table('ticket_price')->where('bus_id', $b->booking[0]->bus_id)->first();
            
                $main_source=Location::where('id',$ticketPrice->source_id)->first()->name;
                
                $main_destination = Location::where('id',$ticketPrice->destination_id)->first()->name;

                


            }
            
            $source_nm = $this->GetLocationName($b->booking[0]->source_id);
            $destination_nm = $this->GetLocationName($b->booking[0]->destination_id);          

            if($main_source!='' && $main_destination!=''){
                $routedetails=$main_source.'-to-'.$main_destination;
            }else{
                $routedetails= $source_nm[0]->name.'-to-'.$destination_nm[0]->name;
            }

            $body = [
                'name' => $b->name,
                'phone' => $b->phone,
                'email' => $request['email'],
                'pnr' => $b->booking[0]->pnr,
                'bookingdate'=> $b->booking[0]->created_at,
                'journeydate' => $b->booking[0]->journey_dt ,
                'boarding_point'=> $b->booking[0]->boarding_point,
                'dropping_point' => $b->booking[0]->dropping_point,
                'departureTime'=> $b->booking[0]->boarding_time,
                'arrivalTime'=> $b->booking[0]->dropping_time,
                'seat_no' => $seat_arr,
                'busname'=> $b->booking[0]->bus->name,
                 "source" => $source_nm[0]->name ,
                "destination" =>$destination_nm[0]->name,
                'busNumber'=> $b->booking[0]->bus->bus_number,
                'bustype' => $b->booking[0]->bus->busType->name,
                'busTypeName' => $b->booking[0]->bus->busType->busClass->class_name,
                'sittingType' => $b->booking[0]->bus->busSitting->name, 
                'conductor_number'=> $b->booking[0]->bus->busContacts[0]->phone,
                'passengerDetails' => $passengerDetails ,
                'totalfare'=> $b->booking[0]->total_fare,
                'discount'=> $b->booking[0]->coupon_discount,
                'payable_amount'=> $b->booking[0]->payable_amount,
                'odbus_gst'=> $b->booking[0]->odbus_gst_amount,
                'odbus_charges'=> $b->booking[0]->odbus_charges,
                'owner_fare'=> $b->booking[0]->owner_fare,
                'routedetails' => $routedetails  
            ];

            //log::info($body);exit;

            $cancellationslabs = $b->booking[0]->bus->cancellationslabs->cancellationSlabInfo;
            $transactionFee=$b->booking[0]->transactionFee;
            $customer_gst_status=$b->booking[0]->customer_gst_status;
            $customer_gst_number=$b->booking[0]->customer_gst_number;
            $customer_gst_business_name=$b->booking[0]->customer_gst_business_name;
            $customer_gst_business_email=$b->booking[0]->customer_gst_business_email;
            $customer_gst_business_address=$b->booking[0]->customer_gst_business_address;
            $customer_gst_percent=$b->booking[0]->customer_gst_percent;
            $customer_gst_amount=$b->booking[0]->customer_gst_amount;
            $coupon_discount=$b->booking[0]->coupon_discount;
            $totalfare=$b->booking[0]->total_fare;
            $discount=$b->booking[0]->coupon_discount;
            $payable_amount=$b->booking[0]->payable_amount;
            $odbus_charges = $b->booking[0]->odbus_charges;
            $odbus_gst = $b->booking[0]->odbus_gst_charges;
            $owner_fare = $b->booking[0]->owner_fare;
            $pnr = $b->booking[0]->pnr;

            if($request['email'] != '')
            {  
                 $sendEmailTicket = SendEmailToCustomerJob::dispatch($totalfare,$discount,$payable_amount,$odbus_charges,$odbus_gst,$owner_fare,$body, $pnr,$cancellationslabs,$transactionFee,$customer_gst_status,$customer_gst_number,$customer_gst_business_name,$customer_gst_business_email,$customer_gst_business_address,$customer_gst_percent,$customer_gst_amount,$coupon_discount);
            }
           
            return "Email has been sent to ".$b->email;

        }else{
            return "Invalid request";   
        }
        
    }

    public function cancelTicketInfo($mobile,$pnr)
    {
            return $this->users->where('phone',$mobile)->with(["booking" => function($u) use($pnr){
            $u->where('booking.pnr', '=', $pnr); 
            $u->with(["customerPayment" => function($b){
                $b->where('payment_done',1);
            }]);           
            $u->with(["bus" => function($bs){
                $bs->with('cancellationslabs.cancellationSlabInfo');
            }]);          
            $u->with(["bookingDetail" => function($b){
                $b->with(["busSeats" => function($s){
                    $s->with("seats");
                }]);
            }]);
            }])->get();    
     }

     public function sendCancelEmailToSupport($request)
     {
         $b =  $this->getBookingDetails($request['mobile'],$request['pnr']);

         if($b && isset($b[0]))
         {
             $b=$b[0];
             $seat_arr=[];
             $seat_no='';             
             foreach($b->booking[0]->bookingDetail as $bd)
             {
                 array_push($seat_arr,$bd->busSeats->seats->seatText);                 
             } 

             $source_nm = $this->GetLocationName($b->booking[0]->source_id);
             $destination_nm = $this->GetLocationName($b->booking[0]->destination_id); 

             $Email_Data = [
                'support_email' => $request['email'],
                'pnr' => $b->booking[0]->pnr, 
                'email' => $b->email,
                'contactNo' => $b->phone,
                'route' => $source_nm[0]->name."-".$destination_nm[0]->name,
                'journeydate' => $b->booking[0]->journey_dt, 
                'seat_no' => $seat_arr,
                'totalfare'=> $b->booking[0]->total_fare,
                'deductionPercentage'=>$b->booking[0]->deduction_percent,
                'refundAmount' => $b->booking[0]->refund_amount,
                'cancellationDateTime' => date('Y-m-d H:i:s',strtotime($b->booking[0]->updated_at))
            ];

            //log::info($Email_Data); exit;

            if($Email_Data['support_email'] !='')
            {
               $sendCancelEmailToSupport = SendCancelEmailToSupportJob::dispatch($Email_Data);
            } 
            
            return 'success';
         }   
     }
    

}