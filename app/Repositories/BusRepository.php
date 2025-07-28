<?php
namespace App\Repositories;
use App\Models\Bus;
use App\Models\BusSchedule;
use App\Models\BusScheduleDate;
use App\Models\BusType;
use App\Models\BusSitting;
use App\Models\BusStoppage;
use App\Models\BusAmenities;
use App\Models\Amenities;
use App\Models\Location;
use App\Models\User;
use App\Models\TicketPrice;
use App\Models\Coupon;
use App\Models\BusDisplayInfo;
use App\Models\cancelationSlab;
use App\Models\BoardingDroping;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use DB;
use App\Models\Seats;
class BusRepository
{
    protected $bus;
    protected $seats;
    protected $ticketPrice; 
    protected $coupon; 
    protected $BusDisplayInfo; 
    
    public function __construct(BusDisplayInfo $BusDisplayInfo,Bus $bus,BusSchedule $busSchedule, BusType $busType, BusSitting $busSitting,
     BusStoppage $busStoppage, BusAmenities $busAmenities, Amenities $amenities,
      BoardingDroping $boardingDroping, User $user,BusScheduleDate $busScheduleDate, Seats $seats, TicketPrice $ticketPrice,Location $location,Coupon $coupon)
    {
        $this->bus = $bus;
        $this->busSchedule = $busSchedule;
        $this->busType = $busType;
        $this->busSitting = $busSitting;
        $this->busStoppage = $busStoppage;
        $this->busAmenities = $busAmenities;
        $this->amenities = $amenities;
        $this->boardingDroping = $boardingDroping;
        $this->user = $user;
        $this->busScheduleDate = $busScheduleDate;
        $this->Seats = $seats;
        $this->ticketPrice=$ticketPrice;
        $this->location = $location;       
        $this->coupon = $coupon;       
        $this->BusDisplayInfo = $BusDisplayInfo;       
    }
    public function getAll()
    {
        $data = $this->bus->with('cancellationslabs','ticketPrice')
        ->orderBy('name','ASC')->where('status','1')
        ->get(); 

        if($data){
            foreach($data as $v){ 
             foreach($v->ticketPrice as $k => $a)
             {             
             
                $stoppages['source'][$k]=$this->location->where('id', $a->source_id)->get();
                $stoppages['destination'][$k]=$this->location->where('id', $a->destination_id)->get(); 
           }
               $v['from_location']=$stoppages['source'][0];
               $v['to_location']=$stoppages['destination'][0];
       }
   }
        return $data;

    }
    public function getByOperaor($id)
    {
       
        $data = $this->bus->with('cancellationslabs','ticketPrice')
        ->whereHas('ticketPrice', function ($query) 
                         {$query->where('status','!=', 2 );})
        ->where('bus_operator_id',$id)
        ->where('status','1')
        ->orderBy('name','ASC')
        ->get(); 

        if($data){
            foreach($data as $v){ 
                // $v['from_location']=[];
                // $v['from_location']=[];
               // if(count($v->ticketPrice) > 0){
                    foreach($v->ticketPrice as $k => $a)
                    {             
                    
                        $stoppages['source'][$k]=$this->location->where('id', $a->source_id)->get();
                        $stoppages['destination'][$k]=$this->location->where('id', $a->destination_id)->get(); 
                    }


                    $v['from_location']=$stoppages['source'][0];
                    $v['to_location']=$stoppages['destination'][0];
               

                //}
            }

   }
    return $data;
    }
    public function seatsBus($request)
    {         

        $busData=  $this->bus
         ->where('id' ,$request->bus_id)->where('status','!=',2)->get();  
        // Log::info($busData);

        $seatData=[];

        $lowerBerth=$this->Seats->with(['BusSeats' => function ($query){
            $query->where('status','!=',2);
         }])
        ->where('bus_seat_layout_id',$busData[0]->bus_seat_layout_id)
        ->where('berthType',1)
        ->where('status','!=',2)
        ->get();


        foreach($lowerBerth as $key=>$rows)
        {
            $row_data=$this->Seats
            ->where('rowNumber',$rows->rowNumber)
            ->where('berthType', '1')
            ->where('status','!=',2)
            ->where('bus_seat_layout_id', $busData[0]->bus_seat_layout_id)
            ->orderBy('colNumber')->get();
            $seatData['lowerBerth'][$rows->rowNumber]=$row_data;
        }

        $upperBerth=$this->Seats->with(['BusSeats' => function ($query){
            $query->where('status','!=',2);
         }])
        ->where('bus_seat_layout_id',$busData[0]->bus_seat_layout_id)
        ->where('berthType',2)
        ->where('status','!=',2)
        ->get();
        foreach($upperBerth as $key=>$rows)
        {
            $row_data=$this->Seats->where('rowNumber',$rows->rowNumber)
            ->where('berthType', '2')
            ->where('status','!=',2)
            ->where('bus_seat_layout_id', $busData[0]->bus_seat_layout_id)
            ->orderBy('colNumber')->get();
            $seatData['upperBerth'][$rows->rowNumber]=$row_data;
        }
        return $seatData;  
        
    }  
     public function locationBusss($request)
    {    
             $source_id = $request->source_id;
             $destination_id = $request->destination_id;
             $operator = $request->USER_BUS_OPERATOR_ID;
        $data = $this->bus->with('ticketPrice')->orderBy('name','ASC')
                          ->whereHas('ticketPrice', function ($query) use ($source_id,$destination_id){
                                        $query->where('source_id', 'like', '%' .$source_id . '%')
                                              ->where('destination_id', 'like', '%' .$destination_id . '%');               
                                    })
                          ->where('bus_operator_id', $operator)
                          ->where('status','1')
                          ->get(); 

            if($data){
                foreach($data as $v){ 
                 foreach($v->ticketPrice as $k => $a)
                 {          
                 
                    $stoppages['source'][$k]=$this->location->where('id', $a->source_id)->get();
                    $stoppages['destination'][$k]=$this->location->where('id', $a->destination_id)->get(); 
               }
                   $v['from_location']=$stoppages['source'][0];
                   $v['to_location']=$stoppages['destination'][0];
           }

       }
             return $data;        
        
    }
    public function getById($id)
    {
        return $this->bus
        ->with('cancelationSlab')
        ->where('id', $id)->get();
    } 

