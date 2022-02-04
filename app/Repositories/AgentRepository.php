<?php

namespace App\Repositories;
use Illuminate\Support\Facades\Log;
use App\Models\Agent;
class AgentRepository
{
    /**
     * @var Agent
     */
    protected $agent;

    /**
     * AgentRepository constructor.
     *
     * @param Post $agent
     */
    public function __construct(Agent $agent)
    {
        $this->agent = $agent;
    }

    
    public function getAll($request)
    {
        return $this->agent->get();

    }

    public function updateAgentProfile($request)
    {
        // log::info($request);
        // exit;
        $agent=$this->agent->find($request['user_id']);
   
        
        $agent->name = $request['name'];
        $agent->email = $request['email'];    
        $agent->phone = $request['phone'];
        if($request['pwd_check']==true && $request['password']!='')
        {
            $agent->password = bcrypt($request['password']);
        }
        $agent->location = $request['location'];
        $agent->adhar_no = $request['adhar_no'];
        $agent->pancard_no = $request['pancard_no'];
        $agent->organization_name = $request['organization_name'];
        $agent->address = $request['address'];
        $agent->street = $request['street'];
        $agent->city = $request['city'];
        $agent->landmark = $request['landmark'];
        $agent->pincode = $request['pincode'];
        $agent->name_on_bank_account = $request['name_on_bank_account'];
        $agent->branch_name = $request['branch_name'];
        $agent->bank_name = $request['bank_name'];
        $agent->ifsc_code = $request['ifsc_code'];
        $agent->bank_account_no = $request['bank_account_no'];
        $agent->upi_id = $request['upi_id'];
        $agent->update();
        // log::info($agent);
        return $agent;

    }

     public function agentprofile($request)
    {
       
        $data= $this->agent->where('id',$request['user_id'])->get();
        // log::info($data);
         return $data;

    }

