<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class SendForgetOtpEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $to;
    protected $subject;
    protected $req;
    protected $otp;
    protected $name;

    public function __construct($to,$subject, $req)
    {
       
        $this->to = $to;
        $this->subject = $subject;
        $this->otp=$req['otp'];
        $this->name=$req['name'];
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data=[
            'otp'=>$this->otp,
            'name' => $this->name
        ];

       // Log::info($data);

        Mail::send('agentOtp', $data, function ($messageNew) {
            $messageNew->from(config('mail.contact.address'))
            ->to($this->to)
            ->subject($this->subject);
        });
    }
}
