<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class SendingEmailToSupportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $to;   
    protected $subject;

    public function __construct($to, $subject)
    {
        $this->to = $to;
        $this->subject = $subject;
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */


    public function handle($Booking_Email,$Email_Message)
    {
        $data = [
            'Email' => $Email_Message,
        ];
        
        Mail::send('EmailToBooking', $data, function ($messageNew) {
            $messageNew->from(config('mail.contact.address'))
            ->to($Booking_Email)
            ->subject($this->subject);
        });
        
        // check for failures
        if (Mail::failures()) {
            return new Error(Mail::failures()); 
            //return "Email failed";
        }else{
            return 'success';
        }

    }
}