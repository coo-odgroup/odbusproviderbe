<?php

namespace App\Services;


use App\Repositories\SeatBlockReportRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class SeatBlockReportService
{
    protected $seatblockreportRepository;

    public function __construct(SeatBlockReportRepository $seatblockreportRepository)
    {
        $this->seatblockreportRepository = $seatblockreportRepository;
    }

    // public function getAll()
    // {
    //     return $this->seatblockreportRepository->getAll();
    // }
    // public function getData($request)
    // {
    //     return $this->seatblockreportRepository->getData($request);
    // }

}