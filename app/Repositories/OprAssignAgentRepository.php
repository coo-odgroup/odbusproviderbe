<?php

namespace App\Repositories;
use App\Models\User;
use App\Models\AssocAssignAgent;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class OprAssignAgentRepository
{    
   protected $usercontent;
   protected $assocAssignAgent;

   public function __construct(User $usercontent,AssocAssignAgent $assocAssignAgent  )
   {
      $this->usercontent = $usercontent ;
      $this->assocAssignAgent = $assocAssignAgent ;
   }     


    public function getAllAssignAgent($request)
    {        
        $paginate = $request['rows_number'];
        $user_id = $request['user_id'];
        $data = $this->assocAssignAgent->with('User.busOperator')
                                        ->whereHas('User', function ($query){
                                                $query->where('role_id', '4' );
                                         })    
                                        ->orderBy('id','DESC');
       
        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }

        if($user_id)
        {
           $data = $data->where('user_id',$user_id);
        }   
        
        $data = $data->paginate($paginate);    
        
        if($data)
        {
            foreach($data as $v)
            { 
                $v['agent']=$this->usercontent->where('id',$v->agent_id)->where('status',1)->get();
            }
        }
        $response = array(
               "count" => $data->count(), 
               "total" => $data->total(),
               "data" => $data
             );   
        return $response;
    } 

    public function addAssignAgent($request)
    { 
        //log::info($request);

        foreach ($request['agent_id'] as $k=>$agent_id) 
        {        
            $oprAssignAgent = new $this->assocAssignAgent;
            $oprAssignAgent->user_id = $request['user_id'];
            $oprAssignAgent->created_by = $request['created_by'];
            $oprAssignAgent->agent_id = $agent_id;
            $oprAssignAgent->save();
        }
        return 'done' ;
    } 

   public function deleteOprAssignAgent($request)
   {
        $assocAssignAgent = $this->assocAssignAgent->find($request->id);
        // $AssocAssignOperator->status = 2;
        $assocAssignAgent->delete();
        return $assocAssignAgent;
   }
}