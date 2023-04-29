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


class SendEmailToSupportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $to;    
    protected $pnr;
    protected $user;
    protected $refundAmount;   
    protected $subject;
    protected $reason;

    public function __construct($to, $subject, $request)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->pnr = $request['pnr'];
        $this->user = $request['user'];
        $this->reason = $request['reason'];
        $this->refundAmount = number_format($request['refundAmount'],2);
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = [
            'email' => $this->to,
            'pnr' => $this->pnr,
            'user' => $this->user,
            'refundAmount'=> $this->refundAmount,        
            'reason'=> $this->reason,        
        ];

        Mail::send('apicancelticketbyadmintosupport', $data, function ($messageNew) {
            $messageNew->from(config('mail.contact.address'))
            ->to($this->to)
            ->subject($this->subject);
        });
    }
}