    public function busDisplayInfo()
    {
           $data=$this->BusDisplayInfo->get();
           $arr=[];
           foreach ($data as $key => $value) {
               $arr[]=$value->name;
           }
           return $arr;
    }

    public function getLocationBus($source_id,$destination_id)
    {
       
        $data = $this->bus->with('cancellationslabs','ticketPrice')
        ->orderBy('name','ASC')->whereHas('ticketPrice', function ($query) use ($source_id,$destination_id){
            $query->where('source_id', $source_id)->where('destination_id', $destination_id);               
        })
        ->where('status','1')
        ->get(); 
        

            if($data){
                foreach($data as $v){ 
                 foreach($v->ticketPrice as $k => $a)
                 {          
                 
                    $stoppages['source'][$k]=$this->location->where('id', $a->source_id)->get();
                    $stoppages['destination'][$k]=$this->location->where('id', $a->destination_id)->get(); 
               }
                   $v['from_location']=$stoppages['source'][0];
                   $v['to_location']=$stoppages['destination'][0];
           }

       }
        return $data;
    }
    public function updateBusName($data,$id)
    {
        $bus = $this->bus->find($id);
        $bus->bus_number = $data['bus_number'];
        $bus->update();
        return $bus;
    }
    public function updatesequence($data, $id)
    {
        $bus = $this->bus->find($id);
        $bus->sequence = $data['sequence'];
        $bus->update();
        return $bus;
    }
    public function getModel($data,Bus $bus)
    {       
        $bus->user_id = $data['user_id'];
        $bus->name = $data['name'];
        $bus->bus_operator_id = $data['bus_operator_id'];
        $bus->via = $data['via'];
        $bus->bus_number = strtoupper($data['bus_number']);
        $bus->bus_description = $data['bus_description'];
        $bus->bus_type_id = $data['bus_type_id'];
        $bus->max_seat_book = $data['max_seat_book'];
        $bus->bus_sitting_id = $data['bus_sitting_id'];
        $bus->bus_seat_layout_id = $data['bus_seat_layout_id'];
        $bus->running_cycle = "0";        
        $bus->has_return_bus ="0"; 
        $bus->type = $data['type'];       
        $bus->lower_sleeper_extra_fare = $data['lower_sleeper_extra_fare'];       
        $bus->cancelation_points = $data['cancelation_points'];      
        $bus->cancellationslabs_id = $data['cancellationslabs_id'];    
        $bus->created_by = $data['created_by'];   
        // $bus->cancellation_policy_desc= $data['cancellation_policy_desc']; 
        // $bus->travel_policy_desc= $data['travel_policy_desc'];     
        return $bus;
    }
    public function save($data)
    {
        $bus = new $this->bus;
        $bus = $this->getModel($data,$bus);
        $bus->save();
        
        $amentiesModel=[];
        $amenities=$data['amenities'];
        foreach($amenities as $amenitiyId)
        {
            $busAmenity=new BusAmenities();
            $busAmenity->bus_id=$bus->id;
            $busAmenity->amenities_id=$amenitiyId;
            $busAmenity->created_by =$data['created_by'];
            $busAmenity->status='1';
            $amentiesModel[]=$busAmenity;
        }
        $bus->busAmenities()->saveMany($amentiesModel);
        return $bus->id;
    }
    public function update($data, $id)
    {  
        $bus = $this->bus->find($id);
        $bus = $this->getModel($data,$bus);
        $bus->update();

        $amenitiesRecord=$this->busAmenities->where('bus_id',$id);
        $amenitiesRecord->delete();
        
        $amentiesModel=[];
        $amenities=$data['amenities'];
        foreach($amenities as $amenitiyId)
        {
            $busAmenity=new BusAmenities();
            $busAmenity->bus_id=$bus->id;
            $busAmenity->amenities_id=$amenitiyId;
            $busAmenity->created_by =$data['created_by'];
            $busAmenity->status='1';
            $amentiesModel[]=$busAmenity;
        }
        $bus->busAmenities()->saveMany($amentiesModel);


        return $bus;
    }    
    public function delete($id)
    {
        $bus = $this->bus->find($id);
        $bus->status = 2;
        $bus->update();
        return $bus;
    }
    
