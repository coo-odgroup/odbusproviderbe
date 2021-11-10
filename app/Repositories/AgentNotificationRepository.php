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

          
         // Log::info($data['user_id']);
          foreach ($data['user_id'] as $k=>$v) 
          { 
            $userNotificationRecords=new UserNotification();
             $userNotificationRecords['user_id'] = $v;
             $userNotificationRecords['created_by']= "Admin"; 
             $user[] =   $userNotificationRecords;
          }
           $notification->userNotification()->saveMany($user);

        return $notification;
    } 

    public function delete($id)
    { 
        // Log::info($id);exit;
         $notification = $this->notification->find($id); 
         $notification->userNotification()->where('notification_id',$id)->delete();
         $notification->delete();
    }

    public function allPushNotification($request)
    {
        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;

         $data= $this->notification->where('created_by','Admin')
                                   ->orderBy('id','DESC');
        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) {
            $paginate = 10 ;
        }
        if($name!=null)
        {
            $data = $data->where('notification_heading', 'like', '%' .$name . '%')
                       ->orWhere('notification_details', 'like', '%' .$name . '%');
        }
        $data=$data->paginate($paginate);

        // Log::info($data);



        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   

           return $response;  

    }






}