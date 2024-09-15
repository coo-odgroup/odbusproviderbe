<?php
namespace App\Repositories;

use App\Models\TicketFareSlab;
use App\Models\BusOperator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
class TicketFareSlabRepository
{
    protected $ticketFareSlab;
    protected $busOperator;
   
    public function __construct(TicketFareSlab $ticketFareSlab,BusOperator $busOperator)
    {
        $this->ticketFareSlab = $ticketFareSlab;
        $this->busOperator = $busOperator;
       
    }
   
    public function ticketFareSlabData($request)
    {      
         $paginate = $request['rows_number'] ;
         $name = $request['name'] ;
         //log::info($request);
       

        $data= $this->busOperator->with('ticketFareSlab')
                                    // ->whereNotIn('status', [2])
                                    ->whereHas('TicketFareSlab', function ($query) {
                                        $query->where('status', 1 );
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

        if($name!=null)
        {
            $data = $data->where('operator_name','like', '%' .$name .'%')
                         ->orwhere('organisation_name','like', '%' .$name .'%');
        } 
      

        $data=$data->paginate($paginate);

        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
           return $response;
               }    

    public function createslab($data)
    {
        $opr_id =$data['bus_operator_id'] ;    

        // $duplicate_data = $this->ticketFareSlab
        //                        ->where('bus_operator_id',$opr_id)
        //                        ->where('status','!=',2)
        //                        ->get();
        // if(count($duplicate_data)==0)
        // {
            $cSlabDetails=[];
            $slabs=$data['slabs'];
            foreach($slabs as $slab_data)
            {
                $cSlabInfo=new $this->ticketFareSlab;
                $cSlabInfo->bus_operator_id=$opr_id;
                $cSlabInfo->starting_fare=$slab_data['startingFare'];
                $cSlabInfo->upto_fare = $slab_data['uptoFare'];
                $cSlabInfo->odbus_commision=$slab_data['odbusCommision'];
                $cSlabInfo->from_date=$slab_data['from_date'];
                $cSlabInfo->to_date=$slab_data['to_date'];
                $cSlabInfo->created_by=$data['created_by'];
                $cSlabInfo->status=1;
                $cSlabInfo->save();            
            }

            return $cSlabInfo;
        // }
        //  else
        // {
        //      return 'Operator Already Exist';
        // }
       
    }

    /**
     * Update cancellationSlab
     *
     * @param $data
     * @return cancellationSlab
     */
    public function update($data, $id)
    { 
        $cSlab = $this->cancellationSlab->findOrFail($id);
        $cSlab=$this->getModel($data,$cSlab);
        $cSlab->update();

        $cancellationSlabInforecord=$this->cancellationSlabInfo->where('cancellation_slab_id',$id);
        $cancellationSlabInforecord->delete();

        $cSlabDetails=[];
        $slabs=$data['slabs'];
        foreach($slabs as $slab_data)
        {
            $cSlabInfo=new CancellationSlabInfo();
            $cSlabInfo->duration=$slab_data['duration'];
            $cSlabInfo->deduction=$slab_data['deduction'];
            $cSlabInfo->created_by = $data['created_by'];
            $cSlabInfo->status='1';
            $cSlabDetails[]=$cSlabInfo;
        }
        $cSlab->SlabInfo()->saveMany($cSlabDetails);


        return $cSlab;
    }

  
    public function deleteticketFareSlab($id)
    {
        return $this->ticketFareSlab::where('id', $id)->delete(); //->update(['status'=> 2]);
    }

    public function changeStatusticketFareSlab($id)
    {
        $cSlab = $this->ticketFareSlab->where('bus_operator_id',$id)->where('status','!=',2)->get();
            if($cSlab[0]->status ==0)
            {
                $status = 1;
               
            }
            elseif($cSlab[0]->status ==1){
                $status = 0;
            }
        return $this->ticketFareSlab::where('bus_operator_id', $id)->update(['status'=>$status]);
            
       
            
       
       
    }

}