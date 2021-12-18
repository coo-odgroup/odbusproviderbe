<?php

namespace App\Repositories;

// use App\Models\Bus;
use App\Models\SeatOpen;
use App\Models\SeatOpenSeats;

use App\Models\BusSeats;
use App\Models\Bus;
use App\Models\Location;

// use App\Models\TicketPrice;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


/*Priyadarshi to Review*/
class SeatOpenRepository
{
    
    protected $seatOpen;
    protected $busSeats;
    protected $bus;
    
    public function __construct(SeatOpen $seatOpen , SeatOpenSeats $seatsOpenSeats ,BusSeats  
        $busSeats,Bus $bus,Location $location )
    {
        $this->seatOpen = $seatOpen;
        $this->seatOpenSeats = $seatsOpenSeats;
        $this->busSeats = $busSeats;
        $this->bus = $bus;
        $this->location = $location;  
       
    }    
    public function getAll()
    {
        // return $this->seatOpen->with('seatOpenSeats')->with('bus','bus.busOperator')->get();
        return $this->busSeats->with('seats')->with('bus','bus.busOperator')->get();

    }
    //  public function addseatopen($data)
    // {       
    // Log::info($data);
    // exit(); 
    //     $seatopen = new $this->seatOpen;
    //     $seatopen->bus_id = $data['bus_id'];
    //     $seatopen->operator_id = $data['bus_operator_id'];
    //     $seatopen->reason = $data['reason'];
    //     $seatopen->date_applied = $data['date'];
    //     $seatopen->created_by = $data['created_by'];
    //     $seatopen->save();
    //     $seats = [];
    //     foreach ($data['bus_seat_layout_data'] as $slayout)
    //     {
            
    //         foreach ($slayout['lowerBerth'] as $lberth) 
    //         {
    //             $seat = new SeatOpenSeats();
    //             if(isset($lberth['seatChecked']))
    //             {
    //                 if($lberth["seatChecked"] == true)
    //                 {
    //                     $seat['seats_id'] = $lberth['seatId'];
    //                     $seat['created_by'] = $data['created_by'];
                       
    //                     $seats[]=$seat;
    //                 }
    //             }
               
                
    //         }

    //         foreach ($slayout['upperBerth'] as $uberth) 
    //         {
    //             $seat = new SeatOpenSeats();
    //             //Log::info($uberth);
    //             if(isset($uberth['seatChecked']))
    //             {
    //                 if($uberth["seatChecked"] == true)
    //                 {
    //                     $seat['seats_id'] = $uberth['seatId'];
    //                     $seat['created_by'] = $data['created_by'];

    //                     $seats[]=$seat;
    //                 }
    //             }
    //         }

