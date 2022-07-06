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


class SendOwnerCancelBusEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $to;
    protected $subject;
    protected $month;
    protected $year;
    protected $cancelled_by;
    protected $reason;
    protected $other_reson;
    protected $busName;
    protected $dates;
    public function __construct($to, $subject, $req)
    {
        
        $this->to = $to;
        $this->subject = $subject;
        $this->month=$req['month'];
        $this->year=$req['year'];
        $this->cancelled_by=$req['cancelled_by'];
        $this->reason=$req['reason'];
        $this->other_reson=$req['other_reson'];
        $this->busName=$req['busName'];
        $this->dates=$req['dates'];

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data=[
            'month'=>$this->month,
            'year' => $this->year,
            'cancelled_by' => $this->cancelled_by,
            'reason' => $this->reason,
            'other_reson' => $this->other_reson,
            'busName' => $this->busName,
            'dates' => $this->dates,
        ]; 

            // Log::info($data);exit;
        Mail::send('sendOwnerCancelBusEmailJob', $data, function ($messageNew) {              
            $messageNew->from(config('mail.contact.address'))           
            ->to($this->to)
            ->subject($this->subject);
        });


    }
}
