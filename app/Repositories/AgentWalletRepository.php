<?php
namespace App\Repositories;
use App\Models\AgentWallet;
use App\Models\AgentWalletRequest;
use App\Models\Notification;
use App\Models\UserNotification;
use App\Models\User;

use App\Jobs\SendSuperAdminEmailJob;
use App\Jobs\SendSupportEmailJob;
use App\Jobs\SendWalletEmailJob;
use App\Jobs\SendWalletApproveEmailJob;
use Illuminate\Support\Facades\Log;
use DB;

class AgentWalletRepository
{
  
    protected $agentWallet; 
    protected $AgentWalletRequest; 
    protected $notification;
    protected $user;
    
    public function __construct(AgentWallet $agentWallet,Notification $notification,User $user,AgentWalletRequest $AgentWalletRequest)
    {
        $this->agentWallet = $agentWallet;
        $this->AgentWalletRequest = $AgentWalletRequest;
        $this->notification = $notification;
        $this->user = $user;
    }
      
    public function getModel($data, AgentWalletRequest $AgentWalletRequest)
    {
        $otp =random_int(100000, 999999);
        $AgentWalletRequest->transaction_id = $data['transaction_id'];
        $AgentWalletRequest->reference_id = $data['reference_id'];
        $AgentWalletRequest->amount = $data['amount'];
        $AgentWalletRequest->payment_via = $data['payment_via'];
        $AgentWalletRequest->remarks = $data['remarks'];
        $AgentWalletRequest->user_id = $data['user_id'];
        $AgentWalletRequest->transaction_type = $data['transaction_type'];       
        $AgentWalletRequest->created_by = $data['user_name'];
        $AgentWalletRequest->otp = $otp ;
        $AgentWalletRequest->status = 0;

        return $AgentWalletRequest;
    }

    public function save($data)
    {    
        // Log::info($data);
        // exit; 
        $user = $this->user->find($data['user_id']);
        // $balance = $this->agentWallet->where('user_id',$data['user_id'])->where('status',1)->orderBy('id','DESC')->limit(1)->get();
        $AgentWalletRequest = new $this->AgentWalletRequest;
        $AgentWalletRequest=$this->getModel($data,$AgentWalletRequest);
        // if(count($balance)==0)
        // {
        //     $agentWallet->balance = 0;
        // }else
        // {
        //      $agentWallet->balance =  $balance[0]->balance; 
        // } 
        $AgentWalletRequest->save(); 

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

           //$to_support = "support@odbus.in";
           // $to_support = "bishal.seofied@gmail.com";
           $subject = "Wallet recharge request From ".$user->name;
           $supportData= [
                    'userName'=>$user->name,
                    'amount'=>$data['amount'],
                    'via'=>$data['payment_via'],
                    'tran_id'=>$data['transaction_id'],
                    'otp' => $AgentWalletRequest->otp,
                   ] ;
            SendSuperAdminEmailJob::dispatch("support@odbus.in", $subject, $supportData);
            SendSuperAdminEmailJob::dispatch("agent@odbus.in", $subject, $supportData);

           $to_superadmin = "coo@odgroup.in";
           // $to_superadmin ="bishal.seofied@gmail.com";
           $subject = "Wallet recharge request From Agent";
           $superAdminData= [
                    'userName'=>$user->name,
                    'amount'=>$data['amount'],
                    'via'=>$data['payment_via'],
                    'tran_id'=>$data['transaction_id'],
                    'otp' => $AgentWalletRequest->otp,
                   ] ;
           SendSuperAdminEmailJob::dispatch($to_superadmin, $subject, $superAdminData);


        return $AgentWalletRequest;
    }



