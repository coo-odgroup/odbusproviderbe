<?php

namespace App\Repositories;
use Illuminate\Support\Facades\Log;
use App\Models\AgentFee;
class AgentFeeRepository
{
    /**
     * @var AgentFee
     */
    protected $agentFee;

    /**
     * AgentFeeRepository constructor.
     *
     * @param Post $agentFee
     */
    public function __construct(AgentFee $agentFee)
    {
        $this->agentFee = $agentFee;
    }

    
    public function getAll($request)
    {
        return $this->agentFee->get();

    }

    public function getAllAgentFeeData($request)
    {
        $paginate = $request['rows_number'] ;

        $data= $this->agentFee
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
    public function getModel($data, AgentFee $agentFee)
    {
        $agentFee->price_from = $data['price_from'];
        $agentFee->price_to = $data['price_to'];    
        $agentFee->max_comission = $data['max_comission'];    
        $agentFee->created_by = "Admin";
        $agentFee->status = 1;
        return $agentFee;
    }
    
    public function getById($id)
    {
        return $this->agentFee->where('id', $id)->get();
    }
    public function save($data)
    {
       
        $agentFee = new $this->agentFee;
        $agentFee=$this->getModel($data,$agentFee);
        $agentFee->save();
        return $agentFee;
    }

    /**
     * Update AgentFee
     *
     * @param $data
     * @return Post
     */
    public function update($data, $id)
    {
        $agentFee = $this->agentFee->find($id);
        $agentFee=$this->getModel($data,$agentFee);
        $agentFee->update();
        return $agentFee;
    }

    /**
     * Delete AgentFee
     *
     * @param $data
     * @return Post
     */
    public function delete($id)
    {
        $post = $this->agentFee->find($id);
        $post->status = 2;
        $post->update();
        return $post;

    }
    public function changeStatus($id)
    {
        $post = $this->agentFee->find($id);
        if($post->status==0){
            $post->status = 1;
        }elseif($post->status==1){
            $post->status = 0;
        }
        $post->update();
        return $post;
    }
    
}