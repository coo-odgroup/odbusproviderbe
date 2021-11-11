<?php
namespace App\Repositories;
use App\Models\AgentWallet;
use App\Models\Notification;
use App\Models\UserNotification;
use App\Models\User;

use App\Jobs\SendSuperAdminEmailJob;
use App\Jobs\SendSupportEmailJob;
use App\Jobs\SendWalletEmailJob;
use App\Jobs\SendWalletApproveEmailJob;



use Illuminate\Support\Facades\Log;

class AgentWalletRepository
{
  
    protected $agentWallet; 
    protected $notification;
    protected $user;
    
    public function __construct(AgentWallet $agentWallet,Notification $notification,User $user)
    {
        $this->agentWallet = $agentWallet;
        $this->notification = $notification;
        $this->user = $user;
    }
      
    public function getModel($data, AgentWallet $agentWallet)
    {
        $otp =random_int(100000, 999999);
        $agentWallet->transaction_id = $data['transaction_id'];
        $agentWallet->reference_id = $data['reference_id'];
        $agentWallet->amount = $data['amount'];
        $agentWallet->payment_via = $data['payment_via'];
        $agentWallet->remarks = $data['remarks'];
        $agentWallet->user_id = $data['user_id'];
        $agentWallet->transaction_type = $data['transaction_type'];       
        $agentWallet->created_by = "Agent";
        $agentWallet->otp = $otp ;
        $agentWallet->status = 0;

        return $agentWallet;
    }

    public function save($data)
    {  
        $user = $this->user->find($data['user_id']);
      
        $agentWallet = new $this->agentWallet;
        $agentWallet=$this->getModel($data,$agentWallet);
        $agentWallet->save(); 

        $notification = new $this->notification; 
         $notification->notification_heading = "Wallet Recharge of Rs.".$data['amount']." Request";
         $notification->notification_details = " Dear Agent,Your Request of Rs.".$data['amount'].
         " through ".$data['payment_via']." with transaction.id-".$data['transaction_id']." has been received.You will be notified once it will approved.";
         $notification->created_by = "Agent";
         $notification->save();

         $userNotification[0]=new UserNotification();
         $userNotification[0]['user_id'] = $data['user_id'];
         $userNotification[0]['created_by']= "Agent";

         $notification->userNotification()->saveMany($userNotification);

           $to_user = $user->email ;
           $subject = "Wallet recharge request";
           $agentData= [
                    'userName'=>$user->name,
                    'amount'=>$data['amount'],
                    'via'=>$data['payment_via'],
                    'tran_id'=>$data['transaction_id']
                   ] ;

           SendWalletEmailJob::dispatch($to_user, $subject, $agentData);

           $to_support = "bishalnaik23@gmail.com";
           $subject = "Wallet recharge request From Agent";
           $supportData= [
                    'userName'=>$user->name,
                    'amount'=>$data['amount'],
                    'via'=>$data['payment_via'],
                    'tran_id'=>$data['transaction_id']
                   ] ;
           SendSupportEmailJob::dispatch($to_support, $subject, $supportData);

           $to_superadmin = "chandra@odgroup.in";
           $subject = "Wallet recharge request From Agent";
           $superAdminData= [
                    'userName'=>$user->name,
                    'amount'=>$data['amount'],
                    'via'=>$data['payment_via'],
                    'tran_id'=>$data['transaction_id'],
                    'otp' => $agentWallet->otp,
                   ] ;
           SendSuperAdminEmailJob::dispatch($to_superadmin, $subject, $superAdminData);


        return $agentWallet;
    }


    public function balance($id)
    {
        $agentWallet = $this->agentWallet->where('user_id',$id)->where('status',1)->orderBy('id','DESC')->offset(1)->limit(1)->get(); 
        if(sizeof($agentWallet) == 0)
        {
             $agentWallet = $this->agentWallet->where('user_id',$id)->where('status',1)->orderBy('id','DESC')->limit(1)->get(); 
        } 
        return $agentWallet;  
    }

    public function getWalletRecord(){
        return $this->agentWallet->where('user_id', 2)->whereNotIn('status', [2]);
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
      return $agentWallet = $this->agentWallet->where('id',$id)->where('otp',$data->otp)->get();

    }


    public function update_Status($id)
    {
         $agentWallet = $this->agentWallet->find($id);
         $agentWallet->status = 1;
         $agentWallet->otp = "";
         $agentWallet->update();
         
      return $agentWallet;

    }

    public function update_balance($id,$balance,$data)
    {
        //Log:info($data[0]);
         $agentWallet = $this->agentWallet->find($id);
         $user = $this->user->find($agentWallet->user_id);
         $agentWallet->balance = $balance;
         $agentWallet->update();



         $notification = new $this->notification; 
         $notification->notification_heading = "Wallet Recharge of Rs.".$data[0]->amount." Approved";
         $notification->notification_details = " Dear Agent,Your Request of Rs.".$data[0]->amount.
         " through ".$data[0]->payment_via." with transaction.id-".$data[0]->transaction_id." has been approved.Your Current balance is".$balance ;
         $notification->created_by = "Agent";
         $notification->save();

         $userNotification[0]=new UserNotification();
         $userNotification[0]['user_id'] = $data[0]->user_id;
         $userNotification[0]['created_by']= "Agent";

         $notification->userNotification()->saveMany($userNotification);

           $to_user = $user->email;
           $subject = "Wallet recharge request Approved";
           $superAdminData= [
                    'userName'=>$user->name,
                    'amount'=>$data[0]->amount,
                    'via'=>$data[0]->payment_via,
                    'tran_id'=>$data[0]->transaction_id,
                    'balance'=>$balance
                   ] ;
           SendWalletApproveEmailJob::dispatch($to_user, $subject, $superAdminData);
         
      return $agentWallet;

    }
    
}