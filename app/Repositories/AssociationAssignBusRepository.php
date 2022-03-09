<?php
namespace App\Repositories;
// use App\Models\Bus;
use App\Models\User;
use App\Models\AssocAssignBus;
use App\Models\AssocAssignOperator;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;
use App\Models\Location;

/*Priyadarshi to Review*/
class AssociationAssignBusRepository
{
   protected $usercontent;
   protected $AssocAssignBus;
   protected $AssocAssignOperator;

   public function __construct(User $usercontent,Location $location,AssocAssignBus $AssocAssignBus,AssocAssignOperator $AssocAssignOperator  )
   {
      $this->usercontent = $usercontent ;
      $this->AssocAssignBus = $AssocAssignBus ;
      $this->AssocAssignOperator = $AssocAssignOperator ;
      $this->location = $location ;
   }    
 

  public function getAssocBuslist($request)
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


   public function getassocAssignBus($request)
     {
      // Log::info($request);

        $paginate = $request['rows_number'] ;
        $assoc_id = $request['assoc_id'] ;


        $data = $this->AssocAssignBus->with('bus.busOperator','bus.ticketPrice','User')->orderBy('id','DESC');
       
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

         
            if($data){
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



   public function assocAssignBus($request)
   {
     foreach ($request['bus_id'] as $k=>$bus_id) {        
       $AssocAssignBus = new $this->AssocAssignBus;
       $AssocAssignBus->user_id = $request['user_id'];
       $AssocAssignBus->created_by = $request['created_by'];
       $AssocAssignBus->bus_id = $bus_id;

        $AssocAssignBus->save();
     }
     return 'done' ;
   
   } 


   public function deleteassocAssignBus($request)
   {
        $AssocAssignBus = $this->AssocAssignBus->find($request->id);
        $AssocAssignBus->delete();
        return $AssocAssignBus;
   }




  

}