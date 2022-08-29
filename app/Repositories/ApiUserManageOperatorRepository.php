<?php

namespace App\Repositories;
use Illuminate\Support\Facades\Log;
use App\Models\ApiUserManageOperator;


class ApiUserManageOperatorRepository
{
    /**
     * @var Agent
     */
       protected $ApiUserManageOperator;

    /**
     * AgentRepository constructor.
     *
     * @param Post $agent
     */
    public function __construct(ApiUserManageOperator $ApiUserManageOperator)
    {
        $this->ApiUserManageOperator = $ApiUserManageOperator;  
    }

 
    public function manageClientOperatorData($request)
    {
       
         $paginate = $request['rows_number'] ;
         $user = $request['user_id'] ;
        

        $data= $this->ApiUserManageOperator->with('user','busOperator')->orderBy('updated_at','DESC');

        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }
        elseif($user!=null)
        {
            $data = $data->where('user_id', $user);                        
        }

        $data=$data->paginate($paginate);

        // log::info($data);

        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
        
        return $response;

       
    }


  
   public function manageClientOperator($data)
    {
    	$insertData = [];

  		if($data!=''){
  			foreach ($data['bus_operator_id'] as $e=>$k) 
  			{
  				$apiOperator= new $this->ApiUserManageOperator;
	    		$apiOperator->user_id = $data['user_id'];
	    		$apiOperator->bus_operator_id = $k;
	    		$apiOperator->created_by = $data->created_by;
	    		$apiOperator->save();
    	    }
    	    return $apiOperator;
  		}   
    }
    
    public function deletemanageClientOperator($id)
    {
    	// log::info($id);
    	
    	
        $post = $this->ApiUserManageOperator->find($id);
        $post->delete();
        

         return $post;

    }
    
}