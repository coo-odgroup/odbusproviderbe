<?php

namespace App\Repositories;
use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\GatewayInformation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Jobs\SendEmailJob;
use App\Jobs\SendEmailTicketJob;
use App\Jobs\SendEmailTicketCancelJob;
use App\Mail\SendEmailOTP;
use Razorpay\Api\Api;
use App\Models\CustomerPayment;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\BusSeats;
use App\Repositories\ViewSeatsRepository;
use App\Models\Credentials;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
Use hash_hmac;
use Razorpay\Api\Errors\SignatureVerificationError;
use App\Services\ValueFirstService;

class ChannelRepository
{
    protected $users;
    protected $customerPayment;
    protected $booking;
    protected $bookingDetail;
    protected $busSeats;
    protected $credentials;

    public function __construct(Users $users,CustomerPayment $customerPayment,Booking $booking,BusSeats $busSeats,Credentials $credentials,BookingDetail $bookingDetail)
    {
        $this->users = $users;
        $this->customerPayment = $customerPayment;
        $this->booking = $booking;
        $this->busSeats = $busSeats;
        $this->credentials = $credentials;
        $this->bookingDetail = $bookingDetail;
    } 

//created by subhasis mohanty on 03 September 2025 for value first and textlocal integration
  public function sendSms($data, $otp)
{
   $SmsGW = config('services.sms.otpservice'); 
   
    if ($SmsGW === 'valuefirst') {
        return $this->sendSms_valueFirst($data, $otp);
    } else if ($SmsGW === 'textLocal' ) {
        return $this->sendSms_textlocal($data, $otp);
        
    }
}
    
     //Craeted by subhasis mohanty on 30Aug 2025
     public function sendSms_valueFirst($data, $otp) 
     {       

            $message = "Your OTP to register as agent is $otp . Do not share this with anyone - ODBUS";
            $valueFirst = new ValueFirstService();
            $response = $valueFirst->sendSms($data['phone'], $message);
            return $response;  
     }

    public function sendSms_textlocal($data, $otp) 
    {
        $SmsGW = config('services.sms.otpservice');

        if($SmsGW =='textLocal')
        {
            //Environment Variables
            //$apiKey = config('services.sms.textlocal.key');
            $apiKey = $this->credentials->first()->sms_textlocal_key;
            $textLocalUrl = config('services.sms.textlocal.url_send');
            $sender = config('services.sms.textlocal.senderid');
            $message = config('services.sms.textlocal.message');
            $apiKey = urlencode( $apiKey);
            $receiver = urlencode($data['phone']);
            $name = $data['name'];
            $message = str_replace("<otp>",$otp,$message);
            $message = str_replace("<name>",$name,$message);
            //return $message;
            $message = rawurlencode($message);
            $response_type = "json"; 
            $data = array('apikey' => $apiKey, 'numbers' => $receiver, "sender" => $sender, "message" => $message);

            $ch = curl_init($textLocalUrl);   
            curl_setopt($ch, CURLOPT_POST, true);
            //curl_setopt ($ch, CURLOPT_CAINFO, 'D:\ECOSYSTEM\PHP\extras\ssl'."/cacert.pem");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response);
             
            // return $response;
            //$msgId = $response->messages[0]->id;  // Store msg id in DB
            //session(['msgId'=> $msgId]);

            // $curlhttpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            // $err = curl_error($ch);
 
            // if ($err) { 
            //     return "cURL Error #:" . $err;
            // } 

        }elseif($SmsGW=='IndiaHUB'){
                $IndiaHubApiKey = urlencode('0Z6jDmBiAE2YBcD9kD4hVg');
                $otp = $data['otp'];
                // $IndiaHubApiKey = urlencode( $IndiaHubApiKey);
                // //$channel = 'transactional';
                // //$route =  '4';
                // //$dcs = '0';
                // //$flashsms = '0';
                // $smsIndiaUrl = 'http://cloud.smsindiahub.in/vendorsms/pushsms.aspx';
                // $receiver = urlencode($data['phone']);
                // $sender_id = urlencode($data['sender']);
                // $name = $data['name'];
                // $message = $data['message'];
                // $message = str_replace("<otp>",$otp,$message);
                // $message = rawurlencode($message);
    
                // $api = "$smsIndiaUrl?APIKey=".$IndiaHubApiKey."&sid=".$sender_id."&msg=".$message."&msisdn=".$receiver."&fl=0&gwid=2";
    
                // $response = file_get_contents($api);
                //return $response;
        }
    }
    //created by subhasis mohanty on 03 September 2025 for value first and textlocal integration
          public function sendSmsTicketCancelCMO($data,$contact_number)
{
   $SmsGW = config('services.sms.otpservice'); 
   
    if ($SmsGW === 'valuefirst') {
        return $this->sendSmsTicketCancelCMO_valueFirst($data,$contact_number);
    } else if ($SmsGW === 'textLocal' ) {
        return $this->sendSmsTicketCancelCMO_textlocal($data,$contact_number);
        
    }
}
 public function sendSmsTicketCancelCMO_valueFirst($data, $contact_number) 
{
    $seatList = implode(",", $data['seat']);
    $doj = $data['doj'];

    
    $message = "PNR: {$data['PNR']}, Bus Details: {$data['busdetails']}, "
             . "Route: {$data['route']}, DOJ: {$doj}, "
             . "Seat: {$seatList} is cancelled - ODBUS.";

    
    $valueFirst = new ValueFirstService();

     $numbers = array_filter(explode(',', $contact_number));

    foreach ($numbers as $number) {
        $number = trim($number); 

    $response = $valueFirst->sendSms($contact_number, $message);

    }
    \Log::info("Cancel Ticket SMS sent via ValueFirst", [
        'phone'    => $contact_number,
        'message'  => $message,
        'response' => $response
    ]);

    

    return $response;
}

