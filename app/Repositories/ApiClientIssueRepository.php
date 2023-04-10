<?php
namespace App\Repositories;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\ApiClientIssue;
use App\Models\ApiClientIssueType;
use App\Models\ApiClientIssueSubType;

use App\Jobs\SendApiClientIssueEmailJob;
use App\Repositories\ChannelRepository;

class ApiClientIssueRepository
{
    protected $ApiClientIssue;
    protected $ApiClientIssueType;
    protected $ApiClientIssueSubType;
    protected $channelRepository;
    protected $user;

    public function __construct(ApiClientIssueType $ApiClientIssueType,
						    	ApiClientIssue $ApiClientIssue,
						    	ApiClientIssueSubType $ApiClientIssueSubType,
						    	ChannelRepository $channelRepository,
                                User $User)
    {
        $this->ApiClientIssueType = $ApiClientIssueType;  
        $this->ApiClientIssue = $ApiClientIssue;  
        $this->ApiClientIssueSubType = $ApiClientIssueSubType;  
        $this->channelRepository = $channelRepository;
        $this->User = $User;
    }

    
    public function apiclientissuetype()
    {
        return $this->ApiClientIssueType->where('status',1)->get();
    }

    public function apiclientissuesubtype($request)
    {
        return $this->ApiClientIssueSubType->where('apiclientissuetype_id',$request->id)->where('status',1)->get();
    }

    public function apiclientissuedata($request)
    {
       
        $paginate = $request['rows_number'] ;

        $data= ApiClientIssue::with('apiclientissuetype','apiclientissuesubtype','bus')
                    ->where('user_id', $request['user_id'] )
                    ->orderBy('id','DESC');

        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }

        $data=$data->paginate($paginate);

        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
           return $response;

       
    }

    public function addapiclientissue($request)
    {
        $user = $this->User->where('id',$request['user_id'])->where('status',1)->get();
        $issuetype =  $this->ApiClientIssueType->where('id',$request['issueType_id'])->where('status',1)->get();
        $issuesubtype = $this->ApiClientIssueSubType->where('id',$request['issueSubType_id'])->get();
        
        $ApiClientIssue = new $this->ApiClientIssue;
        $ApiClientIssue->user_id = $request['user_id'];
        $ApiClientIssue->apiclientissuetype_id = $request['issueType_id'];
        $ApiClientIssue->apiclientissuesubtype_id = $request['issueSubType_id'];
        $ApiClientIssue->reference_id = $request['reference_id'];
        $ApiClientIssue->bus_id = $request['busId'];
        $ApiClientIssue->bus_operator_id = $request['operatorId'];
        $ApiClientIssue->source_id = $request['source'];
        $ApiClientIssue->destination_id = $request['destination'];
        $ApiClientIssue->message = $request['message'];
        $ApiClientIssue->created_by = $request['created_by'];
        $ApiClientIssue->save();

               // $to_user = 'bishal.seofied@gmail.com';
               $to_user = 'support@odbus.in';
               $subject = "Api Client Issue from ".$user[0]->name." on ".$issuetype[0]->name ;
               $agentData= [
                        'userName'=>$user[0]->name,
                        'userEmail'=> $user[0]->email,
                        'issueType' => $issuetype[0]->name,
                        'issueSubType'=>$issuesubtype[0]->name,
                        'mesasage' =>$request['message'],
                       ] ;
                SendApiClientIssueEmailJob::dispatch($to_user, $subject, $agentData);
  
      return $ApiClientIssue;
    }


    public function changeStatus($request)
    {
        $agent_id =random_int(100000, 999999);
        $post = $this->agent->find($request->id);

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

    public function apiclientissuestatue($request)
    {
        $post = ApiClientIssue::find($request->id);
        $post->status = $request->status;
        $post->created_by = $request->created_by;
        $post->update();
        return $post;

        // $agent_id =random_int(100000, 999999);
        // $post = $this->agent->find($request->id);

        // if($post->status==0){
        //     $post->status = 1;
        //     $post->created_by = $request->created_by;
        //     $post->unique_id = $agent_id;
        // }elseif($post->status==1){
        //     $post->status = 0;
        //     $post->created_by = $request->created_by;
        //     $post->unique_id = $agent_id;
        // }
        // $post->update();
        // return $post;
    }


    public function allapiclientissuedata($request)
    {
       
        $paginate = $request['rows_number'] ;
        $user_id = $request['user_id'] ;

        $data= ApiClientIssue::with('apiclientissuetype','apiclientissuesubtype','bus')->orderBy('id','DESC');

        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }
        if($user_id !=null){
             $data=$data->where('user_id', $request['user_id'] );
        }

        $data=$data->paginate($paginate);

        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
           return $response;

       
    }
    
}