<?php

namespace App\Services;


use App\Repositories\AgentCommissionReportRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class AgentCommissionReportService
{
    protected $agentcommissionreportRepository;

    public function __construct(AgentCommissionReportRepository $agentcommissionreportRepository)
    {
        $this->agentcommissionreportRepository = $agentcommissionreportRepository;
    }
    
    public function getalldata($request)
    {
        return $this->agentcommissionreportRepository->getData($request);
    }

}