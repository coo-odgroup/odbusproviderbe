<?php

namespace App\Repositories;


use App\Models\OwnerPayment;


// use App\Models\TicketPrice;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class OwnerPaymentReportRepository
{
    
    public function __construct(OwnerPayment $ownerPayment)
    {
        $this->ownerPayment = $ownerPayment;
    }   

  
    public function getAll()
    {
    	return $this->ownerPayment->with('busOperator')->get();   

    }
}