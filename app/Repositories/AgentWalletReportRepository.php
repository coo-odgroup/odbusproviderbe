<?php
namespace App\Repositories;
use App\Models\AgentWallet;



use Illuminate\Support\Facades\Log;

class AgentWalletReportRepository
{
  
    protected $agentWallet; 
    
    public function __construct(AgentWallet $agentWallet)
    {
        $this->agentWallet = $agentWallet;
    }
      
    public function getWalletRecord($user_id){
        return $this->agentWallet->where('user_id',$user_id)->orderBy('id','DESC')->whereNotIn('status', [2]);
    }

    public function Pagination($data,$paginate){
       return $data->paginate($paginate);
    }

    public function Filter($data,$name){
       return  $data->where('transaction_id', 'like', '%' .$name . '%')
                         ->orWhere('reference_id', 'like', '%' .$name . '%')
                         ->orWhere('amount', 'like', '%' .$name . '%')
                         ->orWhere('remarks', 'like', '%' .$name . '%')
                         ->orWhere('payment_via', 'like', '%' .$name . '%')
                        ;   
    } 

    public function tranType($data,$tran_type){
       return  $data->where('transaction_type', $tran_type );   
    }

   
    
}