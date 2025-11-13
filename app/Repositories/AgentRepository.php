<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use App\Models\Agent;
use App\Jobs\SendAgentCreationEmailJob;
use App\Repositories\ChannelRepository;
use Illuminate\Support\Facades\Config;
use Exception;
use App\Traits\ApiResponser;

class AgentRepository
{
    use ApiResponser;
    /**
     * @var Agent
     */
    protected $agent;
    protected $channelRepository;

    /**
     * AgentRepository constructor.
     *
     * @param Post $agent
     */
    public function __construct(Agent $agent, ChannelRepository $channelRepository)
    {
        $this->agent = $agent;
        $this->channelRepository = $channelRepository;
    }

<<<<<<< HEAD
    
    public function getAll()
=======

    public function getAll($request)
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
    {
        return $this->agent->get();
    }

    public function updateAgentProfile($request)
    {
<<<<<<< HEAD
        try {
            $agent=$this->agent->find($request['user_id']);
   
            $agent->name = $request['name'];
            $agent->email = $request['email'];
            $agent->phone = $request['phone'];
            if($request['pwd_check']=="true" && $request['password']!='')
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
            return $agent;
            
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
=======
        // log::info($request);
        // exit;
        $agent = $this->agent->find($request['user_id']);


        $agent->name = $request['name'];
        $agent->email = $request['email'];
        $agent->phone = $request['phone'];
        if ($request['pwd_check'] == true && $request['password'] != '') {
            $agent->password = bcrypt($request['password']);
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
        }

    }

    public function agentprofile($request)
    {
<<<<<<< HEAD
        return $this->agent->where('id',$request['user_id'])->get();
=======

        $data = $this->agent->where('id', $request['user_id'])->get();
        // log::info($data);
        return $data;

>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
    }

    public function getAllAgentData($request)
    {

        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;
        $status = $request['status'];
        $start_date  =  $request->rangeFromDate;
        $end_date  =  $request->rangeToDate;

        $data = $this->agent
                    ->where('status', 0)
<<<<<<< HEAD
                    ->where('role_id',3)
                    ->where('email','!=',null)
                    ->orderBy('updated_at','DESC');

        if($paginate=='all')
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null)
        {
=======
                    ->where('role_id', 3)
                    ->where('email', '!=', null)
                    ->orderBy('updated_at', 'DESC');

        if ($paginate == 'all') {
            $paginate = Config::get('constants.ALL_RECORDS');
        } elseif ($paginate == null) {
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
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



        if ($name != null && $status != null) {
            $data = $data->where('name', 'like', '%' .$name . '%')
                         ->orWhere('email', 'like', '%' .$name . '%')
                         ->orWhere('phone', 'like', '%' .$name . '%')
                         ->orWhere('bank_account_no', 'like', '%' .$name . '%')
                         ->orWhere('ifsc_code', 'like', '%' .$name . '%')
                         ->orWhere('organization_name', 'like', '%' .$name . '%')
                         ->where('status', $status);
<<<<<<< HEAD
        }
        elseif($name!=null && $status==null)
        {
=======
        } elseif ($name != null && $status == null) {
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
            $data = $data->where('name', 'like', '%' .$name . '%')
                         ->orWhere('email', 'like', '%' .$name . '%')
                         ->orWhere('phone', 'like', '%' .$name . '%')
                         ->orWhere('bank_account_no', 'like', '%' .$name . '%')
                         ->orWhere('ifsc_code', 'like', '%' .$name . '%')
                         ->orWhere('organization_name', 'like', '%' .$name . '%');
<<<<<<< HEAD
        }
        elseif($name==null && $status!=null)
        {
            $data = $data->where('status', $status);
        }
        
=======
        } elseif ($name == null && $status != null) {
            $data = $data->where('status', $status);
        }
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c


        if ($start_date != null && $end_date != null) {
            if ($start_date == $end_date) {
                $data = $data->where('created_at', 'like', '%'.$start_date.'%')
                        ->orderBy('created_at', 'DESC');

            } else {
                $data = $data->whereBetween('created_at', [$start_date, $end_date]);
            }

<<<<<<< HEAD
        $data=$data->paginate($paginate);
=======
        }


        $data = $data->paginate($paginate);
        // Log::info($data);
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c

        $response = array(
             "count" => $data->count(),
             "total" => $data->total(),
            "data" => $data
           );
<<<<<<< HEAD
           return $response;
=======
        return $response;

>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c

    }


    public function ourAgentData($request)
    {
<<<<<<< HEAD
         $paginate = $request['rows_number'] ;
         $name = $request['name'] ;
         $status = $request['status'];
=======
        // log::info($request);
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c

        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;
        $status = $request['status'];

<<<<<<< HEAD
        if($paginate=='all')
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null)
        {
=======
        $data = $this->agent->where('role_id', 3)
                    ->wherenotIn('status', [0,2  ])
                    ->orderBy('id', 'DESC');

        if ($paginate == 'all') {
            $paginate = Config::get('constants.ALL_RECORDS');
        } elseif ($paginate == null) {
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
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
        if ($name != null && $status != null) {
            $data = $data->where('name', 'like', '%' .$name . '%')
                         ->orWhere('email', 'like', '%' .$name . '%')
                         ->orWhere('phone', 'like', '%' .$name . '%')
                         ->orWhere('bank_account_no', 'like', '%' .$name . '%')
                         ->orWhere('ifsc_code', 'like', '%' .$name . '%')
                         ->orWhere('organization_name', 'like', '%' .$name . '%')
                         ->where('status', $status);
<<<<<<< HEAD
        }
        elseif($name!=null && $status==null)
        {
=======
        } elseif ($name != null && $status == null) {
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
            $data = $data->where('name', 'like', '%' .$name . '%')
                         ->orWhere('email', 'like', '%' .$name . '%')
                         ->orWhere('phone', 'like', '%' .$name . '%')
                         ->orWhere('bank_account_no', 'like', '%' .$name . '%')
                         ->orWhere('ifsc_code', 'like', '%' .$name . '%')
                         ->orWhere('organization_name', 'like', '%' .$name . '%');
<<<<<<< HEAD
        }
        elseif($name==null && $status!=null)
        {
            $data = $data->where('status', $status);
        }
        
=======
        } elseif ($name == null && $status != null) {
            $data = $data->where('status', $status);
        }
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c


<<<<<<< HEAD
        $data=$data->paginate($paginate);
=======


        $data = $data->paginate($paginate);
        // Log::info($data);
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c

        $response = array(
             "count" => $data->count(),
             "total" => $data->total(),
            "data" => $data
           );
<<<<<<< HEAD
           return $response;
=======
        return $response;

>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c

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
        $agent->street = $data['street'];
        $agent->city = $data['city'];
        $agent->landmark = $data['landmark'];
        $agent->pincode = $data['pincode'];
        $agent->name_on_bank_account = $data['name_on_bank_account'];
        $agent->bank_name = $data['bank_name'];
        $agent->ifsc_code = $data['ifsc_code'];
        $agent->bank_account_no = $data['bank_account_no'];
        $agent->created_by = $data['created_by'];
        $agent->agent_type = $data['agentType'];
        $agent->status = 0;
        return $agent;
    }

    public function getById($id)
    {
        return $this->agent->where('id', $id)->get();
    }
    public function savePostData($data)
    {
<<<<<<< HEAD
    
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
=======

        $email = $this->agent->where('email', $data['email'])->where('status', '!=', 2)->get();
        $phone = $this->agent->where('phone', $data['phone'])->where('status', '!=', 2)->get();
        $aadhaar = $this->agent->where('adhar_no', $data['adhar_no'])->where('status', '!=', 2)->get();
        $pancard = $this->agent->where('pancard_no', $data['pancard_no'])->where('status', '!=', 2)->get();

        if (count($email) == 0) {
            if (count($phone) == 0) {
                if (count($aadhaar) == 0) {
                    if (count($pancard) == 0) {
                        $agent = new $this->agent();
                        $agent = $this->getModel($data, $agent);
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
                        $agent->save();


                        $smsData = array(
<<<<<<< HEAD
                            'phone' => $data->phone,
                            'agentName' => $data->name,
                            'url' => 'https://agent.odbus.in/#/login', 
                            'agentEmail' => $data->email,
                            'agentPassword' => $data->password
=======
                        'phone' => $data->phone,
                        'agentName' => $data->name,
                        'url' => 'https://agent.odbus.in/#/login',
                        'agentEmail' => $data->email,
                        'agentPassword' => $data->password
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
                        );

                        // $this->channelRepository->SendAgentCreationSms($smsData);


                        $to_user = $data->email;
                        $subject = "Agent Creation Email";
<<<<<<< HEAD
                        $agentData= [
                            'userName'=>$data->name,
                            'userEmail'=> $data->email,
                            'userPassword'=> $data->password,
                            'loginUrl'=>'https://agent.odbus.in/#/login',
                        ] ;
                        SendAgentCreationEmailJob::dispatch($to_user, $subject, $agentData);
                        
                        return $agent;
                   

                    }
                    else
                    {
=======
                        $agentData = [
                                 'userName' => $data->name,
                                 'userEmail' => $data->email,
                                 'userPassword' => $data->password,
                                 'loginUrl' => 'https://agent.odbus.in/#/login',

                                ] ;
                        SendAgentCreationEmailJob::dispatch($to_user, $subject, $agentData);

                        return $agent;


                    } else {
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
                        return 'Pan Card Already Exist';
                    }
                } else {
                    return 'Aadhaar Card Already Exist';
                }
<<<<<<< HEAD
            }
            else
            {
=======

            } else {
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
                return 'Phone Already Exist';
            }
        } else {
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
<<<<<<< HEAD
        $email = $this->agent->where('email',$data['email'])->where('id','!=',$id )->where('status','!=',2)->get();
        $phone = $this->agent->where('phone',$data['phone'])->where('id','!=',$id )->where('status','!=',2)->get();
        $aadhaar = $this->agent->where('adhar_no',$data['adhar_no'])->where('id','!=',$id )->where('status','!=',2)->get();
        $pancard = $this->agent->where('pancard_no',$data['pancard_no'])->where('id','!=',$id )->where('status','!=',2)->get();
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
=======
        // log::info($data);exit;

        $email = $this->agent->where('email', $data['email'])->where('id', '!=', $id)->where('status', '!=', 2)->get();
        $phone = $this->agent->where('phone', $data['phone'])->where('id', '!=', $id)->where('status', '!=', 2)->get();
        $aadhaar = $this->agent->where('adhar_no', $data['adhar_no'])->where('id', '!=', $id)->where('status', '!=', 2)->get();
        $pancard = $this->agent->where('pancard_no', $data['pancard_no'])->where('id', '!=', $id)->where('status', '!=', 2)->get();
        if (count($email) == 0) {
            if (count($phone) == 0) {
                if (count($aadhaar) == 0) {
                    if (count($pancard) == 0) {
                        $agent = $this->agent->find($id);
                        if ($agent->password != $data['password']) {
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
                            $agent->password = bcrypt($data['password']);
                        }
                        $agent->name = $data['name'];
                        $agent->email = $data['email'];
                        $agent->phone = $data['phone'];
                        $agent->user_type = "Agent";
                        $agent->role_id = "3";
                        $agent->location = $data['location'];
                        $agent->agent_type = $data['agentType'];
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
<<<<<<< HEAD
                    }
                    else
                    {
=======
                    } else {
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
                        return 'Pan Card Already Exist';
                    }
                } else {
                    return 'Aadhaar Card Already Exist';
                }
            } else {
                return 'Phone Already Exist';
            }
        } else {
            return 'Email Already Exist';
        }
    }

    /**
     * Delete Agent
     *
     * @param $data
     * @return Post
     */
    public function deleteById($id)
    {
        $post = $this->agent->find($id);
        $post->status = 2;
        $post->update();
        return $post;

    }
    public function changeStatus($request)
    {
        $agent_id = random_int(100000, 999999);
        $post = $this->agent->find($request->id);
<<<<<<< HEAD
        if($post->status==0){
=======
        // log::info($agent_id);
        // exit;
        if ($post->status == 0) {
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
            $post->status = 1;
            $post->created_by = $request->created_by;
            $post->unique_id = $agent_id;
        } elseif ($post->status == 1) {
            $post->status = 0;
            $post->created_by = $request->created_by;
            $post->unique_id = $agent_id;
        }
        $post->update();
        return $post;
    }

    public function blockAgent($request)
    {
        $post = $this->agent->find($request->id);

        if ($post->status == 1) {
            $post->status = 3;
        } elseif ($post->status == 3) {
            $post->status = 1;
            $post->reason = "";
        }
<<<<<<< HEAD
        if($request->reason!= null)
        {
             $post->reason =$request->reason;
=======
        if ($request->reason != null) {
            $post->reason = $request->reason;
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
        }
        $post->update();
        return $post;
    }

}