    //     }          
    //      $seatopen->seatOpenSeats()->saveMany($seats);
    //      return $seatopen;        
    // }
    public function addseatopen($data)
    {
        // Log::info($data);
        // exit();

        $layoutArray=$data['bus_seat_layout_data'];
        $get_ticket_price_id= $data['busRoute'];
        foreach($layoutArray as $sLayoutData)
        {
            if(isset($sLayoutData['upperBerth']))
            {

                if(count($sLayoutData['upperBerth'])>0)
                {

                    foreach($sLayoutData['upperBerth'] as $upperBerthData)
                    {
                        if(isset($upperBerthData['seatChecked']))
                        {
                            if($upperBerthData['seatChecked'] =="true")
                            {
                                foreach($get_ticket_price_id as $ticketpriceID)
                                {                              
                                    $busseats = new $this->busSeats;                            
                                    $busseats->bus_id = $data['bus_id'];
                                    $busseats->category = '0';
                                    $busseats->seats_id = $upperBerthData['seatId'];
                                    $busseats->ticket_price_id = $ticketpriceID;
                                    $busseats->operation_date = $data['date'];
                                    $busseats->status = '1';
                                    $busseats->type = $data['type'];
                                    $busseats->created_by = $data['created_by'];
                                    $busseats->reason = $data['reason'];   
                                    $busseats->other_reason = $data['other_reason'];

                                    $busseats->save(); 
                                }
                            }
                        }                  
                    }
                }
            }
            if(isset($sLayoutData['lowerBerth']))
            {

                if(count($sLayoutData['lowerBerth'])>0)
                { 
                    foreach($sLayoutData['lowerBerth'] as $lowerBerthData)
                    {
                        if(isset($lowerBerthData['seatChecked']))
                        {
                            if($lowerBerthData['seatChecked'] =="true")
                            {                         
                                foreach($get_ticket_price_id as $ticketpriceID)
                                {
                                    $busseats = new $this->busSeats;                              
                                    $busseats->bus_id = $data['bus_id'];
                                    $busseats->category = '0';
                                    $busseats->seats_id = $lowerBerthData['seatId'];
                                    $busseats->ticket_price_id = $ticketpriceID;
                                    $busseats->operation_date = $data['date'];
                                    $busseats->status = '1';
                                    $busseats->type = $data['type'];
                                    $busseats->created_by = $data['created_by'];
                                    $busseats->reason = $data['reason'];                  
                                    $busseats->other_reason = $data['other_reason'];                                 
                                    // Log::info('lower');
                                    // Log::info($busseats);
                                    $busseats->save(); 
                                }
                            }
                        }                      
                    }
                }
            }
        }
        return $data;
    }

    public function seatopenData($request)
    {
         $paginate = $request['rows_number'] ;
         $name = $request['name'] ;
    
        $data= $this->busSeats->with('bus.busOperator','seats','ticketPrice')
                              ->where('type',1)
                              ->whereNotIn('status', [2]);

        if($request['USER_BUS_OPERATOR_ID']!="")
        {
           //  $data=$data->whereHas('bus', function ($query) use ($request){
           //     $query->where('bus_operator_id', $request['USER_BUS_OPERATOR_ID']);               
           // });
        }                                 

        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }   
       
        $data=$data->get()->groupBy(['bus_id','operation_date','ticket_price_id']);

        if($data)
        {
             foreach($data as $date){

                foreach ($date as $route) {
                   foreach ($route as $seatOp)
                    {
                       foreach ($seatOp as $SingleseatOp)
                        {
                           // Log::info($SingleseatOp->ticketPrice->source_id);
                            $SingleseatOp['source']=$this->location->where('id', $SingleseatOp->ticketPrice->source_id)->get();
                            $SingleseatOp['destination']=$this->location->where('id', $SingleseatOp->ticketPrice->destination_id)->get(); 
                        }
                    }
                }
            }
        }

       $result = $this->customPaginate(collect($data),$paginate)->withPath('/api/seatopenData');
    
        return $result;
        
