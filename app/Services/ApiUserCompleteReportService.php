<?php

namespace App\Services;


use App\Repositories\ApiUserCompleteReportRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class ApiUserCompleteReportService
{
    protected $apiusercompletereportRepository;

    public function __construct(ApiUserCompleteReportRepository $apiusercompletereportRepository)
    {
        $this->apiusercompletereportRepository = $apiusercompletereportRepository;
    }
    
    public function getData($request)
    {
        return $this->apiusercompletereportRepository->getData($request);
    }

}