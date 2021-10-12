<?php

namespace App\Repositories;

use App\Models\BusOperator;
use App\Models\BusStoppage;
use App\Models\Location;
use App\Models\BusCancelled;
use Illuminate\Support\Facades\Log;

class BusOperatorRepository
{
    /**
     * @var BusOperators
     */
    protected $busOperators;
    protected $location;
    protected $busCancelled;
    /**
     * BusOperatorRepository constructor.
     *
     * @param BusOperator $amenities
     */
    public function __construct(BusOperator $busOperators,Location $location,BusCancelled $busCancelled)
    {
        $this->busOperators = $busOperators;
        $this->location = $location;
        $this->busCancelled = $busCancelled;
    }
    public function getAll()
    {
        return $this->busOperators->whereNotIn('status', [2])->get();
    }
    public function BusbyOperatorData($request)
    {
        // return $this->busOperators->whereNotIn('status', [2])->get();
         $paginate = $request['rows_number'] ;
         $name = $request['name'] ;
       

        $data= $this->busOperators->whereNotIn('status', [2])
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
             $data = $data->where('email_id','like', '%' . $name . '%')
                     ->orWhere('operator_name','like', '%' . $name . '%')
                     ->orWhere('contact_number','like', '%' . $name . '%');
                  
        } 
      

        $data=$data->paginate($paginate);

        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
           return $response;

    }
    public function getDatatable($request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        if(!is_numeric($rowperpage))
        {
            $rowperpage=Config::get('constants.ALL_RECORDS');
        }
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value
        
        $totalRecords = $this->busOperators->select('COUNT(*) as allcount')->whereNotIn('status', [2])->count();
        $totalRecordswithFilter = $this->busOperators
        ->where('operator_name', 'like', "%" .$searchValue . "%")
        ->Where('status','!=','2')
        ->count();
        $records = $this->busOperators->orderBy($columnName,$columnSortOrder)
            ->where('operator_name', "like", "%" .$searchValue . "%")
            ->Where('status','!=','2')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach($records as $key=>$record)
        {
            $data_arr[]=$record->toArray();
            $data_arr[$key]['created_at']=date('j M Y h:i a',strtotime($record->created_at));
            $data_arr[$key]['updated_at']=date('j M Y h:i a',strtotime($record->updated_at));
        } 
            
        
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        ); 
        return ($response);
    }
    /**
     * Get amenities by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->busOperators
            ->where('id', $id)
            ->get();
    }
    public function getModel($data, BusOperator $busOperators)
    {
        $busOperators->email_id = $data['email_id'];
        $busOperators->password = $data['password'];
        $busOperators->operator_name = $data['operator_name'];
        $busOperators->contact_number = $data['contact_number'];
        $busOperators->organisation_name = $data['organisation_name'];
        $busOperators->location_name = $data['location_name'];
        $busOperators->address = $data['address'];
        $busOperators->operator_info = $data['operator_info'];
        $busOperators->additional_email = $data['additional_email'];
        $busOperators->additional_contact = $data['additional_contact'];
        $busOperators->bank_account_name = $data['bank_account_name'];
        $busOperators->bank_name = $data['bank_name'];
        $busOperators->bank_ifsc = $data['bank_ifsc'];
        $busOperators->bank_account_number = $data['bank_account_number'];

        $busOperators->need_gst_bill = $data['need_gst_bill'];
        $busOperators->gst_number = $data['gst_number'];
        $busOperators->gst_amount = $data['gst_amount'];

        $busOperators->created_by = $data['created_by'];
        return $busOperators;
    }
    public function getOperatorEmail($data)
    {
        $email_id=$data['emailid'];
        $id=$data['operator_id'];
        if($id=="")
        {

           $result= $this->busOperators->where('email_id','=',$email_id)->get();
           return count($result);    
        }
        else
        {
            $result= $this->busOperators
            ->where('email_id','=',$email_id)
            ->where('id','!=',$id)
            ->get();    
            return count($result);   
        }
        return $return;
        

    }

    public function getOperatorPhone($data)
    {
        $contact_number=$data['contact_number'];
        $id=$data['operator_id'];
        if($id=="")
        {

           $result= $this->busOperators->where('contact_number','=',$contact_number)->get();
           return count($result);    
        }
        else
        {
            $result= $this->busOperators
            ->where('contact_number','=',$contact_number)
            ->where('id','!=',$id)
            ->get();    
            return count($result);   
        }
        return $return;
        

    }
    public function save($data)
    {
        $busOperators = new $this->busOperators;
        $busOperators=$this->getModel($data,$busOperators);
        $busOperators->save();
        return $busOperators;
    }

    public function update($data, $id)
    {
        $busOperators = $this->busOperators->find($id);
        $busOperators=$this->getModel($data,$busOperators);
        $busOperators->update();
        return $busOperators;
    }

    public function delete($id)
    {
        $post = $this->busOperators->find($id);
        $post->status = 2;
        $post->update();
        return $post;
    }
    public function changeStatus($id)
    {
        $post = $this->busOperators->find($id);
        if($post->status==0){
            $post->status = 1;
        }elseif($post->status==1){
            $post->status = 0;
        }
        $post->update();
        return $post;
    } 
    public function getBusbyOperator($operatorId)
    {

        $operatorWithBuses =  $this->busOperators->with('bus.busstoppage')
     
         ->where('id', $operatorId)
         
         ->get('id');

        // Log::info($operatorWithBuses);

         $busData = array();
         foreach($operatorWithBuses as $operatorWithBus){
             $buses = $operatorWithBus->bus;
             Log::info($buses);
            foreach($buses as $bus)
            {
                //if($bus->status!='1')continue;
                $bStoppages = $bus->busstoppage;
                if(count($bStoppages)==0)continue;
                //Log::info($bStoppages);
                foreach($bStoppages as $bStoppage){
                    Log::info("inner Loop");
                    $sourceId = $bStoppage->source_id;
                    $destinationId = $bStoppage->destination_id;
                    $stoppageName = $this->location->whereIn('id', array($sourceId, $destinationId))->get('name');
                } 
                $busData[] = array(
                    "id" => $bus->id,
                    "name" => $bus->name."[".$bus->bus_number."]"."[".$stoppageName[0]['name']."-".$stoppageName[1]['name']."]"  
                );  
                Log::info($busData);
            }          
            
         }
         Log::info($busData);
         return $busData;

    }

}