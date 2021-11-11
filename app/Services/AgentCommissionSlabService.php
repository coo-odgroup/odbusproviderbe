<?php
namespace App\Services;

use App\Models\BusOwnerFare;
use App\Repositories\AgentCommissionSlabRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class AgentCommissionSlabService
{
    
    protected $agentCommissionSlabRepository;

    
    public function __construct(AgentCommissionSlabRepository $agentCommissionSlabRepository)
    {
        $this->agentCommissionSlabRepository = $agentCommissionSlabRepository;
    } 

    public function agentcommissionslab()
    {
          return $this->agentCommissionSlabRepository->agentcommissionslab();      
    }
    public function customercommissionslab()
    {
          return $this->agentCommissionSlabRepository->customercommissionslab();   
    }
        
   

}

 