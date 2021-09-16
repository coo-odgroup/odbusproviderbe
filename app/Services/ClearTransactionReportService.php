<?php

namespace App\Services;


use App\Repositories\ClearTransactionReportRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class ClearTransactionReportService
{
    protected $cleartransactionreportRepository;

    
   
    public function __construct(ClearTransactionReportRepository $cleartransactionreportRepository)
    {
        $this->cleartransactionreportRepository = $cleartransactionreportRepository;
    }  
    
    
    public function getAll()
    {
        return $this->cleartransactionreportRepository->getAll();
    }

}