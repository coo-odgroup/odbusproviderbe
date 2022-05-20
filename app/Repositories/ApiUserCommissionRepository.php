<?php

namespace App\Repositories;
use Illuminate\Support\Facades\Log;
use App\Models\ApiUserCommission;

class ApiUserCommissionRepository
{
    /**
     * @var apiUserCommission
     */
    protected $apiUserCommission;

    /**
     * apiUserCommissionRepository constructor.
     *
     * @param Post $apiUserCommission
     */
    public function __construct(ApiUserCommission $apiUserCommission)
    {
        $this->apiUserCommission = $apiUserCommission;
    }

    
    public function getAll($request)
    {
        return $this->apiUserCommission->get();
    }

    public function getAllApiUserCommissionData($request)
    {
        $paginate = $request['rows_number'] ;

        $data= $this->apiUserCommission
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
    public function getModel($data, apiUserCommission $apiUserCommission)
    {
        $apiUserCommission->user_id = $data['user_id'];
        $apiUserCommission->starting_fare = $data['starting_fare'];
        $apiUserCommission->upto_fare = $data['upto_fare'];    
        $apiUserCommission->commision = $data['commision'];    
        $apiUserCommission->created_by = $data['user_name'];
        $apiUserCommission->status = 1;
        //log::info($apiUserCommission);
        return $apiUserCommission;
    }
    
    public function getById($id)
    {
        return $this->apiUserCommission->where('id', $id)->get();
    }
    public function save($data)
    {       
        //Log::info($data);
        $apiUserCommission = new $this->apiUserCommission;
        $apiUserCommission=$this->getModel($data,$apiUserCommission);
        $apiUserCommission->save();
        return $apiUserCommission;
    }

    /**
     * Update apiUserCommission
     *
     * @param $data
     * @return Post
     */
    public function update($data, $id)
    {
        // Log::info($data);exit;
        $apiUserCommission = $this->apiUserCommission->find($id);
        $apiUserCommission=$this->getModel($data,$apiUserCommission);
        $apiUserCommission->update();
        return $apiUserCommission;
    }

    /**
     * Delete apiUserCommission
     *
     * @param $data
     * @return Post
     */
    public function delete($id)
    {
        //$post = $this->apiUserCommission->where('status',"0")->orWhere('status',"1")->findOrFail($id);
        $post = $this->apiUserCommission->find($id);
        $post->status = 2;
        $post->update();
        return $post;

    }
    public function changeStatus($id)
    {
        $post = $this->apiUserCommission->find($id);
        if($post->status==0){
            $post->status = 1;
        }elseif($post->status==1){
            $post->status = 0;
        }
        $post->update();
        return $post;
    }
    
}