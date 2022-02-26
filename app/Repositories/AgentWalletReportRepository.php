<?php
namespace App\Repositories;
use App\Models\AgentWallet;
use App\Models\Booking;



use Illuminate\Support\Facades\Log;

class AgentWalletReportRepository
{
  
    protected $agentWallet; 
    
    public function __construct(AgentWallet $agentWallet,Booking $booking)
    {
        $this->agentWallet = $agentWallet;
        $this->booking = $booking;
    }
      
    public function getWalletRecord($user_id){
        return $this->agentWallet->where('user_id',$user_id)->orderBy('id','DESC')->whereNotIn('status', [2]);
    }

    public function Pagination($data,$paginate){
        $data=  $data->paginate($paginate);
         if($data){
            foreach($data as $key=>$v){
                if($v->booking_id!=null){
                    $v['pnrDetails']=$this->booking->where('id', $v->booking_id)->get();
                }
            }
        }
        return $data;
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