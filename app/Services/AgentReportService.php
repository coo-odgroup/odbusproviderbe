<?php

namespace App\Services;
use App\Repositories\AgentReportRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class AgentReportService
{
    protected $agentreportRepository;

    public function __construct(AgentReportRepository $agentreportRepository)
    {
        $this->agentreportRepository = $agentreportRepository;
    }
    
    public function getData($request)
    {
        return $this->agentreportRepository->getData($request);
    }
 	public function agentcancelreport($request)
    {
        return $this->agentreportRepository->agentcancelreport($request);
    }
	 public function agentCommissionreport($request)
    {
        return $this->agentreportRepository->agentCommissionreport($request);
    }

}