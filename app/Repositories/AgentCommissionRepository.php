<?php

namespace App\Repositories;
use Illuminate\Support\Facades\Log;
use App\Models\AgentCommission;
class AgentCommissionRepository
{
    /**
     * @var AgentCommission
     */
    protected $agentCommission;

    /**
     * AgentCommissionRepository constructor.
     *
     * @param Post $agentCommission
     */
    public function __construct(AgentCommission $agentCommission)
    {
        $this->agentCommission = $agentCommission;
    }

    
    public function getAll($request)
    {
        return $this->agentCommission->get();

    }

    public function getAllAgentCommissionData($request)
    {
        $paginate = $request['rows_number'] ;

        $data= $this->agentCommission
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
    public function getModel($data, AgentCommission $agentCommission)
    {
        $agentCommission->range_from = $data['range_from'];
        $agentCommission->range_to = $data['range_to'];    
        $agentCommission->comission_per_seat = $data['comission_per_seat'];    
        $agentCommission->created_by = "Admin";
        $agentCommission->status = 1;
        return $agentCommission;
    }
    
    public function getById($id)
    {
        return $this->agentCommission->where('id', $id)->get();
    }
    public function save($data)
    {
       
        $agentCommission = new $this->agentCommission;
        $agentCommission=$this->getModel($data,$agentCommission);
        $agentCommission->save();
        return $agentCommission;
    }

    /**
     * Update AgentCommission
     *
     * @param $data
     * @return Post
     */
    public function update($data, $id)
    {
        $agentCommission = $this->agentCommission->find($id);
        $agentCommission=$this->getModel($data,$agentCommission);
        $agentCommission->update();
        return $agentCommission;
    }

    /**
     * Delete AgentCommission
     *
     * @param $data
     * @return Post
     */
    public function delete($id)
    {
        //$post = $this->agentCommission->where('status',"0")->orWhere('status',"1")->findOrFail($id);
        $post = $this->agentCommission->find($id);
        $post->status = 2;
        $post->update();
        return $post;

    }
    public function changeStatus($id)
    {
        $post = $this->agentCommission->find($id);
        if($post->status==0){
            $post->status = 1;
        }elseif($post->status==1){
            $post->status = 0;
        }
        $post->update();
        return $post;
    }
    
}