<?php

namespace App\Services;


use App\Repositories\CompleteReportRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class CompleteReportService
{
    protected $completereportRepository;

    
   
    public function __construct(CompleteReportRepository $completereportRepository)
    {
        $this->completereportRepository = $completereportRepository;
    }

   
    
    
    public function getAll()
    {
        return $this->completereportRepository->getAll();
    }
    
    public function getData($request)
    {
        return $this->completereportRepository->getData($request);
    }

}