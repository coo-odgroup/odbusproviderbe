<?php

namespace App\Services;


use App\Repositories\FailledTransactionReportRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class FailledTransactionReportService
{
    protected $failledtransactionreportrepository;

    
   
    public function __construct(FailledTransactionReportRepository $failledtransactionreportrepository)
    {
        $this->failledtransactionreportrepository = $failledtransactionreportrepository;
    }  
    
    public function getData($request)
    {
        return $this->failledtransactionreportrepository->getData($request);
    }

}