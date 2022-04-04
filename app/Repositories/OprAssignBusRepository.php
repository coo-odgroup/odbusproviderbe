<?php
namespace App\Repositories;
// use App\Models\Bus;
use App\Models\User;
use App\Models\OprAssignBus;
use App\Models\AssocAssignOperator;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;
use App\Models\Location;

/*Priyadarshi to Review*/
class OprAssignBusRepository
{
   protected $usercontent;
   protected $OprAssignBus;
   protected $AssocAssignOperator;

   public function __construct(User $usercontent,Location $location,OprAssignBus $OprAssignBus,AssocAssignOperator $AssocAssignOperator)
   {
        $this->usercontent = $usercontent;
        $this->OprAssignBus = $OprAssignBus;
        $this->AssocAssignOperator = $AssocAssignOperator;
        $this->location = $location;
   }    
 

  public function getOprBuslist($request)
  {
        $opr = $this->AssocAssignOperator
                    ->with('busOperator.bus')
                    ->where('user_id',$request['assoc_id'])
                    ->get();
        $busData=[];

        foreach($opr as $k=>$data)
        {
        if(count($data->busOperator['bus'])>0){

            foreach ($data->busOperator['bus'] as $v) {
            $busData[]= $v;
            }       
        }
        }
        // Log::info($busData);
        return $busData;
  } 

  public function getOperatorbuslist($request)
  {
        $opr = $this->usercontent
                    ->with('busOperator.bus')
                    ->where('id',$request['assoc_id'])
                    ->get();
        $busData=[];

        // Log::info($opr);
        // exit;

        foreach($opr as $k=>$data)
        {
          if(count($data->busOperator['bus'])>0){
              foreach ($data->busOperator['bus'] as $v) {
              $busData[]= $v;
              }       
          }
        }
        
        return $busData;
  } 

   public function getOprAssignBus($request)
   {
        // Log::info($request);

        $paginate = $request['rows_number'];
        $assoc_id = $request['assoc_id'];

        $data = $this->OprAssignBus->with('bus.busOperator','bus.ticketPrice','User')
                                    ->whereHas('User', function ($query) 
                                            {$query->where('role_id', '4');
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

         
         if($data)
         {
            foreach($data as $v){ 
             foreach($v->bus->ticketPrice as $k => $a)
             {     
                   $stoppages['source'][$k]=$this->location->where('id', $a->source_id)->get();
                   $stoppages['destination'][$k]=$this->location->where('id', $a->destination_id)->get(); 
             }
                  $v['from_location']=$stoppages['source'][0];
                  $v['to_location']=$stoppages['destination'][0];
            }
          }


          $response = array(
               "count" => $data->count(), 
               "total" => $data->total(),
              "data" => $data
             );   
             return $response;
    } 



   public function OprAssignBus($request)
   {
    
    $oldrecord=$this->OprAssignBus->where('user_id',$request['user_id'])->get();

    // Log::info($request);
    // exit;
    if(count($oldrecord)==0){
      foreach ($request['bus_id'] as $k=>$bus_id) {  
            
        $OprAssignBus = new $this->OprAssignBus;
        $OprAssignBus->user_id = $request['user_id'];
        $OprAssignBus->created_by = $request['created_by'];
        $OprAssignBus->bus_id = $bus_id;
        $OprAssignBus->save();
        }
         
          return 'done' ;
    }
    else
    {
        return 'Operator Exist' ; 
    }
          
   } 


   public function deleteOprAssignBus($request)
   {
        $OprAssignBus = $this->OprAssignBus->find($request->id);
        $OprAssignBus->delete();
        return $OprAssignBus;
   }
}