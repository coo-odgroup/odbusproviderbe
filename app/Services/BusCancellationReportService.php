<?php

namespace App\Services;


use App\Repositories\BusCancellationReportRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class BusCancellationReportService
{
    protected $buscancellationreportRepository;

    
   
    public function __construct(BusCancellationReportRepository $buscancellationreportRepository)
    {
        $this->buscancellationreportRepository = $buscancellationreportRepository;
    }  
    
    // public function getData($request)
    // {
    //     return $this->buscancellationreportRepository->getData($request);
    // }

}