    public function getAllAgentData($request)
    {
        // log::info($request);
         $paginate = $request['rows_number'] ;
         $name = $request['name'] ;
         $status = $request['status'];

        $data= $this->agent
                    ->where('status', 0)
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
                         ->orWhere('bank_account_no', 'like', '%' .$name . '%')
                         ->orWhere('ifsc_code', 'like', '%' .$name . '%')
                         ->orWhere('organization_name', 'like', '%' .$name . '%')
                         ->where('status', $status);                        
        }
        elseif($name!=null && $status==null)
        {
            $data = $data->where('name', 'like', '%' .$name . '%')
                         ->orWhere('email', 'like', '%' .$name . '%')
                         ->orWhere('phone', 'like', '%' .$name . '%')
                         ->orWhere('bank_account_no', 'like', '%' .$name . '%')
                         ->orWhere('ifsc_code', 'like', '%' .$name . '%')
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


    public function ourAgentData($request)
    {
        // log::info($request);
         $paginate = $request['rows_number'] ;
         $name = $request['name'] ;
         $status = $request['status'];

        $data= $this->agent
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
                         ->orWhere('bank_account_no', 'like', '%' .$name . '%')
                         ->orWhere('ifsc_code', 'like', '%' .$name . '%')
                         ->orWhere('organization_name', 'like', '%' .$name . '%')
                         ->where('status', $status);                        
        }
        elseif($name!=null && $status==null)
        {
            $data = $data->where('name', 'like', '%' .$name . '%')
                         ->orWhere('email', 'like', '%' .$name . '%')
                         ->orWhere('phone', 'like', '%' .$name . '%')
                         ->orWhere('bank_account_no', 'like', '%' .$name . '%')
                         ->orWhere('ifsc_code', 'like', '%' .$name . '%')
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
    public function getModel($data, Agent $agent)
    {
        $agent->name = $data['name'];
        $agent->email = $data['email'];    
        $agent->phone = $data['phone'];    
        $agent->password = bcrypt($data['password']);
        $agent->user_type = "Agent";
        $agent->role_id = "3";
        $agent->location = $data['location'];
        $agent->adhar_no = $data['adhar_no'];
        $agent->pancard_no = $data['pancard_no'];
        $agent->organization_name = $data['organization_name'];
        $agent->address = $data['address'];
        $agent->landmark = $data['landmark'];
        $agent->pincode = $data['pincode'];
        $agent->name_on_bank_account = $data['name_on_bank_account'];
        $agent->bank_name = $data['bank_name'];
        $agent->ifsc_code = $data['ifsc_code'];
        $agent->bank_account_no = $data['bank_account_no'];
        $agent->created_by = $data['created_by'];
        $agent->status = 0;
        return $agent;
    }
    
    public function getById($id)
    {
        return $this->agent->where('id', $id)->get();
    }
    public function save($data)
    {
        // log::info($data);
        // exit;
        $email = $this->agent->where('email',$data['email'])->where('status','!=',2)->get();
        $phone = $this->agent->where('phone',$data['phone'])->where('status','!=',2)->get();
        $aadhaar = $this->agent->where('adhar_no',$data['adhar_no'])->where('status','!=',2)->get();
        $pancard = $this->agent->where('pancard_no',$data['pancard_no'])->where('status','!=',2)->get();
       
        if(count($email)==0)
        {
            if(count($phone)==0)
            {
                if(count($aadhaar)==0)
                {
                    if(count($pancard)==0)
                    {
                            $agent = new $this->agent;
                            $agent=$this->getModel($data,$agent);
                            $agent->save();
                            return $agent;
                    }
                    else
                    {
                        return 'Pan Card Already Exist';
                    }
                }
                else
                {
                    return 'Aadhaar Card Already Exist';
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
     * Update Agent
     *
     * @param $data
     * @return Post
     */
    public function update($data, $id)
    {
        
        $email = $this->agent->where('email',$data['email'])->where('id','!=',$id )->where('status','!=',2)->get();
        $phone = $this->agent->where('phone',$data['phone'])->where('id','!=',$id )->where('status','!=',2)->get();
        $aadhaar = $this->agent->where('adhar_no',$data['adhar_no'])->where('id','!=',$id )->where('status','!=',2)->get();
        $pancard = $this->agent->where('pancard_no',$data['pancard_no'])->where('id','!=',$id )->where('status','!=',2)->get();
          
        // $duplicate_email = $this->agent
        //                        ->where('email',$data['email'])
        //                        ->where('id','!=',$id )
        //                        ->where('status','!=',2)
        //                        ->get(); 
        // $duplicate_phone = $this->agent
        //                        ->where('phone',$data['phone'])
        //                        ->where('id','!=',$id )
        //                        ->where('status','!=',2)
        //                        ->get();
        // log::info($data);
        // exit;
         if(count($email)==0)
        {
            if(count($phone)==0)
            {
                if(count($aadhaar)==0)
                {
                    if(count($pancard)==0)
                    {
                            $agent = $this->agent->find($id);
                            if($agent->password != $data['password'])
                            {
                                 $agent->password = bcrypt($data['password']);
                            }                        
                            $agent->name = $data['name'];
                            $agent->email = $data['email'];    
                            $agent->phone = $data['phone'];   
                            $agent->user_type = "Agent";
                            $agent->role_id = "3";
                            $agent->location = $data['location'];
                            $agent->adhar_no = $data['adhar_no'];
                            $agent->pancard_no = $data['pancard_no'];
                            $agent->organization_name = $data['organization_name'];
                            $agent->address = $data['address'];
                            $agent->landmark = $data['landmark'];
                            $agent->pincode = $data['pincode'];
                            $agent->name_on_bank_account = $data['name_on_bank_account'];
                            $agent->bank_name = $data['bank_name'];
                            $agent->ifsc_code = $data['ifsc_code'];
                            $agent->bank_account_no = $data['bank_account_no'];
                            $agent->created_by = $data['created_by'];
                            $agent->update();
                            return $agent;
                    }
                    else
                    {
                        return 'Pan Card Already Exist';
                    }
                }
                else
                {
                    return 'Aadhaar Card Already Exist';
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
        $post = $this->agent->find($id);
        $post->status = 2;
        $post->update();
        return $post;

    }
    public function changeStatus($id)
    {
     
        $post = $this->agent->find($id);
        if($post->status==0){
            $post->status = 1;
        }elseif($post->status==1){
            $post->status = 0;
        }
        $post->update();
        return $post;
    }

    public function blockAgent($request)
    {
        $post = $this->agent->find($request->id);
        if($post->status==1){
            $post->status = 3;
        }elseif($post->status==3){
            $post->status = 1;
        }
        if($request->reason!= NULL)
        {
             $post->reason =$request->reason;
        }
        $post->update();
        return $post;
    }
    
}