        // $response = array(
        //      "count" => $result->count(), 
        //      "total" => $result->total(),
        //      "data" => $result
        //    ); 
        //    log::info($response);  
        //    return $response;

           

    }

    public function customPaginate($items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }


    // public function updateseatopen($data)
    // {
    //      Log::info($data);
    //     return $data;
    // }

    public function updateseatopen($data, $id)
    {
         $setopen = $this->seatOpen->find($id);
         $setopen->seatOpenSeats()->where('seat_open_id', $id)->delete();
         $setopen->delete();

        $seatopen = new $this->seatOpen;
        $seatopen->bus_id = $data['bus_id'];
        $seatopen->operator_id = $data['bus_operator_id'];
        $seatopen->reason = $data['reason'];
        $seatopen->date_applied = $data['date'];
        $seatopen->created_by = $data['created_by'];
        $seatopen->save();
        $seats = [];
        foreach ($data['bus_seat_layout_data'] as $slayout)
        {
            foreach ($slayout['lowerBerth'] as $lberth) 
            {
                $seat = new SeatOpenSeats();
                if(isset($lberth['seatChecked']))
                {
                    if($lberth["seatChecked"] == 'true')
                    {
                        $seat['seats_id'] = $lberth['seatId'];
                        $seat['created_by'] = $data['created_by'];
                        $seats[]=$seat;
                    }
                }
               
                
            }

            foreach ($slayout['upperBerth'] as $uberth) 
            {
                $seat = new SeatOpenSeats();
                if(isset($uberth['seatChecked']))
                {
                    if($uberth["seatChecked"] == 'true')
                    {
                        $seat['seats_id'] = $uberth['seatId'];
                        $seat['created_by'] = $data['created_by'];
                        $seats[]=$seat;
                    }
                }
            }
        }
         $seatopen->seatOpenSeats()->saveMany($seats);
         return $seatopen;


    }

    public function delete($request)
    {
        
        $seatOpen = $this->busSeats
                         ->where('bus_id',$request['bus_id'])
                         ->where('ticket_price_id',$request['ticketPriceId'])
                         ->where('operation_date',$request['operationDate'])
                         ->where('type',$request['type'])
                         ->update(['status'=> '2']);

        return $seatOpen;
    }



     public function getseatopenDT($request)
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
        // Total records//
        // $totalRecords=$this->specialFare->whereHas('bus')->whereNotIn('status', [2])->count();
        $totalRecords=$this->seatOpen->with('seatOpenSeats.seats')->with('bus.busOperator')->with('seats')->whereNotIn('status', [2])->count();
        $totalRecordswithFilter=$this->seatOpen
            ->with('seatOpenSeats.seats')->with('bus.busOperator')  
            ->whereHas('bus', function ($query) use ($searchValue){
                $query->where('name', 'like', '%' .$searchValue . '%');               
            })
            ->orWhereHas('bus.busOperator', function ($query) use ($searchValue){
                $query->where('operator_name', 'like', '%' .$searchValue . '%');
            })
            ->whereNotIn('status', [2])->count();
        //Fetch records//
        $records = $this->seatOpen
            ->with('seatOpenSeats.seats','bus.busOperator') 
            ->whereHas('bus', function ($query) use ($searchValue){
               $query->where('name', 'like', '%' .$searchValue . '%');               
            })
            ->orWhereHas('bus.busOperator', function ($query) use ($searchValue){
               $query->where('operator_name', 'like', '%' .$searchValue . '%');
            })
            ->orderBy($columnName,$columnSortOrder) 
           ->skip($start)
           ->take($rowperpage)
           ->whereNotIn('status', [2])
           ->get();
        // Log::info($records);      
        // $data_arr = array();        
        // foreach($records as $key=>$record)
        // {       
        //    $buses= $record->bus; 
        //    $busNames="";     
        //   foreach($buses as $bus)
        //    {           
        //     $busNames .=  ($busNames=="")?$bus->name:",".$bus->name; 
        //    }
        //    $data_arr[]=$record->toArray(); 
        //    $data_arr[$key]['name']=$busNames;
        //    $data_arr[$key]['created_at']=date('j M Y h:i a',strtotime($record->created_at));
        //    $data_arr[$key]['updated_at']=date('j M Y h:i a',strtotime($record->updated_at));
        // } 
       $data_arr = array();
        foreach($records as $key=>$record)
        {
            $data_arr[]=$record->toArray();
            $data_arr[$key]['created_at']=date('j M Y h:i a',strtotime($record->created_at));
            $data_arr[$key]['updated_at']=date('j M Y h:i a',strtotime($record->updated_at)); 
            $data_arr[$key]['date_applied']=date('m/d/Y',strtotime($record->date_applied));
        }
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        ); 
        return ($response);
    }


    public function changeStatus($id)
    {
        $post = $this->seatOpen->find($id);
        if($post->status==0){
            $post->status = 1;
        }elseif($post->status==1){
            $post->status = 0;
        }
        $post->update();
        return $post;
    }

     

}