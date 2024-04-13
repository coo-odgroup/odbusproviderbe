<?php
namespace App\Repositories;

use App\Models\ApiClientWalletRequest;
use App\Models\ApiClientWallet;
use App\Models\Notification;
use App\Models\UserNotification;
use App\Models\User;
use App\Models\Booking;
use App\Models\Location;

use App\Jobs\SendSuperAdminEmailJob;
use App\Jobs\SendSupportEmailJob;
use App\Jobs\SendWalletEmailJob;
use App\Jobs\SendWalletApproveEmailJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use DB;

class ApiClientWalletRepository
{
  
    protected $ApiClientWallet; 
    protected $ApiClientWalletRequest; 
    protected $notification;
    protected $user;
    protected $booking;
    
    public function __construct(ApiClientWallet $ApiClientWallet,Notification $notification,User $user,ApiClientWalletRequest $ApiClientWalletRequest,Booking $booking,Location $location)
    {
        $this->ApiClientWallet = $ApiClientWallet;
        $this->ApiClientWalletRequest = $ApiClientWalletRequest;
        $this->notification = $notification;
        $this->user = $user;
        $this->booking = $booking;
        $this->location = $location;
    }
      
    public function getModel($data, ApiClientWalletRequest $ApiClientWalletRequest)
    {
        $otp =random_int(100000, 999999);
        $ApiClientWalletRequest->transaction_id = $data['transaction_id'];
        $ApiClientWalletRequest->reference_id = $data['reference_id'];
        $ApiClientWalletRequest->amount = $data['amount'];
        $ApiClientWalletRequest->payment_via = $data['payment_via'];
        $ApiClientWalletRequest->remarks = $data['remarks'];
        $ApiClientWalletRequest->user_id = $data['user_id'];
        $ApiClientWalletRequest->transaction_type = $data['transaction_type'];       
        $ApiClientWalletRequest->created_by = $data['user_name'];
        $ApiClientWalletRequest->otp = $otp ;
        $ApiClientWalletRequest->status = 0;

        return $ApiClientWalletRequest;
    }

    public function save($data)
    {    
        // Log::info($data);
        // exit; 
        $user = $this->user->find($data['user_id']);
        // $balance = $this->ApiClientWallet->where('user_id',$data['user_id'])->where('status',1)->orderBy('id','DESC')->limit(1)->get();
        $ApiClientWalletRequest = new $this->ApiClientWalletRequest;
        $ApiClientWalletRequest=$this->getModel($data,$ApiClientWalletRequest);

        $ApiClientWalletRequest->save(); 

           $to_user = $user->email ;
           $subject = "Wallet recharge request - ".$user->name;
           $agentData= [
                    'userName'=>$user->name,
                    'amount'=>$data['amount'],
                    'via'=>$data['payment_via'],
                    'tran_id'=>$data['transaction_id']
                   ] ;

           SendWalletEmailJob::dispatch($to_user, $subject, $agentData);

           $subject = "API Client Wallet recharge request From ".$user->name;
           $supportData= [
                    'userName'=>$user->name,
                    'amount'=>$data['amount'],
                    'via'=>$data['payment_via'],
                    'tran_id'=>$data['transaction_id'],
                    'otp' => $ApiClientWalletRequest->otp,
                   ] ;

            SendSuperAdminEmailJob::dispatch("santosh@odbus.in", $subject, $supportData); 
            SendSuperAdminEmailJob::dispatch("support@odbus.in", $subject, $supportData);
            

           $to_superadmin = "coo@odgroup.in";
           // $to_superadmin ="bishal.seofied@gmail.com";
           $subject = "API Client Wallet recharge request From Agent";
           $superAdminData= [
                    'userName'=>$user->name,
                    'amount'=>$data['amount'],
                    'via'=>$data['payment_via'],
                    'tran_id'=>$data['transaction_id'],
                    'otp' => $ApiClientWalletRequest->otp,
                   ] ;
           SendSuperAdminEmailJob::dispatch($to_superadmin, $subject, $superAdminData);


        return $ApiClientWalletRequest;
    }



