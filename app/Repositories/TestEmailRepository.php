<?php
namespace App\Repositories;
use App\Jobs\SendEmailJob;

use Illuminate\Support\Facades\Log;

class TestEmailRepository
{
  
  
    
    public function __construct()
    {
        
    }
      
    public function send_email(){

        
	   $to = "bishal.seofied@gmail.com";
	   $subject = "Hello buddy";
	   $data=[
	   		"name"=>"Bishal",
	   		"Age" => 20
	   ];

	   SendEmailJob::dispatch($to, $subject, $data);
	}
    
      // public function sendEmailTicket($request, $pnr) 
      // {
      //  return SendEmailTicketJob::dispatch($request, $pnr);
      // }
}