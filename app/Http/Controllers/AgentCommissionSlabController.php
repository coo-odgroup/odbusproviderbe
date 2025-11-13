<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Services\AgentCommissionSlabService;
use App\Traits\ApiResponser;
use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\AgentCommissionSlabRepository;
use Illuminate\Support\Facades\Log;

class AgentCommissionSlabController extends Controller
{
    use ApiResponser;
    protected $agentCommissionSlabService;
<<<<<<< HEAD
    protected $agentCommissionSlabRepository;
    
    public function __construct(AgentCommissionSlabService $agentCommissionSlabService,AgentCommissionSlabRepository $agentCommissionSlabRepository)
    {
        $this->agentCommissionSlabService = $agentCommissionSlabService;
        $this->agentCommissionSlabRepository = $agentCommissionSlabRepository;
    }
  

    public function agentcommissionslab(){
        $wallet = $this->agentCommissionSlabRepository->agentcommissionslab();
        return $this->successResponse($wallet,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function customercommissionslab(){
        $wallet = $this->agentCommissionSlabRepository->customercommissionslab();
        return $this->successResponse($wallet,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
=======

    public function __construct(AgentCommissionSlabService $agentCommissionSlabService)
    {
        $this->agentCommissionSlabService = $agentCommissionSlabService;
    }


    public function agentcommissionslab()
    {
        $wallet = $this->agentCommissionSlabService->agentcommissionslab();
        return $this->successResponse($wallet, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function customercommissionslab()
    {
        $wallet = $this->agentCommissionSlabService->customercommissionslab();
        return $this->successResponse($wallet, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
    }



}
