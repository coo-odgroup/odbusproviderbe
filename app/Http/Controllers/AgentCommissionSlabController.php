<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\AgentCommissionSlabRepository;
use Illuminate\Support\Facades\Log;

class AgentCommissionSlabController extends Controller
{
    use ApiResponser;

    protected $agentCommissionSlabRepository;
    
    public function __construct(AgentCommissionSlabRepository $agentCommissionSlabRepository)
    {
        $this->agentCommissionSlabRepository = $agentCommissionSlabRepository;
    }
  

    public function agentcommissionslab(){
        try {
            $wallet = $this->agentCommissionSlabRepository->agentcommissionslab();
            return $this->successResponse($wallet,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function customercommissionslab(){
        try {
            $wallet = $this->agentCommissionSlabRepository->customercommissionslab();
            return $this->successResponse($wallet,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }



}
