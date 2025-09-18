<?php

namespace App\Services;


use App\Repositories\ContactReportRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class ContactReportService
{
    protected $contactreportRepository;   
   
    public function __construct(ContactReportRepository $contactreportRepository)
    {
        $this->contactreportRepository = $contactreportRepository;
    }  
    
    // public function getData($request)
    // {
    //     return $this->contactreportRepository->getData($request);
    // }
    // public function deleteData($id)
    // {
    //     return $this->contactreportRepository->deleteData($id);
    // }

}