<?php

namespace App\Repositories;

use App\Models\AgentWallet;
use App\Models\AgentCommission;
use App\Models\AgentFee;
use Illuminate\Support\Facades\Log;

class AgentCommissionSlabRepository
{
<<<<<<< HEAD
  
=======
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
    protected $agentFee;
    protected $agentCommission;

    public function __construct(AgentFee $agentFee, AgentCommission $agentCommission)
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
