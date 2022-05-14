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
     
    public function sendSms($data, $otp) 
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
     

       public function sendSmsTicketCancelCMO($data,$contact_number) 
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


      public function sendSmsTicketCancel($data) 
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

      public function sendSmsTicket($smsdata) 
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

      public function sendSmsCMO($smsdata,$contact_number)
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
            $message = str_replace("<DOJ>", $data['DOJ'],$message);
            $message = str_replace("<routedetails>",$data['routedetails'],$message);
            $message = str_replace("<seat>",$data['seat'],$message);
            $message = str_replace("<fare>",$data['fare'],$message);   
            $message = str_replace("<dep>",$data['dep'],$message);              
            $message = str_replace("<contactmob>",$data['contactmob'],$message);          
            $message = str_replace("<conmob>",$data['conmob'],$message);          
            $message = str_replace("<name>",$data['name'],$message);          

            $data = array(
                        'Booking_email'  =>'booking@odbus.in',
                        'Message'=> $message
                    );                           
            return [$data];        
      }

      public function sendCancelSmsToCustomer($smsdata) 
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

      public function sendCancelSmsToCMO($smsdata) 
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


       public function SendAgentCreationSms($data)
      {       

        
       // log::info($data);
            $apiKey = $this->credentials->first()->sms_textlocal_key;
            $textLocalUrl = config('services.sms.textlocal.url_send');
            $sender = config('services.sms.textlocal.senderid');
            $message = config('services.sms.textlocal.CREAT_AGENT');
            $apiKey = urlencode( $apiKey);
            $receiver = urlencode($data['phone']);
            $message = str_replace("<agentName>",$data['agentName'],$message);
            $message = str_replace("<url>",$data['url'],$message);
            $message = str_replace("<agentEmail>",$data['agentEmail'],$message);
            $message = str_replace("<agentPassword>", $data['agentPassword'],$message);  
            // Log::info($message); 
            $message = rawurlencode($message);
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



            // $message = config('services.sms.textlocal.CREAT_AGENT');                  
            // $message = str_replace("<agentName>",$data['agentName'],$message);
            // $message = str_replace("<url>",$data['url'],$message);
            // $message = str_replace("<agentEmail>",$data['agentEmail'],$message);
            // $message = str_replace("<agentPassword>", $data['agentPassword'],$message);          

            // $data = array(
            //             'Phone'  => $data['phone'],
            //             'Message'=> $message
            //         );                           
            // return [$data];        
      }

   


}
