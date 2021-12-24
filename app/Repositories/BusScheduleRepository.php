<?php

namespace App\Repositories;

use App\Models\BusSchedule;
use App\Models\BusScheduleDate;
use App\Models\Location;
use App\Models\Bus;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class BusScheduleRepository
{
    protected $busSchedule;
    protected $busScheduleDate;
    protected $location;
    protected $bus;
    
    public function __construct(BusSchedule $busSchedule,Location $location,Bus $bus,BusScheduleDate $busScheduleDate)
    {
        $this->busSchedule = $busSchedule;
        $this->location = $location;
        $this->bus = $bus;
        $this->busScheduleDate = $busScheduleDate;
    }
    /**
     * Get All bus Schedule in Data Table
     *
     * @param $id
     * @return mixed
     */
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
        // Total records
        $totalRecords=$this->busSchedule->whereNotIn('status', [2])->count();
        $totalRecordswithFilter=$this->busSchedule->with('busScheduleDate')->with('bus.busOperator','bus.busstoppage')  
        ->whereHas('bus', function ($query) use ($searchValue){
               $query->where('name', 'like', '%' .$searchValue . '%');               
           })->whereNotIn('status', [2])->count();
        
        $busRecords =  $this->busSchedule->with('busScheduleDate')->with('bus.busOperator','bus.busstoppage')
        ->orderBy($columnName,$columnSortOrder)
        ->whereHas('bus', function ($query) use ($searchValue){
         $query->where('name', 'like', '%' .$searchValue . '%')
         ->groupBy('bus_id');                   
     })       

        ->skip($start)
        ->take($rowperpage)
        ->whereNotIn('status', [2])
        ->get();
         $data_arr = array();
         $bus_stoppage = array();
         

         foreach($busRecords as $key=>$busRecord){

              $dateRecord = $busRecord->busScheduleDate;
              $name = $busRecord->bus->name;
              $name = $name." >> ".$busRecord->bus->bus_number;
              $operatorName = $busRecord->bus->busOperator->operator_name;
              $bStoppages = $busRecord->bus->busstoppage;
              $data_arr[]=$busRecord->toArray();
                $data_arr[$key]['name']=$name;
                $data_arr[$key]['operatorName']=$operatorName;
              $stoppageName="";   
              $routesdata=""; 
              $entry_dates = array();
             foreach($dateRecord as $edate){
                $entryDates = $edate->entry_date; 
                $entry_dates[] = array(
                    "entryDates"=>date('j M Y ',strtotime($entryDates)),
                );
             }  
             $data_arr[$key]['entryDates']=$entry_dates;
            
            $sourceId = $bStoppages[0]->source_id;
                $destinationId = $bStoppages[0]->destination_id;
                $stoppageName = $this->location->whereIn('id', array($sourceId, $destinationId))->orderBy('id','ASC')->get('name');
                $bus_stoppage[] = array(
                    "sourceName" => $stoppageName,
                    "destinationName" => $stoppageName,
                );
                $routesdata =  $stoppageName[1]['name']."-".$stoppageName[0]['name'];
             $data_arr[$key]['routes']=$routesdata; 
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
     * Get All bus Schedule
     *
     * @param $id
     * @return mixed
     */
    public function getAll()
    {
        return $this->busSchedule->get();
    } 

    public function busScheduleById($id)
    {
        $data = $this->busSchedule->with('busScheduleDate')
                                  ->where('bus_id',$id)
                                  ->where('status',1)
                                  ->get();
        // log::info($data);
        return $data;
    }


    public function busSchedulerData($request)
    {
      // Log::info($request);
          $paginate = $request['rows_number'] ;
         $name = $request['name'] ;     

        $data= $this->busSchedule->with('busScheduleDate','bus.busOperator','bus.ticketPrice')
                                 ->whereNotIn('status', [2])
                                 ->orderBy('id','DESC');

        if($request['USER_BUS_OPERATOR_ID']!="")
        {
            $data=$data->whereHas('bus', function ($query) use ($request){
               $query->where('bus_operator_id', $request['USER_BUS_OPERATOR_ID']);               
           });
        }

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
            $data=$data->Where('created_by', 'like', '%' .$name . '%')
                       ->orwhereHas('bus.busOperator', function ($query) use ($name) {$query->where('operator_name', 'like', '%' .$name . '%' );})                        
                       ->orWhereHas('bus', function ($query) use ($name) {$query->where('name','like', '%' .$name . '%');})
                       ->orWhereHas('bus', function ($query) use ($name) {$query->where('bus_number','like', '%' .$name . '%' );});
        }     

        $data=$data->paginate($paginate);
        


        if(count($data) > 0){
            foreach($data as $key=>$v){    
                if(count($v->bus['ticketPrice'])>0)
                {
                     $v['from_location']=$this->location->where('id', $v->bus['ticketPrice'][0]['source_id'])->get();
                    $v['to_location']=$this->location->where('id',$v->bus['ticketPrice'][0]['destination_id'])->get();  
                }                     
            }
        }
        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
           return $response;
    }
    /**
     * Get bus Schedule by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->busSchedule ->where('id', $id)->get();
    }

    /**
     * Save Schedule
     *
     * @param $data
     * @return busschedule
     */
    public function save($data)
    {
        // log::info($data);
        // log::info(date("Y-m-d"));
        // exit;
        if($data['entry_date'] >= date("Y-m-d"))
        {
            $duplicate_data = $this->busSchedule
                               ->where('bus_id',$data['bus_id'])
                               ->where('status','!=',2)
                               ->get();
        if(count($duplicate_data)==0)
        {
        $this->bus = $this->bus->find($data['bus_id']);
        $this->bus->running_cycle = $data['running_cycle'];
        $this->bus->update();   
        $this->busSchedule->bus_id= $data['bus_id'];
        $this->busSchedule->running_cycle= $data['running_cycle'];
        $this->busSchedule->created_by= $data['created_by'];
        $this->busSchedule->status = 0;
        $this->busSchedule->save();
        $busScheduleDateModels = [];
        $entryDate = $data['entry_date'];
        $busScheduleDate= new BusScheduleDate();
        $busScheduleDate->bus_schedule_id=$this->busSchedule->id;
        for($dateCount=0;$dateCount<30;$dateCount++) { 
           
            $busScheduleDate= new BusScheduleDate();
            $busScheduleDate->bus_schedule_id = $this->busSchedule->id;
            if($dateCount!=0)
            {
                $entryDate = strtotime("+".$data['running_cycle']."day", strtotime($entryDate));
            }
            else
            {
                 $entryDate =strtotime($entryDate);
            }                
            $entryDate = date("Y-m-d", $entryDate);
            $busScheduleDate->entry_date=$entryDate;
            $busScheduleDate->created_by =$data['created_by'];
            $busScheduleDate->status = 1;
            $busScheduledateModels[] =  $busScheduleDate;
        }        
        $this->busSchedule->busScheduleDate()->saveMany($busScheduledateModels);
        return $busScheduledateModels; 
        }
        else
        {
            return 'Bus Schedule Already Exist';
        }

        }
        else
        {
            return 'Can Not Add Old Date';
        }
        
   
     }

    /**
     * Update Schedule
     *
     * @param $data
     * @return busschedule
     */
    public function update($data, $id)
    { 

        if($data['entry_date'] >= date("Y-m-d"))
        {
            $this->bus = $this->bus->find($data['bus_id']);
            $this->bus->running_cycle = $data['running_cycle'];
            $this->bus->update(); 
            $this->busSchedule = $this->busSchedule->find($id);
            $this->busSchedule->running_cycle = $data['running_cycle'];
            $this->busSchedule->update(); 
            //TOD Latter,Write Enhanced Query
            $this->busSchedule->BusScheduleDate()->delete();  
            $busScheduleDateModels = [];
            $entryDate = $data['entry_date'];
            $busScheduleDate= new BusScheduleDate();
            $busScheduleDate->bus_schedule_id=$this->busSchedule->id;
            $busScheduleDate->entry_date=$entryDate;
            for($dateCount=0;$dateCount<30;$dateCount++) {   
                $busScheduleDate= new BusScheduleDate();
                $busScheduleDate->bus_schedule_id = $this->busSchedule->id;
                if($dateCount!=0)
                {
                    $entryDate = strtotime("+".$data['running_cycle']."day", strtotime($entryDate));
                }
                else
                {
                     $entryDate =strtotime($entryDate);
                }                      
                $entryDate = date("Y-m-d", $entryDate);
                $busScheduleDate->entry_date=$entryDate;
                $busScheduleDate->created_by =$data['created_by'];
                $busScheduleDate->status = 1;
                $busScheduledateModels[] =  $busScheduleDate;
            }        
            $this->busSchedule->busScheduleDate()->saveMany($busScheduledateModels);
            return $busScheduledateModels;
        }
        else
        {
            return 'Can Not Add Old Date';
        }
        
    }
     
    public function delete($id)
    {
        if($this->busSchedule->where('id', $id)->exists()) 
        {
            $this->busScheduleDate->where("bus_schedule_id", $id)->delete();
            $busschedule = $this->busSchedule->find($id); 
            $busschedule->status = 2;
            $busschedule->update();
            return $busschedule;
        }
    }
    public function changeStatus($id)
    {
        $post = $this->busSchedule->find($id);
        if($post->status==0){
            $post->status = 1;
        }elseif($post->status==1){
            $post->status = 0;
        }
        $post->update();
        return $post;
    }

}