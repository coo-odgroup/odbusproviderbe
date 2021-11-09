<?php
namespace App\Repositories;
use App\Models\Notification;
use App\Models\UserNotification;


use Illuminate\Support\Facades\Log;

class AgentNotificationRepository
{
 
    protected $notification;
    protected $userNotification;
    
    public function __construct(UserNotification $userNotification,Notification $notification)
    {
        $this->notification = $notification;
    }
    
    public function getallnotification($id){

        return $this->notification->whereNotIn('status', [2])
        ->whereHas('userNotification', function ($query) use ($id)
         {$query->where('user_id', $id );
     });
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

 public function dateFilter($data, $start_date,$end_date){
     return $data->whereBetween('created_at', [$start_date, $end_date])
     ->orderBy('created_at','DESC');
 }   


 public function getModel($data,Notification $notification)
    {      
        $notification->notification_heading = $data['subject'];
        $notification->notification_details = $data['notification']; 
        $notification->created_by = "Admin";
        return $notification;
    }

    public function save($data)
    {  
         $notification = new $this->notification; 
         $notification= $this->getModel($data,$notification) ;
         $notification->save();

         $userNotification[0]=new UserNotification();
         $userNotification[0]['user_id'] = $data['user_id'];
         $userNotification[0]['created_by']= "Agent";

         $notification->userNotification()->saveMany($userNotification);

        return $agentWallet;
    } 


}