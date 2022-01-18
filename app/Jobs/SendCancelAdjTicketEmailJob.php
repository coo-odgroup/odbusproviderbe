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


class SendCancelAdjTicketEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $to;
    protected $subject;
    protected $pnr;
    // protected $refund_amount;
    // protected $deduction_percent;

    public function __construct($to, $subject, $req)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->pnr=$req['pnr'];
        // $this->refund_amount=$req['refund_amount'];
        // $this->deduction_percent=$req['deduction_percent'];
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data=[
            'pnr' => $this->pnr,
            // 'refund_amount' => $this->refund_amount,
            // 'deduction_percent' => $this->deduction_percent
        ];
        Mail::send('adjAdminTiceketCancel', $data, function ($messageNew) {
            $messageNew->from(config('mail.contact.address'))
            ->to($this->to)
            ->subject($this->subject);
        });
    }
}
