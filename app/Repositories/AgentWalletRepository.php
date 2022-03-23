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
        $agentWallet->created_by = $data['user_name'];
        $agentWallet->otp = $otp ;
        $agentWallet->status = 0;

        return $agentWallet;
    }

    public function save($data)
    {     
        $user = $this->user->find($data['user_id']);
        $balance = $this->agentWallet->where('user_id',$data['user_id'])->where('status',1)->orderBy('id','DESC')->limit(1)->get();
        $agentWallet = new $this->agentWallet;
        $agentWallet=$this->getModel($data,$agentWallet);
        if(count($balance)==0)
        {
            $agentWallet->balance = 0;
        }else
        {
             $agentWallet->balance =  $balance[0]->balance; 
        } 
        $agentWallet->save(); 

        $notification = new $this->notification; 
         $notification->notification_heading = "Wallet Recharge of Rs.".$data['amount']." Request";
         $notification->notification_details = " Dear ".$user->name.", Your Request of Rs.".$data['amount'].
         " through ".$data['payment_via']." with transaction.id-".$data['transaction_id']." has been received.You will be notified once it will approved.";
         $notification->created_by = $data['user_name'];
         $notification->save();

         $userNotification[0]=new UserNotification();
         $userNotification[0]['user_id'] = $data['user_id'];
         $userNotification[0]['created_by']= $data['user_name'] ;

         $notification->userNotification()->saveMany($userNotification);

           $to_user = $user->email ;
           $subject = "Wallet recharge request - ".$user->name;
           $agentData= [
                    'userName'=>$user->name,
                    'amount'=>$data['amount'],
                    'via'=>$data['payment_via'],
                    'tran_id'=>$data['transaction_id']
                   ] ;

           SendWalletEmailJob::dispatch($to_user, $subject, $agentData);

           $to_support = "support@odbus.in";
           // $to_support = "bishal.seofied@gmail.com";
           $subject = "Wallet recharge request From ".$user->name;
           $supportData= [
                    'userName'=>$user->name,
                    'amount'=>$data['amount'],
                    'via'=>$data['payment_via'],
                    'tran_id'=>$data['transaction_id'],
                    'otp' => $agentWallet->otp,
                   ] ;
            SendSuperAdminEmailJob::dispatch($to_support, $subject, $supportData);

           $to_superadmin = "coo@odgroup.in";
           // $to_superadmin ="bishal.seofied@gmail.com";
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
        $agentWallet = $this->agentWallet->where('user_id',$id)->where('status',1)->orderBy('id','DESC')->limit(1)->get(); 
        return $agentWallet;  
    }

  
    public function getAllWalletRecord(){
        return $this->agentWallet->with('user')->orderBy('id','DESC')->where('payment_via','!=',"");
    }
    
    public function agentWalletBalancedetails($request){
        $paginate = $request->rows_number;
        $name = $request->name;
        $data= $this->user->with(['agentWallet' => function ($a)
                                {$a->where('status',1)
                                    ->orderBy('id','DESC');
                                    }])
                                    ->where('role_id',3)
                                    ->where('status',1);

        if($paginate=='all')    
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) {
            $paginate = 10 ;
        }
          if(!empty($name))
        {
           $data=$data->where('name', 'like', '%' .$name . '%')
                    ->orWhere('email','like', '%' .$name . '%')
                    ->orWhere('phone','like', '%' .$name . '%');
        }

        $data=$data->paginate($paginate); 
      
        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           ); 
           return $response;    

    } 

    public function agentAllTransaction($request){
        // Log::info($request);
        // exit;
        $start_date="";
        $end_date="";
        $paginate = $request->rows_number;
        $name = $request->name;
        $user_id = $request->user_id;
        $rangeFromDate  =  $request->rangeFromDate;
        $rangeToDate  =  $request->rangeToDate;
        if(!empty($rangeFromDate))
        {
            if(strlen($rangeFromDate['month'])==1)
            {
                $rangeFromDate['month']="0".$rangeFromDate['month'];
            }
            if(strlen($rangeFromDate['day'])==1)
            {
                $rangeFromDate['day']="0".$rangeFromDate['day'];
            }

            $start_date = $rangeFromDate['year'].'-'.$rangeFromDate['month'].'-'.$rangeFromDate['day'] ;     
        }

        if(!empty($rangeToDate))
        {
            if(strlen($rangeToDate['month'])==1)
            {
                $rangeToDate['month']="0".$rangeToDate['month'];
            }
            if(strlen($rangeToDate['day'])==1)
            {
                $rangeToDate['day']="0".$rangeToDate['day'];
            }

            $end_date = $rangeToDate['year'].'-'.$rangeToDate['month'].'-'.$rangeToDate['day'] ;     
        }

        

        $data= $this->agentWallet->with('user')->where('status', 1)->orderBy('id','DESC');

        if($paginate=='all')    
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) {
            $paginate = 10 ;
        }
        if(!empty($name))
        {
           $data=$data->where('transaction_id',$name)
                    ->orwhereHas('user', function ($query) use ($name) 
                        {$query->where('name', 'like', '%' .$name . '%')
                        ->orWhere('email','like', '%' .$name . '%')
                        ->orWhere('phone','like', '%' .$name . '%');
                    });
        }
        if($start_date != null && $end_date != null)
        {
            $data =$data->whereBetween('created_at', [$start_date, $end_date]);
                       
        }
        if($user_id!= null)
        {
            $data =$data->where('user_id', $user_id);
                       
        }

        $data=$data->paginate($paginate); 
        
        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           ); 
           return $response;    

    }

  
    public function getWalletRecord($user_id){
        return $this->agentWallet->where('user_id',$user_id)->orderBy('id','DESC')->whereNotIn('status', [2])->where('payment_via','!=',"");
    }

    public function Pagination($data,$paginate){
       return $data->paginate($paginate);
    }

    // public function Filter($data,$name){
    //    return  $data->where('transaction_id', 'like', '%' .$name . '%')
    //                      ->orWhere('reference_id', 'like', '%' .$name . '%')
    //                      ->orWhere('amount', 'like', '%' .$name . '%')
    //                      ->orWhere('remarks', 'like', '%' .$name . '%')
    //                      ->orWhere('payment_via', 'like', '%' .$name . '%')
    //                     ;   
    // }

    public function Filter($data,$name){
       return  $data->where('transaction_id', 'like', '%' .$name . '%')
                         // ->orWhere('reference_id', 'like', '%' .$name . '%')
                         // ->orWhere('amount', 'like', '%' .$name . '%')
                         // ->orWhere('remarks', 'like', '%' .$name . '%')
                        
                        ;   
    }
    public function payViaFilter($data,$name){
       return  $data->Where('payment_via', 'like', '%' .$name . '%');   
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

    public function declineWalletReq($data,$id)
    {
        $agentWallet = $this->agentWallet->find($id);       
        $agentWallet->status = 3;
        $agentWallet->reject_reason = $data->reject_reason;
        $agentWallet->update();
        return "Success";
    }

    public function update_balance($id,$balance,$otpdata,$data)
    {
         $agentWallet = $this->agentWallet->find($id);
         $user = $this->user->find($agentWallet->user_id);
         $lastrow = $this->agentWallet->where('user_id',$agentWallet->user_id)
                                      ->where('status',1)
                                      ->orderBy('id','DESC')
                                      ->limit(1)
                                      ->get();
         $agentWallet->balance = $balance;
         $agentWallet->update();
         if($lastrow[0]->id != $agentWallet->id);
         {
                $agentWalletLastRow = $this->agentWallet->find($lastrow[0]->id);
                $agentWalletLastRow->balance = $balance;
                $agentWalletLastRow->update();
         }



         $notification = new $this->notification; 
         $notification->notification_heading = "Wallet Recharge of Rs.".$otpdata[0]->amount." Approved";
         $notification->notification_details = " Dear ".$user->name.", Your Request of Rs.".$otpdata[0]->amount.
         " through ".$otpdata[0]->payment_via." with transaction.id-".$otpdata[0]->transaction_id." has been approved.Your Current balance is ".$balance ;
         $notification->created_by = $data->user_name ;
         $notification->save();

         $userNotification[0]=new UserNotification();
         $userNotification[0]['user_id'] = $otpdata[0]->user_id;
         $userNotification[0]['created_by']= $data->user_name ; 

         $notification->userNotification()->saveMany($userNotification);

           $to_user = $user->email;
           $subject = "Wallet recharge request Approved";
           $superAdminData= [
                    'userName'=>$user->name,
                    'amount'=>$otpdata[0]->amount,
                    'via'=>$otpdata[0]->payment_via,
                    'tran_id'=>$otpdata[0]->transaction_id,
                    'balance'=>$balance
                   ] ;
           SendWalletApproveEmailJob::dispatch($to_user, $subject, $superAdminData);
         
      return $agentWallet;

    }
    
}