     public function agentTransByAdmin($data)
    {   
    // Log::info($data); 
        $user = $this->user->find($data['user_id']);
        $balance = 0 ;
     
         $agentWallet = $this->agentWallet->where('user_id',$data['user_id'])
                                          ->where('status',1)->orderBy('id','DESC')->limit(1)
                                          ->get();
           // Log::info($agentWallet);

        if(count($agentWallet)>0)
        {
            if($data['transaction_type'] == 'c'){
              $balance = $agentWallet[0]->balance + $data['amount'];
            }
            elseif($data['transaction_type'] == 'd'){
              $balance = $agentWallet[0]->balance - $data['amount'];
            }
            
        }else
        {
            $balance = $data['amount'];
        }

        $agentWallet = new $this->agentWallet;
        $agentWallet->transaction_id =  $data['transaction_id'];
        $agentWallet->reference_id =  $data['reference_id'];
        $agentWallet->amount = $data['amount'];
        $agentWallet->balance = $balance;
        $agentWallet->remarks = $data['remarks'];
        $agentWallet->user_id = $data['user_id'];
        $agentWallet->transaction_type = $data['transaction_type'];       
        $agentWallet->created_by = $data['created_by'];
        $agentWallet->payment_via = '';
        $agentWallet->status = 1;
        // $agentWallet->otp = "";
           // Log::info($agentWallet);exit;

        $agentWallet->save(); 

        return $agentWallet;
    }



    public function balance($id)
    {
        $agentWallet = $this->agentWallet->where('user_id',$id)->where('status',1)->orderBy('id','DESC')->limit(1)->get(); 
        return $agentWallet;  
    }

  
    public function getAllWalletRecord(){
        return $this->AgentWalletRequest->with('user')->orderBy('id','DESC')->where('payment_via','!=',"");
    }
    
    public function agentWalletBalancedetails($request){
       
        $paginate = $request->rows_number;
        $name = $request->name;
        $user_id = $request->user_id;
        $start_date  =  $request->rangeFromDate;
        $end_date  =  $request->rangeToDate;    

        $data = $this->agentWallet->select('user_id', (DB::raw('max(id) as max_id')))
                                  ->where('status', 1)
                                  // ->orderBy('created_at','DESC')
                                  ->groupBy('user_id')                                  
                                  ->with('user');  
                                  
        if(!empty($user_id))
        {
            $data = $data->where('user_id', $user_id );
        }        

        if($start_date != NULL && $end_date != NULL)
        {
            if($start_date == $end_date)
            {
                $data = $data->where('created_at','like','%'.$start_date.'%');               
            }
            else{
                $data = $data->whereBetween('created_at', [$start_date, $end_date]);                                        
            }                       
        }    

        if($paginate=='all')    
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) {
            $paginate = 10 ;
        }        
     
