<?php

namespace App\Services;


use App\Repositories\OwnerPaymentReportRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class OwnerPaymentReportService
{
    protected $ownerpaymentreportRepository;

    
   
    public function __construct(OwnerPaymentReportRepository $ownerpaymentreportRepository)
    {
        $this->ownerpaymentreportRepository = $ownerpaymentreportRepository;
    }  
    
    
    public function getAll()
    {
        return $this->ownerpaymentreportRepository->getAll();
    }
    public function getData($request)
    {
        return $this->ownerpaymentreportRepository->getData($request);
    }

}
