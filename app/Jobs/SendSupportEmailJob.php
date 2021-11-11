<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;

class SendSupportEmailJob implements ShouldQueue
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
    protected $amount;
    protected $via;
    protected $userName;
    protected $tran_id;
    public function __construct($to, $subject, $req)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->userName=$req['userName'];
        $this->amount=$req['amount'];
        $this->via=$req['via'];
        $this->tran_id=$req['tran_id'];
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
            'amount' => $this->amount,
            'via' => $this->via,
            'tran_id' => $this->tran_id
        ];
        Mail::send('agentWalletRequestAdmin', $data, function ($messageNew) {
            $messageNew->from('support@odbus.in', 'ODBUS')
            ->to($this->to)
            ->subject($this->subject);
        });
    }
}