       public function sendSmsTicketCancelCMO_textlocal($data,$contact_number) 
       {     
            $seatList = implode(",",$data['seat']);
            $doj = $data['doj'];
            $apiKey = $this->credentials->first()->sms_textlocal_key;
            $textLocalUrl = config('services.sms.textlocal.url_send');
            $sender = config('services.sms.textlocal.senderid');
            $message = config('services.sms.textlocal.cancelTicketCMO');
            $apiKey = urlencode( $apiKey);
            $receiver = urlencode($contact_number);
            $message = str_replace("<PNR>",$data['PNR'],$message);
            $message = str_replace("<busdetails>",$data['busdetails'],$message);
            $message = str_replace("<doj>",$doj,$message);
            $message = str_replace("<route>",$data['route'],$message);
            //$message = str_replace("<seat>",$data['seat'],$message);
            $message = str_replace("<seat>",$seatList,$message);
            //return $message;
            $message = rawurlencode($message);
            $response_type = "json"; 
            $data = array('apikey' => $apiKey, 'numbers' => $receiver, "sender" => $sender, "message" => $message);            

            $ch = curl_init($textLocalUrl);   
            curl_setopt($ch, CURLOPT_POST, true);
            //curl_setopt ($ch, CURLOPT_CAINFO, 'D:\ECOSYSTEM\PHP\extras\ssl'."/cacert.pem");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response);
            return $response;
            //$msgId = $response->messages[0]->id;  // Store msg id in DB
            session(['msgId'=> $msgId]);
       }

//created by subhasis mohanty on 03 September 2025 for value first and textlocal integration
              public function sendSmsTicketCancel($data)
{
   $SmsGW = config('services.sms.otpservice'); 
   
    if ($SmsGW === 'valuefirst') {
        return $this->sendSmsTicketCancel_valueFirst($data);
    } else if ($SmsGW === 'textLocal' ) {
        return $this->sendSmsTicketCancel_textlocal($data);
        
    }
}
// created by Subhasis Mohanty on 03 September 2025 for ValueFirst integration
public function sendSmsTicketCancel_valueFirst($data) 
{
    
    $seatList = implode(",", $data['seat']);
    $doj = date('d-m-Y', strtotime($data['doj']));

   
    $message = "PNR: {$data['PNR']}, Bus Details: {$data['busdetails']}, "
             . "Route: {$data['route']}, DOJ: {$doj}, "
             . "Seat: {$seatList}, Refund: {$data['refundAmount']} is cancelled - ODBUS.";

    $phone = $data['phone'];

    
    $valueFirst = new ValueFirstService();
    $response = $valueFirst->sendSms($phone, $message);

    
    Log::info("Cancel Ticket SMS sent via ValueFirst", [
        'phone'   => $phone,
        'message' => $message,
        'response'=> $response
    ]);

    return $response;
}

