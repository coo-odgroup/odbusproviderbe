<?php

namespace App\Repositories;
use App\Models\Bus;
use App\Models\BusCancelled;
use App\Models\BusCancelledDate;
use App\Models\BusOperator;
use App\Models\BusStoppage;
use App\Models\Location;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class BusCancelledRepository
{
    
    protected $busCancelled;
    protected $busCancelledDate;
    protected $bus;
    protected $busOperator;
    protected $busStoppage;
    protected $location;
    public function __construct(BusCancelled $busCancelled,Bus $bus,BusOperator $busOperator,BusStoppage $busStoppage,Location $location,BusCancelledDate $busCancelledDate)
    {
        $this->busCancelled = $busCancelled;
        $this->bus = $bus;
        $this->busOperator = $busOperator;
        $this->busStoppage = $busStoppage;
        $this->location = $location;
        $this->busCancelledDate = $busCancelledDate;
    }
    /**
     * Get Bus Cancelled List
     *
     * @param $id
     * @return mixed
     */
    public function getAll()
    {
        return $this->busCancelled->whereNotIn('status', [2])->get();
    }
    /**
     * Get Bus Cancelled List in Datatable Format
     *
     * @param $id
     * @return mixed
     */
    public function getBusCancelledDT($request)
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
        $totalRecords=$this->busCancelled->whereNotIn('status', [2])->count();
        
        $totalRecordswithFilter=$this->busCancelled->with('bus.busOperator','bus.busstoppage')  
        ->whereHas('bus', function ($query) use ($searchValue){
               $query->where('name', 'like', '%' .$searchValue . '%');               
           })->whereNotIn('status', [2])->count();
       // Fetch records
    
       $busRecords =  $this->busCancelled->with('busCancelledDate')->with('bus.busOperator','bus.busstoppage')
       ->orderBy($columnName,$columnSortOrder)
       ->whereHas('bus', function ($query) use ($searchValue){
        $query->where('name', 'like', '%' .$searchValue . '%');                   
    })
       ->skip($start)
       ->take($rowperpage)
       ->whereNotIn('status', [2])
       ->get();
       
        $data_arr = array();
        $bus_stoppage = array();
        $cancel_dates = array();
        foreach($busRecords as $key=>$busRecord)
        {
             $dateRecord = $busRecord->busCancelledDate;
             $name = $busRecord->bus->name;
             $name = $name." >> ".$busRecord->bus->bus_number;
             $operatorName = $busRecord->bus->busOperator->operator_name;
             $bStoppages = $busRecord->bus->busstoppage;
                $data_arr[]=$busRecord->toArray();
                $data_arr[$key]['name']=$name;
                $data_arr[$key]['operatorName']=$operatorName;
                $stoppageName="";   
                $routesdata="";  
                $cancel_dates = array();
                foreach($dateRecord as $cdate){
                    $cancelledDates = $cdate->cancelled_date; 
                    $cancel_dates[] = array(date('j M Y ',strtotime($cancelledDates)));
                }
                $data_arr[$key]['cancelledDates']=$cancel_dates;
            foreach($bStoppages as $bStoppage){                          
                $sourceId = $bStoppage->source_id;
                $destinationId = $bStoppage->destination_id;
                $stoppageName = $this->location->whereIn('id', array($sourceId, $destinationId))->get('name');
                $bus_stoppage[] = array(
                    "sourceName" => $stoppageName,
                    "destinationName" => $stoppageName,
                );
                $routesdata =  $stoppageName[0]['name']."-".$stoppageName[1]['name'];
            } 
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

    public function busCancelledData($request)
    {
        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;

        $data= $this->busCancelled->with('bus.busOperator','bus.busstoppage')
                    ->whereNotIn('status', [2]);


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
            $data = $data->WhereHas('bus', function ($query) use ($name){
                            $query->where('name', 'like', '%' .$name . '%');
                            })
                          ->orWhereHas('bus', function ($query) use ($name){
                            $query->where('bus_number', 'like', '%' .$name . '%');
                            })
                         ->orWhereHas('bus.busOperator', function ($query) use ($name){
                            $query->where('operator_name', 'like', '%' .$name . '%');
                            });                        
        }     

        $data=$data->paginate($paginate);

        // Log::info($data['bus']['busstoppage']);

        // if($data){
        //     foreach($data as $key=>$v){
        //             Log::info($v->bus->bus_operator);
        //         // foreach ($v->bus->bus_stoppage as $e) {
        //         //     Log::info($e);
                    
        //         // }
        //         }
        //     }


        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
           return $response;  
    }
    /**
     * Get Bus Cancelled by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->busCancelled ->where('id', $id)->get();
    }

    /**
     * Save Cancelled Bus
     *
     * @param $data
     * @return busCancelled
     */
   
    public function save($data)
    { 
        $buses = $data['buses'];
        foreach ($buses as $bus)         
        {          
            $this->busCancelled = new BusCancelled();
            $this->busCancelled->bus_operator_id = $data['bus_operator_id'];   
            $this->busCancelled->created_by = $data['cancelled_by'];
            $this->busCancelled->status = 0;
            $this->busCancelled->month = $data['month'];
            $this->busCancelled->year = $data['year'];
            $this->busCancelled->reason = $data['reason'];
            $this->busCancelled->bus_id = $bus['bus_id'];
           // $this->busCancelled = (Object) $bus;
            $this->busCancelled->save();

            $busCanceledDateModels = [];
            foreach ($bus['dateLists'] as $busDateLists)   
            {
                    if($busDateLists['datechecked'])
                    {
                        $busCanceledDate = new BusCancelledDate();
                        $busCanceledDate->cancelled_date = date('Y-m-d',strtotime($busDateLists['entryDates'])) ;
                        $busCanceledDate->created_by = $data['cancelled_by'] ;
                        $busCanceledDateModels[] =  $busCanceledDate;
                    }
            }
            $this->busCancelled->busCancelledDate()->saveMany($busCanceledDateModels);
        }
        return $buses;
       
    }
    /**
     * Update Cancelled Bus
     *
     * @param $data
     * @return busCancelled
     *///////
    public function update($data, $id)
    {
        Log::info($data);
            $buses = $data['buses'];
            foreach ($buses as $bus)         
            { 
                $this->busCancelled = $this->busCancelled->find($id);
        
                $this->busCancelled->bus_operator_id = $data['bus_operator_id'];   
                $this->busCancelled->created_by = $data['cancelled_by'];
                $this->busCancelled->status = 0;
                $this->busCancelled->reason = $data['reason'];
                $this->busCancelled->bus_id = $bus['bus_id'];
                $this->busCancelled->update();
                $this->busCancelledDate->where('bus_cancelled_id',$id)->delete();
                $busCanceledDateModels = [];
                foreach ($bus['dateLists'] as $busDateLists)   
                {
                    if($busDateLists['datechecked'])
                    {
                        $busCanceledDate = new BusCancelledDate();
                        $busCanceledDate->cancelled_date = date('Y-m-d',strtotime($busDateLists['entryDates'])) ;
                        $busCanceledDate->created_by = $data['cancelled_by'] ;
                        $busCanceledDateModels[] =  $busCanceledDate;
                    }
                }
                $this->busCancelled->busCancelledDate()->saveMany($busCanceledDateModels);
            }
        
        
            return $buses;

    }
    /**
     * Update Cancelled Bus
     *
     * @param $data
     * @return busCancelled
    */
    public function delete($id)
    {
        if($this->busCancelled->where('id', $id)->exists()) 
        {
            $this->busCancelledDate->where("bus_cancelled_id", $id)->delete();
            $buscancel = $this->busCancelled->find($id); 
            $buscancel->status = 2;
            $buscancel->update();
            return $buscancel;
        }

    }
    public function changeStatus($id)
    {
        $buscancel = $this->busCancelled->find($id);
        if($buscancel->status==0){
            $buscancel->status = 1;
        }elseif($buscancel->status==1){
            $buscancel->status = 0;
        }
        $buscancel->update();
        return $buscancel;
    }
    
}