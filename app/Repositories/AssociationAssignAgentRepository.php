<?php
namespace App\Repositories;
// use App\Models\Bus;
use App\Models\User;
use App\Models\AssocAssignAgent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

/*Priyadarshi to Review*/
class AssociationAssignAgentRepository
{
   protected $usercontent;
   protected $AssocAssignAgent;
   protected $AssocAssignOperator;

   public function __construct(User $usercontent,AssocAssignAgent $AssocAssignAgent )
   {
      $this->usercontent = $usercontent ;
      $this->AssocAssignAgent = $AssocAssignAgent ;
   }    
 

  public function getAssocBuslist($request)
  {
    $opr = $this->AssocAssignOperator
                ->with('busOperator.bus.ticketPrice','busOperator.bus.busOperator')
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
    if($busData)
    {
      foreach ($busData as $v)
      {
        foreach ($v->ticketPrice as $k=>$a) {

          $stoppages['source'][$k]=$this->location->where('id', $a->source_id)->get();
          $stoppages['destination'][$k]=$this->location->where('id', $a->destination_id)->get(); 
        }
         // Log::info($stoppages['source']);
        $v['from_location']=$stoppages['source'][0];
        $v['to_location']=$stoppages['destination'][0];
      }
    }

    
    return $busData;
  } 


   public function getassocAssignAgent($request)
     {
     	// log::info($request);
        $paginate = $request['rows_number'] ;
        $assoc_id = $request['assoc_id'] ;

        $data = $this->AssocAssignAgent->with('User')
                                     ->whereHas('User', function ($query) 
                                            {$query->where('role_id', '5' );
                                          })->where('status',1)->orderBy('id','DESC');
       
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
             $v['agent']=$this->usercontent->where('id',$v->agent_id)->where('status',1)->get();
          }}


          $response = array(
               "count" => $data->count(), 
               "total" => $data->total(),
              "data" => $data
             );   
             return $response;
     } 



   public function assocAssignAgent($request)
   {
   	// Log::info($request);
   	// exit;
     foreach ($request['agent_id'] as $k=>$agent_id) {        
       $AssocAssignAgent = new $this->AssocAssignAgent;
       $AssocAssignAgent->user_id = $request['user_id'];
       $AssocAssignAgent->created_by = $request['created_by'];
       $AssocAssignAgent->agent_id = $agent_id;

        $AssocAssignAgent->save();
     }
     return 'done' ;
   
   } 


   public function deleteassocAssignAgent($request)
   {
	   	// Log::info($request);
	   	// exit;
        $AssocAssignAgent = $this->AssocAssignAgent->find($request->id);
        $AssocAssignAgent->status = 2;
        $AssocAssignAgent->created_by = $request->created_by;
        $AssocAssignAgent->update();
        return $AssocAssignAgent;
   }




  

}