      public function sendSmsTicketCancel_textlocal($data) 
      {      
            $seatList = implode(",",$data['seat']);
            $doj = $data['doj'];
            $apiKey = $this->credentials->first()->sms_textlocal_key;
            $textLocalUrl = config('services.sms.textlocal.url_send');
            $sender = config('services.sms.textlocal.senderid');
            $message = config('services.sms.textlocal.cancelTicket');
            $apiKey = urlencode( $apiKey);
            $receiver = urlencode($data['phone']);
            $message = str_replace("<PNR>",$data['PNR'],$message);
            $message = str_replace("<busdetails>",$data['busdetails'],$message);
            $message = str_replace("<doj>",$doj,$message);
            $message = str_replace("<route>",$data['route'],$message);
            //$message = str_replace("<seat>",$data['seat'],$message);
            $message = str_replace("<seat>",$seatList,$message);
            $message = str_replace("<fare>",$data['refundAmount'],$message);
            //return $message;
            $message = rawurlencode($message);
            $response_type = "json"; 
            $data = array('apikey' => $apiKey, 'numbers' => $receiver, "sender" => $sender, "message" => $message);            

            $ch = curl_init($textLocalUrl);   
            curl_setopt($ch, CURLOPT_POST, true);
            //curl_setopt ($ch, CURLOPT_CAINFO, 'D:\ECOSYSTEM\PHP\extras\ssl'."/cacert.pem");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response);
            return $response;
            //$msgId = $response->messages[0]->id;  // Store msg id in DB
            session(['msgId'=> $msgId]);
      }

//created by subhasis mohanty on 03 September 2025 for value first and textlocal integration
        public function sendSmsTicket($smsdata)
{
   $SmsGW = config('services.sms.otpservice'); 
   log::info("SMS Gateway in use: ".$SmsGW);
    if ($SmsGW === 'valuefirst') {
        return $this->sendSmsTicket_valueFirst($smsdata);
    } else if ($SmsGW === 'textLocal' ) {
        return $this->sendSmsTicket_textlocal($smsdata);
        
    }
}

//creatyed by subhasis mohanty on 30Aug 2025
      public function sendSmsTicket_valueFirst($smsdata){
            $valueFirst = new ValueFirstService();
            $response = $valueFirst->sendSms($smsdata['mobile_no'], $smsdata['message']);
            return $response;
      }
      public function sendSmsTicket_textlocal($smsdata) 
      {  
            $SmsGW = config('services.sms.otpservice');  

            if($SmsGW =='textLocal')
            {
                //Environment Variables
                $apiKey = $this->credentials->first()->sms_textlocal_key;
                $textLocalUrl = config('services.sms.textlocal.url_send');
                $sender = config('services.sms.textlocal.senderid');
                $message = config('services.sms.textlocal.msgTicket');
                $apiKey = urlencode($apiKey);
                $receiver = urlencode($smsdata['mobile_no']); 
                $message = rawurlencode($smsdata['message']);
                $response_type = "json"; 
                $data = array('apikey' => $apiKey, 'numbers' => $receiver, "sender" => $sender, "message" => $message);  
                
                $ch = curl_init($textLocalUrl);   
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($ch);
                curl_close($ch);
                $response = json_decode($response); 
                return $response;  
            }
            elseif($SmsGW=='IndiaHUB')
            {
                    $IndiaHubApiKey = urlencode('0Z6jDmBiAE2YBcD9kD4hVg');
                    $otp = $data['otp'];
                    // $IndiaHubApiKey = urlencode( $IndiaHubApiKey);
                    // //$channel = 'transactional';
                    // //$route =  '4';
                    // //$dcs = '0';
                    // //$flashsms = '0';
                    // $smsIndiaUrl = 'http://cloud.smsindiahub.in/vendorsms/pushsms.aspx';
                    // $receiver = urlencode($data['phone']);
                    // $sender_id = urlencode($data['sender']);
                    // $name = $data['name'];
                    // $message = $data['message'];
                    // $message = str_replace("<otp>",$otp,$message);
                    // $message = rawurlencode($message);
        
                    // $api = "$smsIndiaUrl?APIKey=".$IndiaHubApiKey."&sid=".$sender_id."&msg=".$message."&msisdn=".$receiver."&fl=0&gwid=2";
        
                    // $response = file_get_contents($api);
                    //return $response;
            }
      } 
//created by subhasis mohanty on 03 September 2025 for value first and textlocal integration
          public function sendSmsCMO($smsdata,$contact_number)
{
   $SmsGW = config('services.sms.otpservice'); 
   
    if ($SmsGW === 'valuefirst') {
        return $this->sendSmsCMO_valueFirst($$smsdata,$contact_number);
    } else if ($SmsGW === 'textLocal' ) {
        return $this->sendSmsCMO_textlocal($$smsdata,$contact_number);
        
    }
}
public function sendSmsCMO_valueFirst($smsdata,$contact_number){
            $valueFirst = new ValueFirstService();
            $numbers = array_filter(explode(',', $contact_number));

    foreach ($numbers as $number) {
        $number = trim($number); 
            $response = $valueFirst->sendSms($number['mobile_no'], $smsdata['message']);
            return $response; 
}
}
      public function sendSmsCMO_textlocal($smsdata,$contact_number)
      {           
            $SmsGW = config('services.sms.otpservice');

            if($SmsGW =='textLocal')
            {
                //Environment Variables
                $apiKey = $this->credentials->first()->sms_textlocal_key;
                $textLocalUrl = config('services.sms.textlocal.url_send');
                $sender = config('services.sms.textlocal.senderid');               
                $apiKey = urlencode( $apiKey);
                $receiver = urlencode($contact_number);
                $message = rawurlencode($smsdata['message']);

                //return $message;
                $message = rawurlencode($message);
                $response_type = "json"; 
                $data = array('apikey' => $apiKey, 'numbers' => $receiver, "sender" => $sender, "message" => $message);
               // log::info($data);exit;
                
                $ch = curl_init($textLocalUrl);   
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($ch);
                curl_close($ch);
                $response = json_decode($response);               
                return $response;
            }
      } 

