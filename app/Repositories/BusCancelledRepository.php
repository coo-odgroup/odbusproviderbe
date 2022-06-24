<?php

namespace App\Repositories;
use App\Models\Bus;
use App\Models\BusCancelled;
use App\Models\BusCancelledDate;
use App\Models\BusOperator;
use App\Models\BusStoppage;
use App\Models\Location;
use App\Models\Booking;
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
    protected $Booking;
    public function __construct(BusCancelled $busCancelled,Bus $bus,BusOperator $busOperator,BusStoppage $busStoppage,Location $location,BusCancelledDate $busCancelledDate,Booking $Booking)
    {
        $this->busCancelled = $busCancelled;
        $this->bus = $bus;
        $this->busOperator = $busOperator;
        $this->busStoppage = $busStoppage;
        $this->location = $location;
        $this->busCancelledDate = $busCancelledDate;
        $this->Booking = $Booking;
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
        $bus_id = $request['bus_id'] ;
        $source_id= $request['source_id'];      
        $destination_id= $request['destination_id'];      
        $toDate= $request['toDate'];      
        $fromDate= $request['fromDate'];      
        $bus_operator_id= $request['bus_operator_id'];  

        $data= $this->busCancelled->with('bus.busOperator','bus.busstoppage','busCancelledDate')
                                  ->with('bus.ticketPrice')
                                  ->orderBy('id','DESC')
                                  ->whereNotIn('status', [2]);

        if($request['USER_BUS_OPERATOR_ID']!="")
        {
            $data=$data->where('bus_operator_id',$request['USER_BUS_OPERATOR_ID']);
        }

        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }
        if($bus_operator_id!= null)
        {
            $data = $data->where('bus_operator_id',$bus_operator_id);
        }

        if($bus_id!= null)
        {
            $data = $data->where('bus_id',$bus_id);
        }

       

        if($toDate!= null && $fromDate!=null)
        {            
            $dt =[];
            $dt[1]= $fromDate ;
            $dt[2]=$toDate;
            if($fromDate==$toDate){
                    $data = $data->whereHas('busCancelledDate', function ($query) use ($dt )
                            {$query->where('cancelled_date', $dt[1]);
                            });
            }else{
                $data = $data->whereHas('busCancelledDate', function ($query) use ($dt )
                            {$query->whereBetween('cancelled_date', [$dt[1], $dt[2]]);
                            });
            }            
        }
        else
        {
            $c_dt = [];
            $c_dt[1] = date('Y-m-d');
            $data = $data->whereHas('busCancelledDate', function ($query) use ($c_dt)
                                    {$query->where('cancelled_date', $c_dt[1]);
                                    });
        }

        if($source_id!=null && $destination_id!=null )
        {
            $loc = [];
            $loc[1]=$source_id;
            $loc[2]=$destination_id;

             $data = $data->whereHas('bus.ticketPrice', function ($query) use ($loc)
                         {$query->where('source_id',$loc[1] )
                                ->where('destination_id',$loc[2] );});
            
        } 
        
        $data = $data->paginate($paginate);
        // log::info($data);
        // exit;
        
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

    public function getByBusId($id)
    {
        $data =  $this->busCancelled
                      ->with(['busCancelledDate'=> function($b){$b->orderBy('id','DESC')->limit(30);}])
                      ->whereHas('busCancelledDate', function ($query) {$query->where('cancelled_date','>=',date('Y-m-d') );})
                      ->where('bus_id', $id)->get();
                // log::info($data);
                      

        return $data;
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
            $this->busCancelled->status = 1;
            $this->busCancelled->month = $data['month'];
            $this->busCancelled->year = $data['year'];
            $this->busCancelled->reason = $data['reason'];
            $this->busCancelled->other_reson = $data['other_reson'];
            $this->busCancelled->bus_id = $bus['bus_id'];
           // $this->busCancelled = (Object) $bus;
            $this->busCancelled->save();

            $busCanceledDateModels = [];
            foreach ($bus['dateLists'] as $busDateLists)   
            {
                    if(isset($busDateLists['datechecked']) && $busDateLists['datechecked'] == true)
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

    public function busCancelledbyowner($data)
    { 
        $buses = $data['buses'];
        $message=[];
        foreach ($buses as $k) 
        {
            foreach ($k['dateLists'] as $i => $DateLists)   
            {
                if(isset($DateLists['datechecked']) && $DateLists['datechecked'] == true)
                {
                    $newDt = date("Y-m-d", strtotime($DateLists['entryDates']));
                    $checkDt = $this->Booking->where('bus_id',$k['bus_id'])
                                        ->where('journey_dt',$newDt)
                                        ->where('status',1)
                                        ->get();

                  if(count($checkDt) > 0){
                    $message['msg'] = 'Some seat already booked on';
                    $message['dt'] = $DateLists['entryDates'];

                    return $message;
                  }
                }
            }
        }
     
             foreach ($buses as $bus)         
        {          
            $this->busCancelled = new BusCancelled();
            $this->busCancelled->bus_operator_id = $data['bus_operator_id'];   
            $this->busCancelled->created_by = $data['cancelled_by'];
            $this->busCancelled->status = 1;
            $this->busCancelled->month = $data['month'];
            $this->busCancelled->year = $data['year'];
            $this->busCancelled->reason = $data['reason'];
            $this->busCancelled->other_reson = $data['other_reson'];
            $this->busCancelled->bus_id = $bus['bus_id'];
            $this->busCancelled->save();

            $busCanceledDateModels = [];
            foreach ($bus['dateLists'] as $busDateLists)   
            {
                    if(isset($busDateLists['datechecked']) && $busDateLists['datechecked'] == true)
                    {
                        $busCanceledDate = new BusCancelledDate();
                        $busCanceledDate->cancelled_date = date('Y-m-d',strtotime($busDateLists['entryDates'])) ;
                        $busCanceledDate->created_by = $data['cancelled_by'] ;
                        $busCanceledDateModels[] =  $busCanceledDate;
                    }

            }
            
            $this->busCancelled->busCancelledDate()->saveMany($busCanceledDateModels);
            $message['msg']  = 'Bus Cancellation Record Added';
        }
        return $message;
     
       
       
    }
    /**
     * Update Cancelled Bus
     *
     * @param $data
     * @return busCancelled
     *///////
    public function update($data, $id)
    {
            $buses = $data['buses'];
            foreach ($buses as $bus)         
            { 
                $this->busCancelled = $this->busCancelled->find($id);
        
                $this->busCancelled->bus_operator_id = $data['bus_operator_id'];   
                $this->busCancelled->created_by = $data['cancelled_by'];
                $this->busCancelled->status = 1;
                $this->busCancelled->reason = $data['reason'];
                $this->busCancelled->other_reson = $data['other_reson'];
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