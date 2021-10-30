<?php

namespace App\Services;


use App\Repositories\SeatOpenReportRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class SeatOpenReportService
{
    protected $seatopenreportRepository;   
    
    public function __construct(SeatOpenReportRepository $seatopenreportRepository)
    {
        $this->seatopenreportRepository = $seatopenreportRepository;
    }
    public function getData($request)
    {
        return $this->seatopenreportRepository->getData($request);
    }

}