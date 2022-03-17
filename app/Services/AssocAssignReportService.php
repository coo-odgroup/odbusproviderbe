<?php

namespace App\Services;
use App\Repositories\AssocAssignReportRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class AssocAssignReportService
{
    protected $AssocAssignReportRepository;

    public function __construct(AssocAssignReportRepository $AssocAssignReportRepository)
    {
        $this->AssocAssignReportRepository = $AssocAssignReportRepository;
    }
    
    public function getAssignBusData($request)
    {
        return $this->AssocAssignReportRepository->getAssignBusData($request);
    }
    public function getAssignAgentData($request)
    {
        return $this->AssocAssignReportRepository->getAssignAgentData($request);
    }
    public function getAssignOperatorData($request)
    {
        return $this->AssocAssignReportRepository->getAssignOperatorData($request);
    }

}