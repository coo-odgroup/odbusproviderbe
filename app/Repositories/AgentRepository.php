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

    public function getAllAgentData($request)
    {
        $paginate = $request['rows_number'] ;

        $data= $this->agent
                    ->whereNotIn('status', [2])
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
        $agent->password = $data['password'];
        $agent->user_type = "Agent";
        $agent->role_id = "3";
        $agent->location = $data['location'];
        $agent->adhar_no = $data['adhar_no'];
        $agent->pancard_no = $data['pancard_no'];
        $agent->organization_name = $data['organization_name'];
        $agent->address = $data['address'];
        $agent->street = $data['street'];
        $agent->landmark = $data['landmark'];
        $agent->city = $data['city'];
        $agent->pincode = $data['pincode'];
        $agent->name_on_bank_account = $data['name_on_bank_account'];
        $agent->bank_name = $data['bank_name'];
        $agent->ifsc_code = $data['ifsc_code'];
        $agent->bank_account_no = $data['bank_account_no'];
        $agent->branch_name = $data['branch_name'];
        $agent->upi_id = $data['upi_id'];
        $agent->created_by = "Admin";
        $agent->status = 1;
        return $agent;
    }
    
    public function getById($id)
    {
        return $this->agent->where('id', $id)->get();
    }
    public function save($data)
    {
       
        $agent = new $this->agent;
        $agent=$this->getModel($data,$agent);
        $agent->save();
        return $agent;
    }

    /**
     * Update Agent
     *
     * @param $data
     * @return Post
     */
    public function update($data, $id)
    {
        $agent = $this->agent->find($id);
        $agent=$this->getModel($data,$agent);
        $agent->update();
        return $agent;
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
    
}