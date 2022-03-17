<?php

namespace App\Repositories;
use App\Models\AssocAssignAgent;
use App\Models\AssocAssignBus;
use App\Models\AssocAssignOperator;
use App\Models\User;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

/*Priyadarshi to Review*/

class AssocAssignReportRepository
{    
    protected $usercontent;
    protected $AssocAssignAgent;
    protected $AssocAssignBus;
    protected $AssocAssignOperator;

    public function __construct(User $usercontent,AssocAssignAgent $AssocAssignAgent , AssocAssignBus $AssocAssignBus , AssocAssignOperator $AssocAssignOperator)
    {
        $this->AssocAssignOperator = $AssocAssignOperator;
        $this->AssocAssignBus = $AssocAssignBus;
        $this->AssocAssignAgent = $AssocAssignAgent;     
        $this->usercontent = $usercontent ;
    }    
  

    public function getAssignAgentData($request)
    {
    	// Log::info($request);
    	$userID = $request->userID ;
        $role_id = $request->role_id ;
        $paginate = $request->rows_number;
        $assoc_id = $request->assoc_id;
        $date_range = $request->date_range;

        // if(!empty($rangeFromDate))
        // {
        //     if(strlen($rangeFromDate['month'])==1)
        //     {
        //         $rangeFromDate['month']="0".$rangeFromDate['month'];
        //     }
        //     if(strlen($rangeFromDate['day'])==1)
        //     {
        //         $rangeFromDate['day']="0".$rangeFromDate['day'];
        //     }

        //     $start_date = $rangeFromDate['year'].'-'.$rangeFromDate['month'].'-'.$rangeFromDate['day'] ;     
        // }

        // if(!empty($rangeToDate))
        // {
        //     if(strlen($rangeToDate['month'])==1)
        //     {
        //         $rangeToDate['month']="0".$rangeToDate['month'];
        //     }
        //     if(strlen($rangeToDate['day'])==1)
        //     {
        //         $rangeToDate['day']="0".$rangeToDate['day'];
        //     }

        //     $end_date = $rangeToDate['year'].'-'.$rangeToDate['month'].'-'.$rangeToDate['day'] ;     
        // }
                 
        $data= $this->AssocAssignAgent->with('User',)
                    ->where('status','1')
                     ->whereHas('User', function ($query) {
                    	$query->where('role_id ', 5 );
                    })
                    ->orderBy('id','DESC');                  

        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) {
            $paginate = 10 ;
        }
        if($userID!= null && $role_id!= 1 )
          {
            $data = $data->Where('user_id', $userID);
          }

         $data=$data->paginate($paginate); 
          if($data)
            {
            foreach($data as $v){ 
             $v['agent']=$this->usercontent->where('id',$v->agent_id)->where('status',1)->get();
          }}
       
      
        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );    
           return $response; 
    }
     public function getAssignBusData($request)
    {
       // Log::info($request);
       // exit;
       	$userID = $request->userID ;
        $role_id = $request->role_id ;
        $paginate = $request->rows_number;
        $assoc_id = $request->assoc_id;
        $date_range = $request->date_range;
                 
        $data= $this->AssocAssignBus->with('User','bus')
                    ->where('status','1')
                     ->whereHas('User', function ($query) {
                    	$query->where('role_id ', 5 );
                    })
                    ->orderBy('id','DESC');                  

        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) {
            $paginate = 10 ;
        }
        if($userID!= null && $role_id!= 1 )
          {
            $data = $data->Where('user_id', $userID);
          }

          $data=$data->paginate($paginate); 
         //  if($data)
         //    {
         //    foreach($data as $v){ 
         //     $v['agent']=$this->usercontent->where('id',$v->agent_id)->where('status',1)->get();
         //  }}
       
      
        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );    
           return $response; 
    }
    public function getAssignOperatorData($request)
    {
        $userID = $request->userID ;
        $role_id = $request->role_id ;
        $paginate = $request->rows_number;
        $assoc_id = $request->assoc_id;
        $date_range = $request->date_range;
                 
        $data= $this->AssocAssignOperator->with('User','busOperator')
                    ->where('status','1')
                    ->whereHas('User', function ($query) {
                    	$query->where('role_id ', 5 );
                    })
                    ->orderBy('id','DESC');                  

        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) {
            $paginate = 10 ;
        }
        if($userID!= null && $role_id!= 1 )
          {
            $data = $data->Where('user_id', $userID);
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
}