        $data=$data->paginate($paginate); 
        if($data)
        {
            foreach ($data as $v)
            {
                $v['wallet'] = $this->agentWallet->where('id',$v->max_id)->get();
            }
        }
      
        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           ); 
           return $response;    

    } 

    public function agentAllTransaction($request){
        
        // log::info($request);
        $start_date="";
        $end_date="";
        $paginate = $request->rows_number;
        $name = $request->name;
        $user_id = $request->user_id;
        $start_date  =  $request->rangeFromDate;
        $end_date  =  $request->rangeToDate;
        $tranType  =  $request->tranType;

        $data= $this->agentWallet->with('user')->where('status', 1)->orderBy('id','DESC');

        
       //exit;

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
            if($start_date == $end_date){
                $data =$data->where('created_at','like','%'.$start_date.'%')
                        ->orderBy('created_at','DESC');
                       
            }else{
                 $data =$data->whereBetween('created_at', [$start_date, $end_date]);
            }
                       
        }
        if($user_id!= null)
        {
            $data =$data->where('user_id', $user_id);
                       
        }

        if($tranType!= null && $tranType!= 'all_transaction')
        {
          
            if($tranType == 'wallet_recharge')
            {
                $data =$data->where('payment_via','!=','')->where('transaction_type','c' );
            } 
            elseif($tranType == 'commission_received')
            {
                $data =$data->where('type','Commission')->where('transaction_type','c') ;
            } 
            elseif($tranType == 'pnr_booking')
            {
                 $data =$data->where('payment_via','')->where('transaction_type','d') ;
            }
            elseif($tranType == 'cancel_ticket')
            {
                $data =$data->where('type','Refund')->where('transaction_type','c') ;
            }
                       
        }

        $data=$data->paginate($paginate); 
        
        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           ); 
           return $response;    
    }

    public function getWalletRequestRecord($user_id){
        return $this->AgentWalletRequest->where('user_id',$user_id)->orderBy('id','DESC')->whereNotIn('status', [2])->where('payment_via','!=',"");
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

    public function FilterDate($data,$start_date,$end_date){
           
         if($start_date == $end_date)
         { 
           
                $data =$data->where('created_at','like','%'.$start_date.'%')
                        ->orderBy('created_at','DESC');
                       
            }else{
                
                 $data =$data->whereBetween('created_at', [$start_date, $end_date]);
            }
            return  $data;

    }
    public function Filter_user($data,$user_id){
        return  $data->where('user_id', $user_id);   
     }
    public function payViaFilter($data,$name){
       return  $data->Where('payment_via', 'like', '%' .$name . '%');   
    }

    public function Otp($id,$data)
    {
      return $agentWallet = $this->AgentWalletRequest->where('id',$id)->where('otp',$data->otp)->get();

    }


    public function update_Status($id,$ext_data,$data)
    {
        $balance = 0 ;
     
         $agentWallet = $this->agentWallet->where('user_id',$ext_data->user_id)->where('status',1)->orderBy('id','DESC')->limit(1)->get();
         $user = $this->user->find($ext_data->user_id); 

        $AgentWalletRequest = $this->AgentWalletRequest->find($id);

         if(count($agentWallet)>0)
        {
            $balance = $agentWallet[0]->balance + $AgentWalletRequest->amount;
        }else
        {
            $balance = $AgentWalletRequest->amount;
        }

        $agentWallet = new $this->agentWallet;
        $agentWallet->transaction_id = $AgentWalletRequest->transaction_id;
        $agentWallet->reference_id = $AgentWalletRequest->reference_id;
        $agentWallet->amount = $AgentWalletRequest->amount;
        $agentWallet->balance = $balance;
        $agentWallet->payment_via = $AgentWalletRequest->payment_via;
        $agentWallet->remarks = $AgentWalletRequest->remarks;
        $agentWallet->user_id = $AgentWalletRequest->user_id;
        $agentWallet->transaction_type = $AgentWalletRequest->transaction_type;       
        $agentWallet->created_by = $data->user_name;
        $agentWallet->status = 1;
        $agentWallet->otp = "";


        $agentWallet->save();
        $AgentWalletRequest->delete(); 

         $notification = new $this->notification; 
         // $notification->notification_heading = "Wallet Recharge of Rs.".$ext_data->amount." Approved";
         $notification->notification_heading = "New Balance is Rs ".$balance." After recharge sucessfull of Rs ".$ext_data->amount." for Transaction ID ".$ext_data->transaction_id ;

         $notification->notification_details = " Dear ".$user->name.", Your Request of Rs.".$ext_data->amount.
         " through ".$ext_data->payment_via." with transaction.id-".$ext_data->transaction_id." has been approved.Your Current balance is ".$balance ;
         $notification->created_by = $data->user_name ;
         $notification->save();

         $userNotification[0]=new UserNotification();
         $userNotification[0]['user_id'] = $ext_data->user_id;
         $userNotification[0]['created_by']= $data->user_name ; 

         $notification->userNotification()->saveMany($userNotification);

           $to_user = $user->email;
           $subject = "Wallet recharge request Approved";
           $superAdminData= [
                    'userName'=>$user->name,
                    'amount'=>$ext_data->amount,
                    'via'=>$ext_data->payment_via,
                    'tran_id'=>$ext_data->transaction_id,
                    'balance'=>$balance
                   ] ;
           SendWalletApproveEmailJob::dispatch($to_user, $subject, $superAdminData);

      return $agentWallet;

    }

    public function declineWalletReq($data,$id)
    {
        $AgentWalletRequest = $this->AgentWalletRequest->find($id);       
        $AgentWalletRequest->status = 3;
        $AgentWalletRequest->reject_reason = $data->reject_reason;
        $AgentWalletRequest->update();
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