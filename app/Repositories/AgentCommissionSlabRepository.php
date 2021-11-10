<?php
namespace App\Repositories;
use App\Models\AgentWallet;
use App\Models\AgentCommission;
use App\Models\AgentFee;


use Illuminate\Support\Facades\Log;

class AgentCommissionSlabRepository
{
  
    protected $agentFee; 
    protected $agentCommission;
    
    public function __construct(AgentFee $agentFee,AgentCommission $agentCommission)
    {
        $this->agentFee = $agentFee;
        $this->agentCommission = $agentCommission;
    }
      

    public function agentcommissionslab()
    {
          return $this->agentCommission->get();      
    }


    public function customercommissionslab()
    {
            return $this->agentFee->get();  
    }
   
    
}