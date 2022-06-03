<?php

namespace App\Services;


use App\Repositories\ApiUserCancelTicketReportRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class ApiUserCancelTicketReportService
{
    protected $apiusercancelticketreportRepository;    
   
    public function __construct(ApiUserCancelTicketReportRepository $apiusercancelticketreportRepository)
    {
        $this->apiusercancelticketreportRepository = $apiusercancelticketreportRepository;
    }
    public function getData($request)
    {
        return $this->apiusercancelticketreportRepository->getData($request);
    }

}