    public function getAllBusDT( $request)
    {
      
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length");
        if(!is_numeric($rowperpage))
        {
            $rowperpage=Config::get('constants.ALL_RECORDS');
        }
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column'];
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $order_arr[0]['dir'];
        $searchValue = $search_arr['value'];

        $totalRecords = $this->bus->where('status','!=','2')->count();
        $totalRecordswithFilter = $this->bus->where('name', 'like', '%' .$searchValue . '%')->where('status','!=','2')->count();

        $records = 
            $this->bus
            ->with('busAmenities')
            ->orderBy($columnName,$columnSortOrder)
            ->where('name', 'like', '%' .$searchValue . '%')
            ->where('status','!=','2')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = $records->toArray();
        
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        ); 
        return $response;
        
    }

    public function busSeatsFareData( $request)
    {
        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;
        $user_role = $request['user_role'] ;
        $user_id = $request['user_id'] ;
        $data= $this->bus->with('ticketPrice')->whereNotIn('status', [2])->orderBy('updated_at','DESC');

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

        if($name!=null)
        {
            $data = $data->where('name', 'like', '%' .$name . '%') ;
                         // ->orWhere('bus_number', 'like', '%' .$name . '%')
                         // ->orWhere('via', 'like', '%' .$name . '%');
                                             
        }    
        if($user_role==5)
        {
            $data= $data->where('user_id',$user_id);   
        } 

        $data=$data->paginate($paginate);

         if(count($data)>0){
            foreach($data as $key=>$v)
            {   
                if(count($v->ticketPrice)>0)
                {
                   $start = $v->ticketPrice[0]['source_id'];
                    $end = $v->ticketPrice[0]['destination_id'];
                    // Log::info($start)  ;exit;
                    if($start!="" && $end !="")
                    {
                         $v['from_location']=$this->location->where('id',$start)->get();
                         $v['to_location']=$this->location->where('id',$end)->get();
                    } 
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

    public function busseatfarereport( $request)
    {
        // log::info($request);
        $paginate = $request['rows_number'] ;
        $bus_operator_id = $request['bus_operator_id'] ;
        $start_date = $request['rangeFromDate'] ;
        $end_date = $request['rangeToDate'] ;

        $data= $this->bus->with(['ticketPrice.getBusSeats'=> function($b){$b->where('new_fare','>',0 )->whereNotIn('status', [2])->with('seats');}])
                        // ->with('')
                         ->whereHas('ticketPrice.getBusSeats', function ($query)  
                            {$query->where('new_fare','>',0 )->whereNotIn('status', [2])->orderBy('updated_at','DESC');
                        })
                         ->whereNotIn('status', [2])
                         ->orderBy('updated_at','DESC');
        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }

        if(!empty($bus_operator_id))
        {
            $data= $data->where('bus_operator_id',$bus_operator_id);   
        }  

        if(!empty($start_date) && !empty($end_date))
        {
            $data= $data->whereHas('busSeats', function ($query)  
                            {$query->whereBetween('updated_at', [$start_date, $end_date])
                                   ->orderBy('updated_at','DESC');
                        });   
        } 

        $data=$data->paginate($paginate);

         if(count($data)>0){
            foreach($data as $key=>$v)
            {   
                if(count($v->ticketPrice)>0)
                {
                    $start = $v->ticketPrice[0]['source_id'];
                    $end = $v->ticketPrice[0]['destination_id'];
                    if($start!="" && $end !="")
                    {
                         $v['from_location']=$this->location->where('id',$start)->get();
                         $v['to_location']=$this->location->where('id',$end)->get();
                    }
                    foreach ($v->ticketPrice as $y => $ve) {
                          $ve['source']=$this->location->where('id',$ve->source_id)->get();
                          $ve['destination']=$this->location->where('id',$ve->destination_id)->get();
                    }
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


    public function busupdatesequenceData( $request)
    {
        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;

        $data= $this->bus->with('busOperator','ticketPrice')->whereNotIn('status', [2])->orderBy('id','DESC');
      

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
            $data = $data->where('name', 'like', '%' .$name . '%')
                         ->orWhere('bus_number', 'like', '%' .$name . '%')
                         ->orWhere('via', 'like', '%' .$name . '%');
                                             
        }     

        $data=$data->paginate($paginate);
         // log::info($data);exit; 
        if(count($data)>0){
            foreach($data as $key=>$v)
            {   
                if(count($v->ticketPrice)>0)
                {
                   $start = $v->ticketPrice[0]['source_id'];
                    $end = $v->ticketPrice[0]['destination_id'];
                    // Log::info($start)  ;exit;
                    if($start!="" && $end !="")
                    {
                         $v['from_location']=$this->location->where('id',$start)->get();
                         $v['to_location']=$this->location->where('id',$end)->get();
                    } 
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

   
    public function BusData( $request)
    {
        // Log::info($request);

        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;   
        $user_role = $request['user_role'] ;
        // $user_id = $request['user_id'] ; 
        $operator= $request['operator'];   
        $status= $request['status'];   
        $source_id= $request['source_id'];   
        $destination_id= $request['destination_id'];   

        $data= $this->bus->with('busOperator','busstoppage','BusType','busAmenities.amenities','busSafety.safety','busContacts','busSeats.seats')
            ->with(['ticketPrice.getBusSeats' => function($query) {
                    $query->where('status','!=', 2 )->with('seats');}])
            ->whereHas('ticketPrice', function ($query) 
                         {$query->where('status','!=', 2 );})
            ->whereNotIn('status', [2])->orderBy('id','DESC');
                  

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
        // if($user_role!=null && $user_role==4)
        // {
        //     $data = $data->where('user_id',$user_id) ;   
        // }
        if($operator!=null)
        {
            $data = $data->where(
                function($query) use ($operator) {
                    $data = $query->orwhereHas('busOperator', function ($query) use ($operator)
                                        {$query->where('id',$operator );
            }); 
            })  ;   
        }
        if($source_id!=null && $destination_id!=null )
        {
            $loc = [];
            $loc[1]=$source_id;
            $loc[2]=$destination_id;

             $data = $data->whereHas('ticketPrice', function ($query) use ($loc)
                         {$query->where('source_id',$loc[1] )
                                ->where('destination_id',$loc[2] );});
            
        }
        if($status!=null)
        {
            if($status==1)
            {
                $data = $data->where(
                function($query) use ($name) {
                    $data = $query->where('status', 1);
                });    
            }
            if($status==0)
            {
                $data = $data->where(
                function($query) use ($name) {
                    $data = $query->where('status', 0);
                });    
            }
                
        }

        if($name!=null && $request['USER_BUS_OPERATOR_ID']=="")
        {
            $data = $data->where(
                function($query) use ($name) {
                    $data = $query->where('name', 'like', '%' .$name . '%')
                    ->orWhere('bus_number', 'like', '%' .$name . '%')
                    ->orWhere('via', 'like', '%' .$name . '%')
                    ->orWhere('created_by', 'like', '%' .$name . '%')
                    ->orwhereHas('busOperator', function ($query) use ($name)
                                {$query->where('organisation_name','like', '%' .$name . '%' );})
                   ->orwhereHas('busOperator', function ($query) use ($name)
                               {$query->where('operator_name', 'like', '%' .$name . '%');});
            });        
        } 
        else if($name!=null && $request['USER_BUS_OPERATOR_ID']!=""){
            $data = $data->where(
                function($query) use ($name) {
                    $data = $query->orWhere('bus_number', 'like', '%' .$name . '%');
            }); 
        }

        if($user_role==5)
        {
            $data= $data->where('user_id',$user_id);   
        }


        // if($name!=null)
        // {
            
        //     $data = $data->where('name', 'like', '%' .$name . '%')
        //                  ->orWhere('bus_number', 'like', '%' .$name . '%')
        //                  ->orWhere('via', 'like', '%' .$name . '%')
        //                  ->orWhere('created_by', 'like', '%' .$name . '%')
        //                  ->orwhereHas('busOperator', function ($query) use ($name)
        //                              {$query->where('organisation_name','like', '%' .$name . '%' );})
        //                 ->orwhereHas('busOperator', function ($query) use ($name)
        //                             {$query->where('operator_name', 'like', '%' .$name . '%');});                                             
        // }     

        $data=$data->paginate($paginate);
        ;
        
         // Log::info($data);

        if($data){
            foreach($data as $v){ 
               foreach($v->ticketPrice as $k => $a)
               {      
                $a['from_location']=$this->location->where('id', $a->source_id)->get();
                $a['to_location']=$this->location->where('id', $a->destination_id)->get(); 
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


    public function changeStatus($id)
    {
        $post = $this->bus->find($id);
        // Log::info($post);

        if($post->status==0)
        {
            $post->status = 1;
        }
        elseif($post->status==1)
        {
            $post->status = 0;
        }
        $post->update();
        return $post;
    }
    public function getBusbyBuschedule($busId)
    {
        $busWithBusSchedules =  $this->bus->with('busSchedule') 
        ->where('id', $busId)
        ->get('id');
        $busData = array();
        foreach($busWithBusSchedules as $busWithBusSchedule){
            $busSchedules = $busWithBusSchedule->busSchedule;
            foreach($busSchedules as $bSchedule)
            {
                $journeyDate = $bSchedule->journey_date; 
                $busData[] = array(
                    "cancelled_date" =>$journeyDate
                ); 
            }
        }
        return $busData;
    } 
    public function getBusScheduleEntryDates($busId)
    {
        $busWithEntryDates = $this->bus->with('busSchedule.busScheduleDate') 
        ->where('id', $busId)
        ->get('id');
        $busData = array();
        foreach($busWithEntryDates as $busWithEntryDate){
            $dateRecord = $busWithEntryDate->busSchedule->busScheduleDate;
            foreach($dateRecord as $dateRec)
            {
                $entryDate = $dateRec->entry_date; 
                $busData[] = array(
                    "entry_date"=>date('j M Y ',strtotime($entryDate)),
                ); 
            }
        }
        return $busData;
    }
    public function getBusScheduleEntryDatesFilter($data)
    {
        // Log::info($data);
        // exit;
        $searchByBus = $data['busLists'];
        $search = $data['year']."-".$data['month'];
        $busData = array();
        $today=date("Y-m-d");

        foreach($searchByBus as $busList){
            $busWithEntryDates = $this->bus->with('busstoppage','busCancelled.busCancelledDate')
            ->where('bus.id' , $busList)
            ->with(['busSchedule.busScheduleDate' => function($query) use ($search){
            $query->where('entry_date', 'like', '%'.$search. '%');
            $query->orderBy('entry_date',  'DESC');
            }])
            ->get();
           
            foreach($busWithEntryDates as $busWithEntryDate){
                $dateRecord = $busWithEntryDate->busSchedule;
                $cancelRecord = $busWithEntryDate->busCancelled ;
                
                $busWithEntryDate['source']=$this->location->where('id',$busWithEntryDate['busstoppage'][0]->source_id)->get();
                $busWithEntryDate['destination']=$this->location->where('id', $busWithEntryDate['busstoppage'][0]->destination_id)->get();

                $entryDates = array();
                foreach($dateRecord as $items)
                {
                     foreach($items->busScheduleDate as $dateRec) 
                    {     
                        $bus_id = $items->bus_id;              
                        $entryDate = $dateRec->entry_date; 
                        if($today <= $entryDate){

                            $entryDates[] = array(
                                "busId" =>$bus_id,
                                "entry_date"=>date('j M Y ',strtotime($entryDate)),
                               ); 

                        }                         
                    }
                }

                $cancelDates = array();
                foreach($cancelRecord as $items)
                {
                     foreach($items->busCancelledDate as $dateRec) 
                    {     
                        $bus_id = $items->bus_id;              
                        $cancelDate = $dateRec->cancelled_date; 
                        if($today <= $cancelDate){

                            $cancelDates[] = array(
                                "busId" =>$bus_id,
                                "cancelled_date"=>date('j M Y',strtotime($cancelDate)),
                               ); 

                        }                         
                    }
                }

               

                $busName = $busWithEntryDate->name; 
                $busName = $busName." [ ".$busWithEntryDate->bus_number." ] [".$busWithEntryDate->source[0]->name. ">>" .$busWithEntryDate->destination[0]->name." ]"   ;
               
                $busData[] = array(
                    "bus_id"=>$busList,
                    "busName"=>$busName,
                    "entryDates"=>$entryDates,   
                    "cancelDates"=>$cancelDates,   
                );              
            }

        }

        return ["busDatas" => $busData];
    } 


    public function getBusScheduleEntryforOperator($data)
    {
        // Log::info($data);
        // exit;
        $searchByBus = $data['busLists'];
        $search = $data['year']."-".$data['month'];
        $busData = array();
        $today=date("Y-m-d");
        // log::info($today);

       
            $busWithEntryDates = $this->bus->with('busstoppage','busCancelled.busCancelledDate')
            ->where('bus.id' , $searchByBus)
            ->with(['busSchedule.busScheduleDate' => function($query) use ($today){
            $query->where('entry_date', '>=', $today);
            $query->orderBy('entry_date',  'ASC')->limit(16);
            }])
            ->get();
           
            foreach($busWithEntryDates as $busWithEntryDate){
                $dateRecord = $busWithEntryDate->busSchedule;
                $cancelRecord = $busWithEntryDate->busCancelled ;
                
                $busWithEntryDate['source']=$this->location->where('id',$busWithEntryDate['busstoppage'][0]->source_id)->get();
                $busWithEntryDate['destination']=$this->location->where('id', $busWithEntryDate['busstoppage'][0]->destination_id)->get();

                $entryDates = array();
                foreach($dateRecord as $items)
                {
                     foreach($items->busScheduleDate as $dateRec) 
                    {     
                        $bus_id = $items->bus_id;              
                        $entryDate = $dateRec->entry_date; 
                        if($today <= $entryDate){

                            $entryDates[] = array(
                                "busId" =>$bus_id,
                                "entry_date"=>date('j M Y ',strtotime($entryDate)),
                               ); 

                        }                         
                    }
                }

                $cancelDates = array();
                foreach($cancelRecord as $items)
                {
                     foreach($items->busCancelledDate as $dateRec) 
                    {     
                        $bus_id = $items->bus_id;              
                        $cancelDate = $dateRec->cancelled_date; 
                        if($today <= $cancelDate){

                            $cancelDates[] = array(
                                "busId" =>$searchByBus,
                                "cancelled_date"=>date('j M Y',strtotime($cancelDate)),
                               ); 

                        }                         
                    }
                }

               

                $busName = $busWithEntryDate->name; 
                $busName = $busName." [ ".$busWithEntryDate->bus_number." ] [".$busWithEntryDate->source[0]->name. ">>" .$busWithEntryDate->destination[0]->name." ]"   ;
               
                $busData[] = array(
                    "bus_id"=>$searchByBus,
                    "busName"=>$busName,
                    "entryDates"=>$entryDates,   
                    "cancelDates"=>$cancelDates,   
                );              
            }

       

        return ["busDatas" => $busData];
    }


    public function getBusScheduleEntry($data)
    {
        // Log::info($data);
        // exit;
        $searchByBus = $data['busLists'];
        $search = $data['year']."-".$data['month'];
        $busData = array();
        $today=date("Y-m-d");

       
            $busWithEntryDates = $this->bus->with('busstoppage','busCancelled.busCancelledDate')
            ->where('bus.id' , $searchByBus)
            ->with(['busSchedule.busScheduleDate' => function($query) use ($search){
            $query->where('entry_date', 'like', '%'.$search. '%');
            $query->orderBy('entry_date',  'DESC');
            }])
            ->get();
           
            foreach($busWithEntryDates as $busWithEntryDate){
                $dateRecord = $busWithEntryDate->busSchedule;
                $cancelRecord = $busWithEntryDate->busCancelled ;
                
                $busWithEntryDate['source']=$this->location->where('id',$busWithEntryDate['busstoppage'][0]->source_id)->get();
                $busWithEntryDate['destination']=$this->location->where('id', $busWithEntryDate['busstoppage'][0]->destination_id)->get();

                $entryDates = array();
                foreach($dateRecord as $items)
                {
                     foreach($items->busScheduleDate as $dateRec) 
                    {     
                        $bus_id = $items->bus_id;              
                        $entryDate = $dateRec->entry_date; 
                        if($today <= $entryDate){

                            $entryDates[] = array(
                                "busId" =>$bus_id,
                                "entry_date"=>date('j M Y ',strtotime($entryDate)),
                               ); 

                        }                         
                    }
                }

                $cancelDates = array();
                foreach($cancelRecord as $items)
                {
                     foreach($items->busCancelledDate as $dateRec) 
                    {     
                        $bus_id = $items->bus_id;              
                        $cancelDate = $dateRec->cancelled_date; 
                        if($today <= $cancelDate){

                            $cancelDates[] = array(
                                "busId" =>$searchByBus,
                                "cancelled_date"=>date('j M Y',strtotime($cancelDate)),
                               ); 

                        }                         
                    }
                }

               

                $busName = $busWithEntryDate->name; 
                $busName = $busName." [ ".$busWithEntryDate->bus_number." ] [".$busWithEntryDate->source[0]->name. ">>" .$busWithEntryDate->destination[0]->name." ]"   ;
               
                $busData[] = array(
                    "bus_id"=>$searchByBus,
                    "busName"=>$busName,
                    "entryDates"=>$entryDates,   
                    "cancelDates"=>$cancelDates,   
                );              
            }

       

        return ["busDatas" => $busData];
    }

    public function GetBusList($data){

       // Log::info($data);

        $allBusList=[];
        $busIds=[];

        if(isset($data['route']) && $data['route'] && !isset($data['bus_operator_id'])){
            
            foreach($data['route'] as $d){

               $arr= explode('-',$d);

               $s_id=$arr[0];
               $d_id=$arr[1];

               $allBus=$this->busStoppage->with('bus')
                            ->whereHas('bus', function ($query){
                                $query->where('status', 1);               
                            })
                          ->with('busOperator')
                          ->where('source_id',$s_id)
                          ->where('destination_id',$d_id)
                          ->where('status',1)->get();


           //                 ->whereHas('bus', function ($query) use ($searchValue){
           //     $query->where('name', 'like', '%' .$searchValue . '%');               
           // })->whereNotIn('status', [2])->count()

                if($allBus){
                    foreach($allBus as $b){

                        if(!in_array($s_id.'-'.$d_id.'-'.$b->bus_id,$busIds)){

                            //array_push($busIds,$b->bus_id);


                            $src = $this->location->where('id',$b->source_id)->get();
                            $dest= $this->location->where('id',$b->destination_id)->get();
    
                            $BusList['bus_name']=$b->bus->name." - ".$b->bus->bus_number." - (".$src[0]->name."-".$dest[0]->name.") - (".$b->busOperator->organisation_name.' - '.$b->busOperator->operator_name.") ";
                            //$BusList['id']='src_'.$s_id.'-dest_'.$d_id.'-'.$b->bus_id;
                            $BusList['id']=$s_id.'-'.$d_id.'-'.$b->bus_id;

                            array_push($busIds,$s_id.'-'.$d_id.'-'.$b->bus_id);
                            
    
    
                            array_push($allBusList,$BusList);

                        }                      

    
                    }
                } 
                
            }    


        }
        else if(isset($data['bus_operator_id']) && $data['bus_operator_id'] && !isset($data['route'])){

              $allBus=$this->busStoppage->select('bus_id')
                             ->with(['bus' => function($b){
                                $b->with('busOperator','ticketPrice');
                             } ])   
                              ->whereHas('bus', function ($query){
                                $query->where('status', 1);               
                            })                     
                            ->whereIn('bus_operator_id',$data['bus_operator_id'])
                            ->groupBy('bus_id')
                            ->get();
 
            if($allBus){
                foreach($allBus as $k => $b){
                    $stoppages['source'][$k]=$this->location->where('id', $b->bus->ticketPrice[0]->source_id)->get();
                    $stoppages['destination'][$k]=$this->location->where('id', $b->bus->ticketPrice[0]->destination_id)->get();
                    
                    $allBusList[$k]['bus_name']=$b->bus->name." - ".$b->bus->bus_number." (".$b->bus->busOperator->organisation_name.' - '.$b->bus->busOperator->operator_name.")"." [ ".$stoppages['source'][$k][0]->name.">>".$stoppages['destination'][$k][0]->name." ] ";
                   // $allBusList[$k]['id']='opr_'.$b->bus->busOperator->id.'-'.$b->bus_id;
                    $allBusList[$k]['id']=$b->bus->busOperator->id.'-'.$b->bus_id;

                }
                // return $stoppages;
            }    
        }

        else if(isset($data['bus_operator_id']) && isset($data['route'])){

            foreach($data['route'] as $d){

                $arr= explode('-',$d);
 
                $s_id=$arr[0];
                $d_id=$arr[1];
 
                $allBus=$this->busStoppage->with(['bus' => function($b){
                    $b->where('status',1);} ])  ->whereHas('bus', function ($query){
                                $query->where('status', 1);               
                            })   
                           ->with('busOperator')
                           ->where('source_id',$s_id)
                           ->where('destination_id',$d_id)
                           ->where('status',1)
                           ->whereIn('bus_operator_id',$data['bus_operator_id'])
                           ->get();
 
                  if($allBus){
                      foreach($allBus as  $b){
 
                        if(!in_array($b->bus_id,$busIds)){

                            array_push($busIds,$b->bus_id);


                            $src = $this->location->where('id',$b->source_id)->get();
                            $dest= $this->location->where('id',$b->destination_id)->get();
    
                            $BusList['bus_name']=$b->bus->name." - ".$b->bus->bus_number." - (".$src[0]->name."-".$dest[0]->name.") - (".$b->busOperator->organisation_name.' - '.$b->busOperator->operator_name.") ";
                            //$BusList['id']='opr_'.$b->busOperator->id.'-'.'src_'.$s_id.'-dest_'.$d_id.'-'.$b->bus_id;
                            $BusList['id']=$b->busOperator->id.'-'.$s_id.'-'.$d_id.'-'.$b->bus_id;
    
    
                            array_push($allBusList,$BusList);

                        }    
 
                      }
                  }         
 
             }

           
        }

        //Log::info($allBusList);

        return $allBusList;

    }

    public function allCouponBusList($id){

      $coupon_detail= $this->coupon->where("id",$id)->first();

    
      $operator_id= $coupon_detail->bus_operator_id;
      $s_id=$coupon_detail->source_id;
      $d_id=$coupon_detail->destination_id;

      $allBusList=[];
      $busIds=[];

     


        if($coupon_detail->source_id != null && $coupon_detail->destination_id != null && $coupon_detail->bus_operator_id == null){
              
               $allBus=$this->busStoppage->with('bus')->with('busOperator')
                          ->where('source_id',$s_id)
                          ->where('destination_id',$d_id)
                          ->where('status',1)->get();

                if($allBus){
                    foreach($allBus as $b){

                        if(!in_array($b->bus_id,$busIds)){

                            array_push($busIds,$b->bus_id);


                            $src = $this->location->where('id',$b->source_id)->get();
                            $dest= $this->location->where('id',$b->destination_id)->get();
    
                            $BusList['bus_name']=$b->bus->name." - ".$b->bus->bus_number." - (".$src[0]->name."-".$dest[0]->name.") - (".$b->busOperator->organisation_name.' - '.$b->busOperator->operator_name.") ";
                            $BusList['id']=$b->bus_id;
    
    
                            array_push($allBusList,$BusList);

                        }                      

    
                    }
                }

        }

        else if($coupon_detail->source_id == null && $coupon_detail->destination_id == null && $coupon_detail->bus_operator_id != null){

            $allBus=$this->busStoppage->select('bus_id')
                             ->with(['bus' => function($b){
                                $b->with('busOperator');
                             } ])                           
                            ->where('bus_operator_id',$operator_id)
                            ->groupBy('bus_id')
                            ->get();
 
            if($allBus){
                foreach($allBus as $k => $b){
                    $allBusList[$k]['bus_name']=$b->bus->name." - ".$b->bus->bus_number." (".$b->bus->busOperator->organisation_name.' - '.$b->bus->busOperator->operator_name.") ";
                    $allBusList[$k]['id']=$b->bus_id;

                }
            }    
        }

        else if($coupon_detail->source_id != null && $coupon_detail->destination_id != null && $coupon_detail->bus_operator_id != null){
         
                $allBus=$this->busStoppage->with('bus')->with('busOperator')
                           ->where('source_id',$s_id)
                           ->where('destination_id',$d_id)
                           ->where('status',1)
                           ->where('bus_operator_id',$operator_id)
                           ->get();
 
                  if($allBus){
                      foreach($allBus as  $b){
 
                        if(!in_array($b->bus_id,$busIds)){

                            array_push($busIds,$b->bus_id);

                            $src = $this->location->where('id',$b->source_id)->get();
                            $dest= $this->location->where('id',$b->destination_id)->get();
    
                            $BusList['bus_name']=$b->bus->name." - ".$b->bus->bus_number." - (".$src[0]->name."-".$dest[0]->name.") - (".$b->busOperator->organisation_name.' - '.$b->busOperator->operator_name.") ";                           
                            $BusList['id']=$b->bus_id;   
    
                            array_push($allBusList,$BusList);

                        }    
 
                      }
                  }
           
        }

        return $allBusList;
}

}
