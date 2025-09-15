<?php

namespace App\Services;


use App\Repositories\AgentCompleteReportRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class AgentCompleteReportService
{
    protected $agentcompletereportRepository;

    public function __construct(AgentCompleteReportRepository $agentcompletereportRepository)
    {
        $this->agentcompletereportRepository = $agentcompletereportRepository;
    }
    
    // public function getalldata($request)
    // {
    //     return $this->agentcompletereportRepository->getData($request);
    // }

}