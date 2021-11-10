<?php
namespace App\Repositories;
use App\Models\AgentWallet;
use App\Models\Notification;
use App\Models\UserNotification;


use Illuminate\Support\Facades\Log;

class AgentCommissionSlabRepository
{
  
    protected $agentWallet; 
    protected $notification;
    
    public function __construct(AgentWallet $agentWallet,Notification $notification)
    {
        $this->agentWallet = $agentWallet;
        $this->notification = $notification;
    }
      

    public function agentcommissionslab()
    {
          return "working agent commission";      
    }


    public function customercommissionslab()
    {
           return "working customer commission";   
    }
   
    
}