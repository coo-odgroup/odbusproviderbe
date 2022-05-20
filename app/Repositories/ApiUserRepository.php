<?php

namespace App\Repositories;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Jobs\SendAgentCreationEmailJob;
use App\Repositories\ChannelRepository;

class ApiUserRepository
{
    /**
     * @var User
     */
    protected $user;
    protected $channelRepository;

    /**
     * AgentRepository constructor.
     *
     * @param Post $user
     */
    public function __construct(User $user,ChannelRepository $channelRepository)
    {
        $this->user = $user;  
        $this->channelRepository = $channelRepository;
    }

    public function save($data)
    {    
        $email = $this->user->where('email',$data['email'])->where('status','!=',2)->get();
        $phone = $this->user->where('phone',$data['phone'])->where('status','!=',2)->get();
        $pancard = $this->user->where('pancard_no',$data['pancard_no'])->where('status','!=',2)->get();
       
        if(count($email) == 0)
        {
            if(count($phone) == 0)
            {                
                if(count($pancard) == 0)
                {
                    $user = new $this->user;
                    $user = $this->getModel($data,$user);
                    // log::info($user);exit;

                    $user->save();

                    $smsData = array(
                        'phone' => $data->phone,
                        'agentName' => $data->name,
                        'url' => 'https://agent.odbus.in/#/login', 
                        'agentEmail' => $data->email,
                        'agentPassword' => $data->password
                    );

                   // $this->channelRepository->SendAgentCreationSms($smsData);

                    $to_user = $data->email;
                    $subject = "Agent Creation Email";
                    $agentData= [
                                    'userName'=>$data->name,
                                    'userEmail'=> $data->email,
                                    'userPassword'=> $data->password,
                                    'loginUrl'=>'https://agent.odbus.in/#/login'                        
                                ];
                    //SendAgentCreationEmailJob::dispatch($to_user, $subject, $agentData);

                    return $user;
                }
                else 
                {
                    return 'Pan Card Already Exist';
                }
            }
            else
            {
                return 'Phone Already Exist';
            }
        }
        else
        {
            return 'Email Already Exist';
        }
    }

    
    public function getAll($request)
    {
        return $this->user->get();

    }

    public function updateAgentProfile($request)
    {
        // log::info($request);
        // exit;
        $user = $this->user->find($request['user_id']);        
        $user->name = $request['name'];
        $user->email = $request['email'];    
        $user->phone = $request['phone'];

        if($request['pwd_check']==true && $request['password']!='')
        {
            $user->password = bcrypt($request['password']);
        }

        $user->location = $request['location'];
        $user->pancard_no = $request['pancard_no'];
        $user->organization_name = $request['organization_name'];
        $user->address = $request['address'];
        $user->street = $request['street'];
        $user->city = $request['city'];
        $user->landmark = $request['landmark'];
        $user->pincode = $request['pincode'];
        $user->update();
        // log::info($user);
        return $user;
    }

     public function agentprofile($request)
     {       
        $data= $this->user->where('id',$request['user_id'])->get();
        // log::info($data);
         return $data;
     }

    public function getAllApiUserData($request)
    {       
         $paginate = $request['rows_number'] ;
         $name = $request['name'] ;
         $status = $request['status'];
         $start_date  =  $request->rangeFromDate;
         $end_date  =  $request->rangeToDate;

         //

         $data= $this->user->where('role_id', 6)
                          ->orderBy('id','DESC');                   

        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }

        //  if($status!=null)
        // {
        //     if($status== 1){
        //         $data = $data->where('status', 1); 
        //     }
        //     elseif($status== 0)
        //     {
        //         $data = $data->where('status', 0); 
        //     }                                      
        // }

        

        if($name!=null && $status!=null)
        {
            $data = $data->where('name', 'like', '%' .$name . '%')
                         ->orWhere('email', 'like', '%' .$name . '%')
                         ->orWhere('phone', 'like', '%' .$name . '%')                        
                         ->orWhere('organization_name', 'like', '%' .$name . '%')
                         ->where('status', $status);                        
        }
        elseif($name!=null && $status==null)
        {
            $data = $data->where('name', 'like', '%' .$name . '%')
                         ->orWhere('email', 'like', '%' .$name . '%')
                         ->orWhere('phone', 'like', '%' .$name . '%')                        
                         ->orWhere('organization_name', 'like', '%' .$name . '%');                        
        }
        elseif($name==null && $status!=null)
        {
            $data = $data->where('status', $status);                        
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

        $data=$data->paginate($paginate);
        // Log::info($data);

        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
        return $response;       
    }


