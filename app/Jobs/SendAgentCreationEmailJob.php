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


class SendAgentCreationEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $to;
    protected $subject;
    protected $name;
    protected $userEmail;
    protected $userPassword;
    protected $userName;
    protected $loginUrl;
    public function __construct($to, $subject, $req)
    {
        
        $this->to = $to;
        $this->subject = $subject;
        $this->userName=$req['userName'];
        $this->userEmail=$req['userEmail'];
        $this->userPassword=$req['userPassword'];
        $this->loginUrl=$req['loginUrl'];

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
            'userPassword' => $this->userPassword,
            'loginUrl' => $this->loginUrl
        ]; 
        Mail::send('sendAgentCreationEmail', $data, function ($messageNew) {              
            $messageNew->from(config('mail.contact.address'))           
            ->to($this->to)
            ->subject($this->subject);
        });


    }
}
