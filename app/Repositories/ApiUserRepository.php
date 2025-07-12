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
        // log::info($users);exit;
                    //exit;
        $email = $this->user->where('email',$data['email'])->where('status','!=',2)->get();
        $phone = $this->user->where('phone',$data['phone'])->where('status','!=',2)->get();
        $pancard = $this->user->where('pancard_no',$data['pancard_no'])->where('status','!=',2)->get();

        if(count($email) == 0)
        {
            if(count($phone) == 0)
            {                
                if(count($pancard) == 0)
                {
                    $users = new $this->user;
                    $users = $this->getModel($data,$users);                         
                    // log::info($users); exit;
                    $users->save();    

                    return $users;
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

    public function getAllApiUserData($request)
    {       
         $paginate = $request['rows_number'] ;
         $name = $request['name'] ;
         $status = $request['status'];
         $start_date  =  $request->rangeFromDate;
         $end_date  =  $request->rangeToDate;        

         $data = $this->user->where('role_id', 6)
                            ->orderBy('id','DESC');                              
                                     

        if($paginate == 'all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }

        if($status!=null)
        {
            if($status == 1)
            {
                $data = $data->where('status', 1); 
            }
            elseif($status == 0)
            {
                $data = $data->where('status', 0); 
            }                                      
        }        

        if($name!=null && $status!=null)
        {
            $data = $data->where('name', 'like', '%' .$name . '%')
                         ->orWhere('email', 'like', '%' .$name . '%')
                         ->orWhere('phone', 'like', '%' .$name . '%')                  
                         ->where('status', $status);                        
        }
        elseif($name!=null && $status==null)
        {
            $data = $data->where('name', 'like', '%' .$name . '%')
                         ->orWhere('email', 'like', '%' .$name . '%')
                         ->orWhere('phone', 'like', '%' .$name . '%');                                                 
        }
        elseif($name==null && $status!=null)
        {
            $data = $data->where('status', $status);                        
        }

        if($start_date != null && $end_date != null)
        {
            if($start_date == $end_date)
            {
                $data = $data->where('created_at','like','%'.$start_date.'%')
                             ->orderBy('created_at','DESC');                       
            }
            else
            {
                $data = $data->whereBetween('created_at', [$start_date, $end_date]);
            }  
        }      

        $data = $data->paginate($paginate);
       // log::info($data); 

        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
             "data"  => $data
           );   
        return $response;       
    }
    
    public function getModel($data, User $user)
    {
        $gst = 0;
        $client_id = random_int(1123456, 9999999);
        if($data['has_gst'] == 'true')
        {
            $gst = 1;
        }

        $user->name = $data['name'];
        $user->email = $data['email'];    
        $user->alternate_email = $data['alternate_email'];    
        $user->phone = $data['phone'];    
        $user->client_id = $client_id;    
        $user->password = bcrypt($data['password']);
        $user->user_type = "API USER";
        $user->role_id = "6";
        $user->location = $data['location'];
        $user->pancard_no = $data['pancard_no'];
        $user->organization_name = $data['organization_name'];
        $user->has_gst = $gst;
        $user->address = $data['address'];
        $user->street = $data['street'];
        $user->city = $data['city'];
        $user->landmark = $data['landmark'];
        $user->pincode = $data['pincode'];      
        $user->created_by = $data['created_by'];
        $user->status = 1;
        return $user;
    }
    
    public function getById($id)
    {
        return $this->user->where('id', $id)->get();
    }   

    /**
     * Update API User
     *
     * @param $data
     * @return Post
     */
    public function update($data, $id)
    {        
        // log::info($data); exit;
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

                        // if($user->password != $data['password'])
                        // {
                        //     $user->password = bcrypt($data['password']);
                        // }  
                        
                        $user->name = $data['name'];
                        $user->email = $data['email'];    
                        $user->alternate_email = $data['alternate_email'];    
                        $user->phone = $data['phone'];   
                        $user->user_type = "API USER";
                        $user->role_id = "6";
                        $user->location = $data['location'];
                        $user->pancard_no = $data['pancard_no'];
                        $user->organization_name = $data['organization_name'];
                        $user->has_gst = $data['has_gst'];
                        $user->address = $data['address'];
                        $user->landmark = $data['landmark'];
                        $user->pincode = $data['pincode'];    
                        $user->street = $data['street'];
                        $user->city = $data['city'];                    
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
     * Delete API User
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

    public function apiclientprofile($request)
    {
        return $this->user->where('id',$request['user_id'])->get();
    } 

    public function updateapiclient($request)
    {
        $apiuser=$this->user->find($request['user_id']);
        $apiuser->name = $request['name'];
        $apiuser->email = $request['email'];    
        $apiuser->phone = $request['phone'];
        if($request['pwd_check']==true && $request['password']!='')
        {
            $apiuser->password = bcrypt($request['password']);
        }
        $apiuser->location = $request['location'];
        $apiuser->adhar_no = $request['adhar_no'];
        $apiuser->pancard_no = $request['pancard_no'];
        $apiuser->organization_name = $request['organization_name'];
        $apiuser->address = $request['address'];
        $apiuser->street = $request['street'];
        $apiuser->city = $request['city'];
        $apiuser->landmark = $request['landmark'];
        $apiuser->pincode = $request['pincode'];
        $apiuser->name_on_bank_account = $request['name_on_bank_account'];
        $apiuser->branch_name = $request['branch_name'];
        $apiuser->bank_name = $request['bank_name'];
        $apiuser->ifsc_code = $request['ifsc_code'];
        $apiuser->bank_account_no = $request['bank_account_no'];
        $apiuser->upi_id = $request['upi_id'];
        $apiuser->update();

        return $apiuser;
    }   


  


}