    public function ourAgentData($request)
    {
        // log::info($request);
        
         $paginate = $request['rows_number'] ;
         $name = $request['name'] ;
         $status = $request['status'];

        $data= $this->user->where('role_id',6)
                    ->wherenotIn('status',[0,2  ])
                    ->orderBy('id','DESC');

        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }

        //  if($status!=null)
        // {
        //     if($status== 1){
        //         $data = $data->where('status', 1); 
        //     }
        //     elseif($status== 0)
        //     {
        //         $data = $data->where('status', 0); 
        //     }                                      
        // }
        if($name!=null && $status!=null)
        {
            $data = $data->where('name', 'like', '%' .$name . '%')
                         ->orWhere('email', 'like', '%' .$name . '%')
                         ->orWhere('phone', 'like', '%' .$name . '%')                       
                         ->orWhere('organization_name', 'like', '%' .$name . '%')
                         ->where('status', $status);                        
        }
        elseif($name!=null && $status==null)
        {
            $data = $data->where('name', 'like', '%' .$name . '%')
                         ->orWhere('email', 'like', '%' .$name . '%')
                         ->orWhere('phone', 'like', '%' .$name . '%')                       
                         ->orWhere('organization_name', 'like', '%' .$name . '%');                        
        }
        elseif($name==null && $status!=null)
        {
            $data = $data->where('status', $status);                        
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
    public function getModel($data, User $user)
    {
        $user->name = $data['name'];
        $user->email = $data['email'];    
        $user->phone = $data['phone'];    
        $user->password = bcrypt($data['password']);
        $user->user_type = "API USER";
        $user->role_id = "6";
        $user->location = $data['location'];
        $user->pancard_no = $data['pancard_no'];
        $user->organization_name = $data['organization_name'];
        $user->address = $data['address'];
        $user->street = $data['street'];
        $user->city = $data['city'];
        $user->landmark = $data['landmark'];
        $user->pincode = $data['pincode'];      
        $user->created_by = $data['created_by'];
        $user->status = 1;
        //log::info($user);exit;
        return $user;
    }
    
    public function getById($id)
    {
        return $this->user->where('id', $id)->get();
    }
   

    /**
     * Update Agent
     *
     * @param $data
     * @return Post
     */
    public function update($data, $id)
    {        
        $email = $this->user->where('email',$data['email'])->where('id','!=',$id )->where('status','!=',2)->get();
        $phone = $this->user->where('phone',$data['phone'])->where('id','!=',$id )->where('status','!=',2)->get();
        $pancard = $this->user->where('pancard_no',$data['pancard_no'])->where('id','!=',$id )->where('status','!=',2)->get();

         if(count($email)==0)
        {
            if(count($phone)==0)
            {                
                if(count($pancard)==0)
                {
                        $user = $this->user->find($id);
                        if($user->password != $data['password'])
                        {
                                $user->password = bcrypt($data['password']);
                        }    

                        $user->name = $data['name'];
                        $user->email = $data['email'];    
                        $user->phone = $data['phone'];   
                        $user->user_type = "API USER";
                        $user->role_id = "6";
                        $user->location = $data['location'];
                        $user->pancard_no = $data['pancard_no'];
                        $user->organization_name = $data['organization_name'];
                        $user->address = $data['address'];
                        $user->landmark = $data['landmark'];
                        $user->pincode = $data['pincode'];                        
                        $user->created_by = $data['created_by'];
                        $user->update();
                        return $user;
                }
                else
                {
                    return 'Pan Card Already Exist';
                }                
            }
            else
            {
                return 'Phone Already Exist';
            }
        }
        else
        {
            return 'Email Already Exist';
        }
    }

    /**
     * Delete Agent
     *
     * @param $data
     * @return Post
     */
    public function delete($id)
    {
        $post = $this->user->find($id);
        $post->status = 2;
        $post->update();
        return $post;

    }
    public function changeStatus($request)
    {
        $post = $this->user->find($request->id);

        if($post->status==0)
        {
            $post->status = 1;            
        }
        elseif($post->status==1)
        {
            $post->status = 0;         
        }
        $post->update();
        return $post;
    }

    public function blockAgent($request)
    {
        $post = $this->user->find($request->id);

        if($post->status==1){
            $post->status = 3;
        }elseif($post->status==3){
            $post->status = 1;
            $post->reason ="";
        }
        if($request->reason!= NULL)
        {
             $post->reason =$request->reason;
        }
        $post->update();
        return $post;
    }
    
}