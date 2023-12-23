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
    
    public function getData($request)
    {
        return $this->completereportRepository->getData($request);
    }

    //Created By Chakra 26-04-2022 11:56 AM
    public function getPendingPNR($request)
    {
        return $this->completereportRepository->getPendingPNR($request);
    }

    public function getLessBookingUrls()
    {
        return $this->completereportRepository->getLessBookingUrls();
    }
    

}