      public function createCancelTktFormatToCustomer($data)
      {             
            $message = config('services.sms.textlocal.cancelTicket');                  
            $message = str_replace("<PNR>",$data['PNR'],$message);
            $message = str_replace("<busdetails>",$data['busdetails'],$message);
            $message = str_replace("<doj>", $data['doj'],$message);
            $message = str_replace("<route>",$data['route'],$message);
            $message = str_replace("<seat>",$data['seat'],$message);
            $message = str_replace("<fare>",$data['refundAmount'],$message);          

            $data = array(
                        'Phone'  => $data['phone'],
                        'Message'=> $message
                    );                           
            return [$data];        
      }

      public function createCancelTktFormatToCMO($data)
      {             
            $message = config('services.sms.textlocal.cancelTicketCMO');                  
            $message = str_replace("<PNR>",$data['PNR'],$message);
            $message = str_replace("<busdetails>",$data['busdetails'],$message);
            $message = str_replace("<doj>", $data['doj'],$message);
            $message = str_replace("<route>",$data['route'],$message);
            $message = str_replace("<seat>",$data['seat'],$message);             

            $data = array(
                        'Phone'  => $data['phone'],
                        'Message'=> $message
                    );                           
            return [$data];        
      }

      public function createSMSTktFormatToBooking($data)
      {             
            $message = config('services.sms.textlocal.msgTicketBooking');                  
            $message = str_replace("<PNR>",$data['PNR'],$message);
            $message = str_replace("<busdetails>",$data['busdetails'],$message);
            $message = str_replace("<DOJ>", date('d-m-Y',strtotime($data['DOJ'])),$message);
            $message = str_replace("<routedetails>",$data['routedetails'],$message);
            $message = str_replace("<seat>",$data['seat'],$message);
            $message = str_replace("<fare>",$data['fare'],$message);   
            $message = str_replace("<dep>",$data['dep'],$message);              
            $message = str_replace("<contactmob>",$data['contactmob'],$message);          
            $message = str_replace("<conmob>",$data['conmob'],$message);          
            $message = str_replace("<name>",$data['name'],$message);          

            $data = array(
                        'PNR' => $data['PNR'],
                        'Booking_email'  =>'booking@odbus.in',
                        'Message'=> $message
                    );                           
            return [$data];        
      }
//created by subhasis mohanty on 03 September 2025 for value first and textlocal integration
           public function sendCancelSmsToCustomer($smsdata)
{
   $SmsGW = config('services.sms.otpservice'); 
   
    if ($SmsGW === 'valuefirst') {
        return $this->sendCancelSmsToCustomer_valueFirst($smsdata);
    } else if ($SmsGW === 'textLocal' ) {
        return $this->endCancelSmsToCustomer_textlocal($smsdata);
        
    }
}
public function sendCancelSmsToCustomer_valueFirst($smsdata){
            $valueFirst = new ValueFirstService();
            $response = $valueFirst->sendSms($smsdata['mobile_no'], $smsdata['message']);
            return $response; 
}
      public function sendCancelSmsToCustomer_textlocal($smsdata) 
      {  
            $SmsGW = config('services.sms.otpservice');  

            if($SmsGW =='textLocal')
            {
                //Environment Variables
                $apiKey = $this->credentials->first()->sms_textlocal_key;
                $textLocalUrl = config('services.sms.textlocal.url_send');
                $sender = config('services.sms.textlocal.senderid');
                $message = config('services.sms.textlocal.cancelTicket');
                $apiKey = urlencode($apiKey);
                $receiver = urlencode($smsdata['mobile_no']); 
                $message = rawurlencode($smsdata['message']);
                $response_type = "json"; 
                $data = array('apikey' => $apiKey, 'numbers' => $receiver, "sender" => $sender, "message" => $message);  

                //log::info($data);exit;
                
                $ch = curl_init($textLocalUrl);   
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($ch);
                curl_close($ch);
                $response = json_decode($response); 
                return $response;  
            }
            elseif($SmsGW=='IndiaHUB')
            {
                    $IndiaHubApiKey = urlencode('0Z6jDmBiAE2YBcD9kD4hVg');
                    $otp = $data['otp'];
                    // $IndiaHubApiKey = urlencode( $IndiaHubApiKey);
                    // //$channel = 'transactional';
                    // //$route =  '4';
                    // //$dcs = '0';
                    // //$flashsms = '0';
                    // $smsIndiaUrl = 'http://cloud.smsindiahub.in/vendorsms/pushsms.aspx';
                    // $receiver = urlencode($data['phone']);
                    // $sender_id = urlencode($data['sender']);
                    // $name = $data['name'];
                    // $message = $data['message'];
                    // $message = str_replace("<otp>",$otp,$message);
                    // $message = rawurlencode($message);
        
                    // $api = "$smsIndiaUrl?APIKey=".$IndiaHubApiKey."&sid=".$sender_id."&msg=".$message."&msisdn=".$receiver."&fl=0&gwid=2";
        
                    // $response = file_get_contents($api);
                    //return $response;
            }
      }
//created by subhasis mohanty on 03 September 2025 for value first and textlocal integration
              public function sendCancelSmsToCMO($smsdata)
{
   $SmsGW = config('services.sms.otpservice'); 
   
    if ($SmsGW === 'valuefirst') {
        return $this->sendCancelSmsToCMO_valueFirst($smsdata);
    } else if ($SmsGW === 'textLocal' ) {
        return $this->sendCancelSmsToCMO_textlocal($smsdata);
        
    }
}
public function sendCancelSmsToCMO_valueFirst($smsdata){
            $valueFirst = new ValueFirstService();
            $response = $valueFirst->sendSms($smsdata['mobile_no'], $smsdata['message']);
            return $response; 
}
      public function sendCancelSmsToCMO_textlocal($smsdata) 
      {  
            $SmsGW = config('services.sms.otpservice');  

            if($SmsGW =='textLocal')
            {
                //Environment Variables
                $apiKey = $this->credentials->first()->sms_textlocal_key;
                $textLocalUrl = config('services.sms.textlocal.url_send');
                $sender = config('services.sms.textlocal.senderid');
                $message = config('services.sms.textlocal.cancelTicketCMO');
                $apiKey = urlencode($apiKey);
                $receiver = urlencode($smsdata['mobile_no']); 
                $message = rawurlencode($smsdata['message']);
                $response_type = "json"; 
                $data = array('apikey' => $apiKey, 'numbers' => $receiver, "sender" => $sender, "message" => $message);  

                //log::info($data);exit;
                
                $ch = curl_init($textLocalUrl);   
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($ch);
                curl_close($ch);
                $response = json_decode($response); 
                return $response;  
            }
            elseif($SmsGW=='IndiaHUB')
            {
                    $IndiaHubApiKey = urlencode('0Z6jDmBiAE2YBcD9kD4hVg');
                    $otp = $data['otp'];
                    // $IndiaHubApiKey = urlencode( $IndiaHubApiKey);
                    // //$channel = 'transactional';
                    // //$route =  '4';
                    // //$dcs = '0';
                    // //$flashsms = '0';
                    // $smsIndiaUrl = 'http://cloud.smsindiahub.in/vendorsms/pushsms.aspx';
                    // $receiver = urlencode($data['phone']);
                    // $sender_id = urlencode($data['sender']);
                    // $name = $data['name'];
                    // $message = $data['message'];
                    // $message = str_replace("<otp>",$otp,$message);
                    // $message = rawurlencode($message);
        
                    // $api = "$smsIndiaUrl?APIKey=".$IndiaHubApiKey."&sid=".$sender_id."&msg=".$message."&msisdn=".$receiver."&fl=0&gwid=2";
        
                    // $response = file_get_contents($api);
                    //return $response;
            }
      }

