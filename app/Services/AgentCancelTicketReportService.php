<?php

namespace App\Services;


use App\Repositories\AgentCancelTicketReportRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class AgentCancelTicketReportService
{
    protected $agentCancelTicketReportRepository;

    public function __construct(AgentCancelTicketReportRepository $agentCancelTicketReportRepository)
    {
        $this->agentCancelTicketReportRepository = $agentCancelTicketReportRepository;
    }
    
    public function getalldata($request)
    {
        return $this->agentCancelTicketReportRepository->getData($request);
    }

}