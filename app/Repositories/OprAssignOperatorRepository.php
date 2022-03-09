<?php

namespace App\Repositories;

// use App\Models\Bus;
use App\Models\User;
use App\Models\OprAssignOperator;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class OprAssignOperatorRepository
{
    
   protected $usercontent;
   protected $OprAssignOperator;

   public function __construct(User $usercontent,oprAssignOperator $OprAssignOperator  )
   {
      $this->usercontent = $usercontent ;
      $this->OprAssignOperator = $OprAssignOperator ;
   }     


    public function getAllAssignOperator($request)
     {
        // Log::info($request);
        $paginate = $request['rows_number'];
        $assoc_id = $request['assoc_id'];

        $data = $this->OprAssignOperator->with('busOperator','User')
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

        if($assoc_id)
        {
          $data = $data->where('user_id',$assoc_id);
        }   
        
        $data = $data->paginate($paginate);     
        $response = array(
               "count" => $data->count(), 
               "total" => $data->total(),
              "data" => $data
             );   
        return $response;
     } 



   public function addAssignOperator($request)
   { 
        foreach ($request['operator_id'] as $k=>$opr_id) 
        {        
            $OprAssignOperator = new $this->OprAssignOperator;
            $OprAssignOperator->user_id = $request['user_id'];
            $OprAssignOperator->created_by = $request['created_by'];
            $OprAssignOperator->bus_operator_id = $opr_id;
            $OprAssignOperator->save();
        }
        return 'done' ;
   } 

   public function deleteOprAssignOperator($request)
   {
        // log::info($request);
        // exit;
        $OprAssignOperator = $this->OprAssignOperator->find($request->id);
        // $AssocAssignOperator->status = 2;
        $OprAssignOperator->delete();
        return $OprAssignOperator;
   }
}