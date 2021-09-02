<?php

namespace App\Services;


use App\Repositories\ExtraSeatOpenReportRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class ExtraSeatOpenReportService
{
    protected $extraseatopenreportRepository;

    
   
    public function __construct(ExtraSeatOpenReportRepository $extraseatopenreportRepository)
    {
        $this->extraseatopenreportRepository = $extraseatopenreportRepository;
    }  
    
    
    public function getAll()
    {
        return $this->extraseatopenreportRepository->getAll();
    }

}