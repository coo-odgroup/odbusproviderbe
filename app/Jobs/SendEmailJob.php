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

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $to;
    protected $subject;
    protected $email_body;
    protected $name;
    protected $Age;

    public function __construct($to, $subject, $req)
    {
        // Log::info($req);
        $this->to = $to;
        $this->subject = $subject;
        $this->message= "Good Morning";
        $this->name=$req['name'];
        $this->Age=$req['Age'];
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data=[
            'content'=>$this->to,
            'name' => $this->name,
            'Age' => $this->Age
        ];
        Mail::send('email', $data, function ($messageNew) {
            $messageNew->from('support@odbus.in', 'ODBUS')
            ->to($this->to)
            ->subject($this->subject);
        });
    }
}
