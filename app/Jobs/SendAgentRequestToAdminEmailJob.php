<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;


class SendAgentRequestToAdminEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $to;
    protected $subject;
    protected $userEmail;
    protected $userName;

    public function __construct($to, $subject, $req)
    {
        
        $this->to = $to;
        $this->subject = $subject;
        $this->userName=$req['userName'];
        $this->userEmail=$req['userEmail'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data=[
            'userName'=>$this->userName,
            'userEmail' => $this->userEmail,
        ]; 
         // Log::info($data);
        Mail::send('SendAgentRequestToAdminEmail', $data, function ($messageNew) {              
            $messageNew->from(config('mail.contact.address'))           
            ->to($this->to)
            ->subject($this->subject);
        });


    }
}
