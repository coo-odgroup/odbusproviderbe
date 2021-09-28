<?php

namespace App\Services;


use App\Repositories\CancelTicketReportRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class CancelTicketReportService
{
    protected $cancelticketreportRepository;

    
   
    public function __construct(CancelTicketReportRepository $cancelticketreportRepository)
    {
        $this->cancelticketreportRepository = $cancelticketreportRepository;
    }

   
    
    
    public function getAll()
    {
        return $this->cancelticketreportRepository->getAll();
    }
    public function getData($request)
    {
        return $this->cancelticketreportRepository->getData($request);
    }

}