      public function createBookingTktFormatToCustomer($data)
      {             
            $message = config('services.sms.textlocal.msgTicket');                  
            $message = str_replace("<PNR>",$data['PNR'],$message);
            $message = str_replace("<busdetails>",$data['busdetails'],$message);
            $message = str_replace("<DOJ>", $data['DOJ'],$message);
            $message = str_replace("<routedetails>",$data['route'],$message);
            $message = str_replace("<dep>",$data['dep'],$message);
            $message = str_replace("<name>",$data['Name'],$message);
            $message = str_replace("<gender>",$data['Gender'],$message);
            $message = str_replace("<seat>",$data['seat'],$message);
            $message = str_replace("<fare>",$data['fare'],$message);          
            $message = str_replace("<conmob>",$data['contactmob'],$message);          

            $data = array(
                        'to'      => $data['customermobile'],
                        'contents'=> $message
                    );                           
            return [$data];        
      }

      public function createBookingTktFormatToCMO($data)
      {             
            $message = config('services.sms.textlocal.msgTicketCMO');                  
            $message = str_replace("<PNR>",$data['PNR'],$message);
            $message = str_replace("<busdetails>",$data['busdetails'],$message);
            $message = str_replace("<DOJ>", $data['DOJ'],$message);
            $message = str_replace("<routedetails>",$data['route'],$message);
            $message = str_replace("<dep>",$data['dep'],$message);
            $message = str_replace("<name>",$data['Name'],$message);
            $message = str_replace("<gender>",$data['Gender'],$message);
            $message = str_replace("<seat>",$data['seat'],$message);
            $message = str_replace("<contactmob>",$data['contactmob'],$message);          

            $data = array(
                        'to'      => $data['CMO_mobile'],
                        'contents'=> $message
                    );                           
            return [$data];        
      }
      
}