    public function balance($id)
    {
        $ApiClientWallet = $this->ApiClientWallet->where('user_id',$id)->where('status',1)->orderBy('id','DESC')->limit(1)->get(); 

        return $ApiClientWallet;  
    }

  
    public function getAllWalletRecord(){
        return $this->ApiClientWalletRequest->with('user')->orderBy('id','DESC')->where('payment_via','!=',"");
    }
    
    public function ApiClientWalletBalancedetails($request){
       
        $paginate = $request->rows_number;
        $name = $request->name;
        $user_id = $request->user_id;
        $start_date  =  $request->rangeFromDate;
        $end_date  =  $request->rangeToDate;    

        $data = $this->ApiClientWallet->select('user_id', (DB::raw('max(id) as max_id')))
                                  ->where('status', 1)
                                  ->orderBy('created_at','DESC')
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
                $v['wallet'] = $this->ApiClientWallet->where('id',$v->max_id)->get();
            }
        }
      
        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           ); 
           return $response;    

    } 

    public function apiClientTotalTransactions($request){

        // Log::info($request);
        $user_id = $request->user_id;
        $start_date  =  $request->rangeFromDate;
        $end_date  =  $request->rangeToDate; 
        $dt = date('Y-m-d', strtotime('-7 days'));
        $today = date('Y-m-d');

        if($start_date != NULL && $end_date != NULL)
        {
            if($start_date == $end_date)
            {
                $transaction_list = $this->ApiClientWallet->where('user_id',372)->where('status', 1)->orderBy('id','ASC')->where('created_at','like','%'.$start_date.'%')->get();              
            }
            else{
                $transaction_list = $this->ApiClientWallet->where('user_id',372)->where('status', 1)->orderBy('id','ASC')->whereBetween('created_at', [$start_date, $end_date])->get();                                        
            }                       
        }else{
            $transaction_list = $this->ApiClientWallet->where('user_id',372)->where('status', 1)->orderBy('id','ASC')->whereBetween('created_at', [$dt, $today])->get();
        }    

        
      

        // Log::info($transaction_list);
        $main=[];
        // $main['credit']=0;
        foreach($transaction_list as $e => $w){            
            if($w->booking_id!= null){
                $totalSeats = 0;

                // categorized all the rows for the same booking id (booking,commision,refund)
                $booking_array=$this->ApiClientWallet->where('booking_id',$w->booking_id)->where('status', 1)->get();
                // get booking details from booking table by using $w->booking_id
                $booking_detail = $this->booking->with('BookingDetail')->find($w->booking_id);
                foreach($booking_detail->BookingDetail as $a){
                    $totalSeats = ++$totalSeats ;
                }

                $source = $this->location->select('name')->find($booking_detail->source_id);
                $destination = $this->location->select('name')->find($booking_detail->destination_id);

                $main[$e]['booking_id']=$w->booking_id;
                $main[$e]['pnr']=$booking_detail->pnr;
                $main[$e]['source']=$source->name;
                $main[$e]['destination']=$destination->name;
                $main[$e]['journey_date']=$booking_detail->journey_dt;
                $main[$e]['journey_time']=$booking_detail->boarding_point;
                $main[$e]['seat']=$totalSeats;
                $main[$e]['client_gst']=$booking_detail->client_gst;
                $main[$e]['cancel_percent']=$booking_detail->deduction_percent;
                $main[$e]['cancel_charges']=0;
                $main[$e]['created_at']= $w->created_at;

                    foreach($booking_array as $b){
                    if($b->type=='Commission'){
                     $main[$e]['Commission']=$b->amount;
                     $main[$e]['closing_balance']=$b->balance;
                    }

                    if($b->type=='Refund'){
                     $main[$e]['Refund']=$b->amount;
                     $main[$e]['closing_balance']=$b->balance;
                     $main[$e]['cancel_charges']=$booking_detail->payable_amount - $booking_detail->refund_amount;
                    }

                    if($b->type== null){     
                     $main[$e]['booking_amount']=$b->amount;
                     $main[$e]['opening_balance']=$b->balance+$b->amount;                     
                    }

                }
                 $main[$e]['debit']=$main[$e]['booking_amount'] - $main[$e]['Commission'];
            }
            else
            {
                $main[$e]['opening_balance']= $w->balance - $w->amount;
                $main[$e]['credit']=$w->amount;
                $main[$e]['closing_balance']=$w->balance;
                $main[$e]['created_at']= $w->created_at;

            }            
        }

        $new_arr = [];
        $arr = array_map('unserialize', array_unique(array_map('serialize', $main)));

        $pnr_arr=[];
           foreach ($arr as $key => $value){
               if(@$value['pnr'] && !in_array($value['pnr'],$pnr_arr)){
                   $new_arr[] = $value;
                   array_push($pnr_arr,$value['pnr']);
               }elseif(!@$value['pnr']){
                   $new_arr[] = $value;
               }
              
           }
        return $new_arr;

        // return 'success';
    }

    public function agentAllTransaction($request){
        
        $start_date="";
        $end_date="";
        $paginate = $request->rows_number;
        $name = $request->name;
        $user_id = $request->user_id;
        $start_date  =  $request->rangeFromDate;
        $end_date  =  $request->rangeToDate;
        $tranType  =  $request->tranType;

        // $data= $this->ApiClientWallet->with('user')->where('status', 1)->orderBy('id','DESC');
        $data= $this->ApiClientWallet->with('user','booking')->where('status', 1)->orderBy('id','DESC');


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
        return $this->ApiClientWalletRequest->where('user_id',$user_id)->orderBy('id','DESC')->whereNotIn('status', [2])->where('payment_via','!=',"");
    }

    public function getWalletRecord($user_id){
        return $this->ApiClientWallet->where('user_id',$user_id)->orderBy('id','DESC')->whereNotIn('status', [2])->where('payment_via','!=',"");
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
      return $ApiClientWallet = $this->ApiClientWalletRequest->where('id',$id)->where('otp',$data->otp)->get();

    }


    public function update_Status($id,$ext_data,$data)
    {
        $balance = 0 ;
     
         $ApiClientWallet = $this->ApiClientWallet->where('user_id',$ext_data->user_id)->where('status',1)->orderBy('id','DESC')->limit(1)->get();
         $user = $this->user->find($ext_data->user_id); 

        $ApiClientWalletRequest = $this->ApiClientWalletRequest->find($id);

         if(count($ApiClientWallet)>0)
        {
            $balance = $ApiClientWallet[0]->balance + $ApiClientWalletRequest->amount;
        }else
        {
            $balance = $ApiClientWalletRequest->amount;
        }

        $ApiClientWallet = new $this->ApiClientWallet;
        $ApiClientWallet->transaction_id = $ApiClientWalletRequest->transaction_id;
        $ApiClientWallet->reference_id = $ApiClientWalletRequest->reference_id;
        $ApiClientWallet->amount = $ApiClientWalletRequest->amount;
        $ApiClientWallet->balance = $balance;
        $ApiClientWallet->payment_via = $ApiClientWalletRequest->payment_via;
        $ApiClientWallet->remarks = $ApiClientWalletRequest->remarks;
        $ApiClientWallet->user_id = $ApiClientWalletRequest->user_id;
        $ApiClientWallet->transaction_type = $ApiClientWalletRequest->transaction_type;       
        $ApiClientWallet->created_by = $data->user_name;
        $ApiClientWallet->status = 1;
        $ApiClientWallet->otp = "";

        // log::info($data);
        // exit;

        $ApiClientWallet->save();
        $ApiClientWalletRequest->delete(); 

         $notification = new $this->notification; 
         $notification->notification_heading = "Wallet Recharge of Rs.".$ext_data->amount." Approved";
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

         
      return $ApiClientWallet;

    }

    public function declineWalletReq($data,$id)
    {

        // log::info($data);
        
        $ApiClientWalletRequest = $this->ApiClientWalletRequest->find($id);       
        $ApiClientWalletRequest->status = 3;
        $ApiClientWalletRequest->reject_reason = $data->reject_reason;
        $ApiClientWalletRequest->update();
        return "Success";
    }

    public function update_balance($id,$balance,$otpdata,$data)
    {
         $ApiClientWallet = $this->ApiClientWallet->find($id);
         $user = $this->user->find($ApiClientWallet->user_id);
         $lastrow = $this->ApiClientWallet->where('user_id',$ApiClientWallet->user_id)
                                      ->where('status',1)
                                      ->orderBy('id','DESC')
                                      ->limit(1)
                                      ->get();
         $ApiClientWallet->balance = $balance;
         $ApiClientWallet->update();
         if($lastrow[0]->id != $ApiClientWallet->id);
         {
                $ApiClientWalletLastRow = $this->ApiClientWallet->find($lastrow[0]->id);
                $ApiClientWalletLastRow->balance = $balance;
                $ApiClientWalletLastRow->update();
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
         
      return $ApiClientWallet;

    }



    public function allTransactionData($request)
    {
        // log::info($request);
        // exit;
        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;
        $user_id =$request['user_id'];
        $startDate =$request['startDate'];
        $endDate =$request['endDate'];

        $data = $this->ApiClientWallet->where('user_id',$user_id)->orderBy('id','DESC')->whereNotIn('status', [2]) ;

        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }

        if($name!= null && $name!= 'all_transaction')
        {
          
            if($name == 'recharge')
            {
                $data =$data->where('payment_via','!=','')->where('transaction_type','c' );
            } 
            elseif($name == 'commission_received')
            {
                $data =$data->where('type','Commission')->where('transaction_type','c') ;
            } 
            elseif($name == 'booking')
            {
                 $data =$data->where('payment_via','')->where('transaction_type','d') ;
            }
            elseif($name == 'cancellation')
            {
                $data =$data->where('type','Refund')->where('transaction_type','c') ;
            }
                       
        }
        if($startDate!=null && $endDate!=null)
        {
            if($startDate == $endDate){
                $data = $data->where('created_at','like','%'.$endDate.'%')
                        ->orderBy('created_at','DESC');;
            }
            else
            {
                $data =$data->whereBetween('created_at', [$startDate, $endDate])
                        ->orderBy('created_at','DESC');
            }
           
        }

        $data=  $data->paginate($paginate);

        if($data){
            foreach($data as $key=>$v){
                if($v->booking_id!=null){
                    $v['pnrDetails']=$this->booking->where('id', $v->booking_id)->get();
                }
            }
        }

        $response = array(
            "count" => $data->count(), 
            "total" => $data->total(),
            "data" => $data
        );  

        return $response;  

    }

    public function clientTransByAdmin($data)
    {   
    $booking_id='';
        $user = $this->user->find($data['user_id']);
        $balance = 0 ;

        $pnr = $this->booking->where('pnr', $data['pnr'])->get();

        if(!empty($pnr)){
            $booking_id= $pnr[0]->id;
        }
     
         $agentWallet = $this->ApiClientWallet->where('user_id',$data['user_id'])
                                          ->where('status',1)->orderBy('id','DESC')->limit(1)
                                          ->get();
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

        $agentWallet = new $this->ApiClientWallet;
        $agentWallet->transaction_id =  $data['transaction_id'];
        $agentWallet->reference_id =  $data['reference_id'];
        $agentWallet->amount = $data['amount'];
        $agentWallet->balance = $balance;
        $agentWallet->booking_id = $booking_id;
        $agentWallet->remarks = $data['remarks'];
        $agentWallet->user_id = $data['user_id'];
        $agentWallet->transaction_type = $data['transaction_type'];       
        $agentWallet->created_by = $data['created_by'];
        $agentWallet->payment_via = '';
        $agentWallet->status = 1;

        $agentWallet->save(); 

        return $agentWallet;
    }

    public function clientTransUpdateByAdmin($data)
    {   
        // log::info($data);
        // exit();

        $agentWallet = $this->ApiClientWallet->find($data->id);
        $agentWallet->amount = $data->amount;
        $agentWallet->balance = $data->balance;
        $agentWallet->update();
        return $agentWallet;
    }
    
}