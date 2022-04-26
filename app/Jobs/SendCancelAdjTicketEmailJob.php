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
    protected $email;
    protected $pnr;
    protected $journeydate;
    protected $contactNo;
    protected $route;
    protected $deductionPercentage;
    protected $refundAmount;
    protected $seat_no;
    protected $cancellationDateTime;
    protected $totalfare;    
    protected $subject;

    public function __construct($to, $subject, $request)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->pnr = $request['pnr'];
        $this->journeydate = $request['journeydate'];
        $this->contactNo = $request['contactNo'];
        $this->route = $request['route'];
        $this->deductionPercentage = $request['deductionPercentage'];
        $this->refundAmount = number_format($request['refundAmount'],2);
        $this->seat_no = $request['seat_no'];
        $this->totalfare = $request['totalfare'];
        $this->cancellationDateTime = $request['cancellationDateTime'];
        
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
            'contactNo'=> $this->contactNo,
            'journeydate' => $this->journeydate ,
            'route'=> $this->route,
            'seat_no' => $this->seat_no,
            'totalfare'=> $this->totalfare,
            'deductionPercentage'=> $this->deductionPercentage,
            'refundAmount'=> $this->refundAmount,
            'cancellationDateTime'=> $this->cancellationDateTime,
            
        ];

        //Log::info($data);

        Mail::send('adjAdminTiceketCancel', $data, function ($messageNew) {
            $messageNew->from(config('mail.contact.address'))
            ->to($this->to)
            ->subject($this->subject);
        });
    }
}
