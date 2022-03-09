<?php

namespace App\Repositories;

// use App\Models\Bus;
use App\Models\User;
use App\Models\AssocAssignOperator;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class AssociationAssignOperatorRepository
{
    
   protected $usercontent;
   protected $AssocAssignOperator;

   public function __construct(User $usercontent,AssocAssignOperator $AssocAssignOperator  )
   {
      $this->usercontent = $usercontent ;
      $this->AssocAssignOperator = $AssocAssignOperator ;
   }    
 


    public function getAllAssignOperator($request)
     {
      // Log::info($request);

        $paginate = $request['rows_number'] ;
        $assoc_id = $request['assoc_id'] ;


        $data = $this->AssocAssignOperator->with('busOperator','User')
                                          ->whereHas('User', function ($query) 
                                            {$query->where('role_id', '5' );
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
   
        
         $data=$data->paginate($paginate);
         

          $response = array(
               "count" => $data->count(), 
               "total" => $data->total(),
              "data" => $data
             );   
             return $response;
     } 



   public function addAssignOperator($request)
   {
 
     foreach ($request['operator_id'] as $k=>$opr_id) { 
       
       $AssocAssignOperator = new $this->AssocAssignOperator;
       $AssocAssignOperator->user_id = $request['user_id'];
       $AssocAssignOperator->created_by = $request['created_by'];
       $AssocAssignOperator->bus_operator_id = $opr_id;

        $AssocAssignOperator->save();

     }
    return 'done' ;
   } 


   public function deleteassocAssignOperator($request)
   {
     // log::info($request);
     // exit;
        $AssocAssignOperator = $this->AssocAssignOperator->find($request->id);
        // $AssocAssignOperator->status = 2;
        $AssocAssignOperator->delete();
        return $AssocAssignOperator;
   }




  

}