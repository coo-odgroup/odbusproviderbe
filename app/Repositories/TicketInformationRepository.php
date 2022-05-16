<?php
namespace App\Repositories;
use App\Models\Users;
use App\Models\Bus;
use App\Models\Booking;
use App\Models\BusContacts;
use App\Models\CustomerPayment;
use App\Models\Location;
use App\Models\AgentWallet;
use App\Models\ManageSMS;
use App\Models\CustomSMS;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

use App\Jobs\SendCancelTicketEmailJob;
use App\Jobs\SendCancelAdjTicketEmailJob;
use App\Repositories\ChannelRepository;

use App\Jobs\SendingEmailToSupportJob;
use App\Jobs\SendCancelEmailToSupportJob;
use App\Jobs\SendEmailToCustomerJob;

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

    public function __construct(AgentWallet $AgentWallet,Location $location, Bus $bus,Users $users,Booking $booking , CustomerPayment $customerPayment,ChannelRepository $channelRepository,ManageSMS $manageSMS,CustomSMS $customSMS)
    {
        $this->users = $users;
        $this->location = $location;
        $this->bus = $bus;
        $this->booking = $booking;
        $this->customerPayment = $customerPayment;
        $this->channelRepository = $channelRepository;
        $this->AgentWallet = $AgentWallet;
        $this->manageSMS = $manageSMS;
        $this->customSMS = $customSMS;
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
               }
           }
        return $pnr_Details;
    }

    public function cancelticket($request)
    {
        $id=$request->id ;
        $user_id=$request->user_id ;
        if($user_id!= NULL && $user_id>0)
        {
            $transactionId = date('YmdHis') . gettimeofday()['usec'];

            $balance = $this->AgentWallet->where('user_id',$user_id)->where('status',1)->orderBy('id','DESC')->limit(1)->get();
         
            $newBalance= $request['refund_amount'] +  $balance[0]->balance;
            
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
          
            $cancelticket = $this->booking->find($id);       
            $cancelticket->deduction_percent = $request['percentage_deduct'];
            $cancelticket->refund_amount = $request['refund_amount'];
            $cancelticket->cancel_reason = $request['reason'];             
            $cancelticket->cancel_by = $request['cancelled_by'];
            $cancelticket->status = $request['status'];
            $cancelticket->update();
          
            // $to_user = 'bishal.seofied@gmail.com';
           $to_user = $request['email'];
         
            $subject = "Ticket Cancel( Pnr.no-".$request->pnr." )";
            $data= ['pnr'=>$request['pnr'],
                    'refund_amount' => $request['refund_amount'],
                    'deduction_percent'=> $request['percentage_deduct']
                  ] ;
           SendCancelTicketEmailJob::dispatch($to_user, $subject, $data);

        return $cancelticket;
       
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

        if($name!=null)
        {
           $data = $data->where('created_by', $name )
                        ->orwhere('pnr', $name  );
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
    public function adjustticket($request)
    {
      // Log::info($request);
      // exit;
        /////// first check the seat is booked or on hold before cancelling pnr and insert new record to booking table

        $client = new \GuzzleHttp\Client();       

        $api_url = Config::get('constants.CONSUMER_API_URL');

        ////////// get access token first 

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
            ]
        ]);

        $response = json_decode($API_RESP->getBody());

        if($response->data =='SEAT AVAIL'){ // allow to pnr cancel and new booking record insert to booking table

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
                    "boarding_time"=>  $request['bookingInfo']['boarding_time'],
                    "dropping_time"=>  $request['bookingInfo']['dropping_time'],
                    "origin"=>  $request['bookingInfo']['origin'],
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
                    "status"=>  '4',
                    "booking_type" =>'Adjust',
                    "created_by"=>  $request['bookingInfo']['created_by'],
                    "bookingDetail" => $bookingDetailarr
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
                    "boarding_time"=>  $request['bookingInfo']['boarding_time'],
                    "dropping_time"=>  $request['bookingInfo']['dropping_time'],
                    "origin"=>  $request['bookingInfo']['origin'],
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
                    "status"=>  '4',
                    "booking_type" =>'Adjust',
                    "created_by"=>  $request['bookingInfo']['created_by'],
                    "bookingDetail" => $bookingDetailarr
                  ],              
                ];

                Log::info($BookTicketBody);

                $url = $api_url.'AgentBooking';
            }

             
             $res = $client->request('POST', $url,  [
                'verify' => false,
                'headers'=> ['Authorization' =>   "Bearer " . $access_token],
                'form_params' => $BookTicketBody
            ]);

           $get_booking_data = json_decode($res->getBody());

          // return $get_booking_data->data->id;

          Log::info($res->getBody());

         

           if(isset($get_booking_data->data->id)){
             /////////////// cancel pnr /////////////////////////
                $id=$request['bookingInfo']['id'] ;
                $cancelticket = $this->booking->find($id);
                $cancelticket->cancel_reason = $request['bookingInfo']['reason'];             
                $cancelticket->cancel_by = $request['bookingInfo']['created_by'];
                $cancelticket->cancel_type = "BOOKING ADJUSTMENT";
                $cancelticket->status = 2 ;
                // Log::info($cancelticket);exit;
                $cancelticket->update();

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

                 ///// send email

                 $subject = "TICKET CANCELLATION FROM ODBUS PNR ".$pnr;  

               if($request['customerInfo']['email']!= ''){

                        $to_user = $request['customerInfo']['email'];    
                       

                        $current_date_time = date("Y-m-d H:i:s"); 

                       
                        $data= array(
                            'email' => $to_user,
                            'contactNo' => $request['customerInfo']['phone'],
                            'pnr' => $pnr,
                            'journeydate' => $request['bookingInfo']['journey_dt'], 
                            'route' => $request['bookingInfo']['source_name'].'-'.$request['bookingInfo']['destination_name'],
                            'seat_no' => $request['bookingInfo']['seat_names'],
                            'cancellationDateTime' => $current_date_time,
                            'deductionPercentage' => 100,
                            'refundAmount' => 0,
                            'totalfare' => $request['bookingInfo']['payable_amount'],
                        );

                        SendCancelAdjTicketEmailJob::dispatch($to_user, $subject, $data);

                    } 
                        ////////

                        /////// send email to odbus support 
                        SendCancelAdjTicketEmailJob::dispatch('support@odbus.in', $subject, $data);

                        ///// send sms to customer

                        $getBus_id=$this->booking->with('Bus')->where("pnr",$pnr)->get();

                        $bus_id= $getBus_id[0]->bus_id;
                        $busName= $getBus_id[0]->Bus->name;
                        $busNumber= $getBus_id[0]->Bus->bus_number;

                        $smsData = array(
                            'phone' => $request['customerInfo']['phone'],
                            'PNR' => $pnr,
                            'busdetails' => $busName.'-'.$busNumber,
                            'doj' => $request['bookingInfo']['journey_dt'], 
                            'route' => $request['bookingInfo']['source_name'].'-'.$request['bookingInfo']['destination_name'],
                            'seat' => $request['bookingInfo']['seat_names'],
                            'refundAmount' =>0
                        );

                        $this->channelRepository->sendSmsTicketCancel($smsData,$request['customerInfo']['phone']);

                        //////////// send sms to CMO

                        $busContactDetails = BusContacts::where('bus_id',$bus_id)
                        ->where('status','1')
                        ->where('cancel_sms_send','1')
                        ->get('phone');
                        if($busContactDetails->isNotEmpty()){
                            $contact_number = collect($busContactDetails)->implode('phone',',');
                            $this->channelRepository->sendSmsTicketCancelCMO($smsData,$contact_number);
                        }
              

                 ///////////////////// final ticket booking and email/sms sending //////////////////////
           $booking_date = date("d-m-Y");
           $journey_date = date("d-m-Y",strtotime($request['bookingInfo']['journey_dt']));

           Log::info($request);

           if($request['bookingInfo']['user_id']==0){

            $final_arr=  [
                "transaction_id"=> $get_booking_data->data->transaction_id,
                "razorpay_payment_id" => $request['bookingInfo']['razorpay_payment_id'],
                 "razorpay_order_id" => $request['bookingInfo']['razorpay_order_id'],
                 "razorpay_signature" => $request['bookingInfo']['razorpay_signature']
            ]; 
 
 
            $url = $api_url.'PaymentStatus';
            $resp = $client->request('POST', $url,  [
               'verify' => false,
               'headers'=> ['Authorization' =>   "Bearer " . $access_token],
               'form_params' => $final_arr
           ]);
 
            Log::info($resp->getBody());

            return 'Booking is successful';

           }else{

            $final_arr=  [
                "transaction_id"=> $get_booking_data->data->transaction_id, 
                "customer_comission" =>$request['bookingInfo']['customer_comission']

            ]; 


            Log::info($final_arr);
 
 
            $url = $api_url.'AgentPaymentStatus';
            $resp = $client->request('POST', $url,  [
               'verify' => false,
               'headers'=> ['Authorization' =>   "Bearer " . $access_token],
               'form_params' => $final_arr
           ]);
 
            Log::info($resp->getBody());

            return 'Booking is successful';


           }

           

         // return $resp->getBody();

          //$get_final_response = json_decode($resp->getBody());

            

              
           }else{
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
        }
        else if($action == 'smsToConductor')
        {
            $type = 'cmo';

            $sms_Details = $this->manageSMS->where('pnr',$pnr)                                       
                                           ->where('type',$type)
                                           ->get();                                                     
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
    }

    public function sendEmailToCustomer($request)
    {        
        $b = $this->getBookingDetails($request['mobile'],$request['pnr']);

        if($b && isset($b[0]))
        {
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
            
            $source_nm = $this->GetLocationName($b->booking[0]->source_id);
            $destination_nm = $this->GetLocationName($b->booking[0]->destination_id);          

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
                'source'=> $source_nm[0]->name,
                'destination'=> $destination_nm[0]->name,
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
                'routedetails' => $source_nm[0]->name."-".$destination_nm[0]->name   
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
         }   
     }
    

}