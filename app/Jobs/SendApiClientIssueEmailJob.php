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


class SendApiClientIssueEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $to;
    protected $subject;
    protected $userName;
    protected $userEmail;
    protected $issueType;
    protected $issueSubType;
    protected $heading;
    protected $mesasage;

    public function __construct($to, $subject, $req)
    {
        
        $this->to = $to;
        $this->subject = $subject;
        $this->userName=$req['userName'];
        $this->userEmail=$req['userEmail'];
        $this->issueType=$req['issueType'];
        $this->issueSubType=$req['issueSubType'];
        $this->mesasage=$req['mesasage'];

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
            'issueType' => $this->issueType,
            'issueSubType' => $this->issueSubType,
            'mesasage' => $this->mesasage,
        ]; 
        Mail::send('sendApiClientIssueEmail', $data, function ($messageNew) {              
            $messageNew->from(config('mail.contact.address'))           
            ->to($this->to)
            ->subject($this->subject);
        });


    }
}
