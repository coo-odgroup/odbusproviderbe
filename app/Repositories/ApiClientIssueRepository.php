<?php
namespace App\Repositories;
use Illuminate\Support\Facades\Log;
// use App\Models\Agent;
use App\Models\ApiClientIssue;
use App\Models\ApiClientIssueType;
use App\Models\ApiClientIssueSubType;

use App\Jobs\SendAgentCreationEmailJob;
use App\Repositories\ChannelRepository;

class ApiClientIssueRepository
{
    protected $ApiClientIssue;
    protected $ApiClientIssueType;
    protected $ApiClientIssueSubType;
    protected $channelRepository;

    public function __construct(ApiClientIssueType $ApiClientIssueType,
						    	ApiClientIssue $ApiClientIssue,
						    	ApiClientIssueSubType $ApiClientIssueSubType,
						    	ChannelRepository $channelRepository)
    {
        $this->ApiClientIssueType = $ApiClientIssueType;  
        $this->ApiClientIssue = $ApiClientIssue;  
        $this->ApiClientIssueSubType = $ApiClientIssueSubType;  
        $this->channelRepository = $channelRepository;
    }

    
    public function apiclientissuetype()
    {
        return $this->ApiClientIssueType->get();
    }

    public function apiclientissuesubtype($request)
    {
        return $this->ApiClientIssueSubType->where('apiclientissuetype_id',$request->id)->get();
    }


    // public function getModel($data, Agent $agent)
    // {
    //     $agent->name = $data['name'];
    //     $agent->email = $data['email'];    
    //     $agent->phone = $data['phone'];    
    //     $agent->password = bcrypt($data['password']);
    //     $agent->user_type = "Agent";
    //     $agent->role_id = "3";
    //     $agent->location = $data['location'];
    //     $agent->adhar_no = $data['adhar_no'];
    //     $agent->pancard_no = $data['pancard_no'];
    //     $agent->organization_name = $data['organization_name'];
    //     $agent->address = $data['address'];
    //     $agent->street = $data['street'];
    //     $agent->city = $data['city'];
    //     $agent->landmark = $data['landmark'];
    //     $agent->pincode = $data['pincode'];
    //     $agent->name_on_bank_account = $data['name_on_bank_account'];
    //     $agent->bank_name = $data['bank_name'];
    //     $agent->ifsc_code = $data['ifsc_code'];
    //     $agent->bank_account_no = $data['bank_account_no'];
    //     $agent->created_by = $data['created_by'];
    //     $agent->status = 0;
    //     return $agent;
    // }
    
    
    // public function save($data)
    // {
    
    //     $email = $this->agent->where('email',$data['email'])->where('status','!=',2)->get();
    //     $phone = $this->agent->where('phone',$data['phone'])->where('status','!=',2)->get();
    //     $aadhaar = $this->agent->where('adhar_no',$data['adhar_no'])->where('status','!=',2)->get();
    //     $pancard = $this->agent->where('pancard_no',$data['pancard_no'])->where('status','!=',2)->get();
       
    //     if(count($email)==0)
    //     {
    //         if(count($phone)==0)
    //         {
    //             if(count($aadhaar)==0)
    //             {
    //                 if(count($pancard)==0)
    //                 {
    //                         $agent = new $this->agent;
    //                         $agent=$this->getModel($data,$agent);
    //                         $agent->save();


    //                         $smsData = array(
    //                         'phone' => $data->phone,
    //                         'agentName' => $data->name,
    //                         'url' => 'https://agent.odbus.in/#/login', 
    //                         'agentEmail' => $data->email,
    //                         'agentPassword' => $data->password
    //                     );

    //                     $this->channelRepository->SendAgentCreationSms($smsData);


    //                            $to_user = $data->email;
    //                            $subject = "Agent Creation Email";
    //                            $agentData= [
    //                                     'userName'=>$data->name,
    //                                     'userEmail'=> $data->email,
    //                                     'userPassword'=> $data->password,
    //                                     'loginUrl'=>'https://agent.odbus.in/#/login',
                                    
    //                                    ] ;
    //                             SendAgentCreationEmailJob::dispatch($to_user, $subject, $agentData);
                          
    //                         // return $agent;
                   

    //                 }
    //                 else 
    //                 {
    //                     return 'Pan Card Already Exist';
    //                 }
    //             }
    //             else
    //             {
    //                 return 'Aadhaar Card Already Exist';
    //             }

    //         }
    //         else
    //         {
    //             return 'Phone Already Exist';
    //         }
    //     }
    //     else
    //     {
    //         return 'Email Already Exist';
    //     }
    // }


    public function changeStatus($request)
    {
        $agent_id =random_int(100000, 999999);
        $post = $this->agent->find($request->id);
     // log::info($agent_id);
     // exit;
        if($post->status==0){
            $post->status = 1;
            $post->created_by = $request->created_by;
            $post->unique_id = $agent_id;
        }elseif($post->status==1){
            $post->status = 0;
            $post->created_by = $request->created_by;
            $post->unique_id = $agent_id;
        }
        $post->update();
        return $post;
    }
    
}