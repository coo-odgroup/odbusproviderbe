<?php
namespace App\Repositories;
use App\Models\AgentWallet;
use App\Models\Bus;
use Illuminate\Support\Facades\Log;

class AgentWalletRepository
{
  
    protected $agentWallet;
    
    public function __construct(agentWallet $agentWallet)
    {
        $this->agentWallet = $agentWallet;
    }
      
    public function getModel($data, agentWallet $agentWallet)
    {
        $otp =random_int(100000, 999999);
        // $balance= $this->agentWallet->where('user_id',$data['user_id'])->orderBy('id','DESC')->limit(1)->get();
        $agentWallet->transaction_id = $data['transaction_id'];
        $agentWallet->reference_id = $data['reference_id'];
        $agentWallet->amount = $data['amount'];
        $agentWallet->payment_via = $data['payment_via'];
        $agentWallet->remarks = $data['remarks'];
        $agentWallet->user_id = $data['user_id'];
        $agentWallet->transaction_type = $data['transaction_type'];       
        $agentWallet->created_by = "Admin";
        $agentWallet->otp = $otp ;
        $agentWallet->status = 0;

        return $agentWallet;
    }

    public function save($data)
    {
        $agentWallet = new $this->agentWallet;
        $agentWallet=$this->getModel($data,$agentWallet);
        $agentWallet->save();       
        return $agentWallet;
    }
    public function balance($id)
    {
       return $agentWallet = $this->agentWallet->where('user_id',$id)->orderBy('id','DESC')->offset(1)->limit(1)->get();
         
    }

    public function getWalletRecord(){
        return $this->agentWallet->whereNotIn('status', [2]);
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

    public function Otp($id,$data)
    {
      return $agentWallet = $this->agentWallet->where('id',$id)->where('otp',$data['otp'])->get();

    }


    public function update_Status($id)
    {
         $agentWallet = $this->agentWallet->find($id);
         $agentWallet->status = 1;
         $agentWallet->otp = "";
         $agentWallet->update();
         
      return $agentWallet;

    }

    public function update_balance($id,$balance)
    {
         $agentWallet = $this->agentWallet->find($id);
         $agentWallet->balance = $balance;
         $agentWallet->update();
         
      return $agentWallet;

    }





    
}