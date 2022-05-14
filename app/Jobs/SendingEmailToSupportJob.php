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
    protected $email;
    protected $pnr;
    protected $message;

    public function __construct($request)
    {
        $this->to = $request['Booking_Email'];        
        $this->pnr = $request['pnr'];        
        $this->message = $request['Booking_Msg'];        
    }

    /**
     * Execute the job.
     *
     * @return void
     */


    public function handle()
    {
        $data = [            
            'Email_Msg' => $this->message,
        ];

        $this->subject = config('services.email.subjectTicket');
        $this->subject = str_replace("<PNR>",$this->pnr,$this->subject);
        
        Mail::send('EmailToBooking', $data, function ($messageNew) {
                    $messageNew->to($this->to)            
                               ->subject($this->subject);
        });
        
        // check for failures
        if (Mail::failures()) {
            return new Error(Mail::failures()); 
            return "Email failed";
        }else{
            return 'success';
        }

    }
}