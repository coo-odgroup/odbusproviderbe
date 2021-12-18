<?php

namespace App\Repositories;

// use App\Models\Bus;
use App\Models\SeatBlock;
use App\Models\SeatBlockSeats;

use App\Models\BusSeats;
use App\Models\Bus;
use App\Models\Location;

// use App\Models\TicketPrice;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class SeatBlockRepository
{
    
    protected $seatBlock;

    
    public function __construct(SeatBlock $seatBlock , SeatBlockSeats $seatsBlockSeats,BusSeats 
        $busSeats,Bus $bus,Location $location)
    {
        $this->seatBlock = $seatBlock;
        $this->seatBlockSeats = $seatsBlockSeats;
        $this->busSeats = $busSeats;
        $this->bus = $bus;
        $this->location = $location;  
       
    }    
    public function getAll()
    {
        return $this->seatBlock->with('seatBlockSeats')->with('bus','bus.busOperator')->get();

    }
    //  public function addseatBlock($data)
    // {        
    //     $seatBlock = new $this->seatBlock;
    //     $seatBlock->bus_id = $data['bus_id'];
    //     $seatBlock->operator_id = $data['bus_operator_id'];
    //     $seatBlock->reason = $data['reason'];
    //     $seatBlock->date_applied = $data['date'];
    //     $seatBlock->created_by = $data['created_by'];
    //     $seatBlock->save();
    //     $seats = [];
    //     foreach ($data['bus_seat_layout_data'] as $slayout)
    //     {
            
    //         foreach ($slayout['lowerBerth'] as $lberth) 
    //         {
    //             $seat = new seatBlockSeats();
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
    //             $seat = new seatBlockSeats();
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
    //      $seatBlock->seatBlockSeats()->saveMany($seats);
    //      return $seatBlock;        
    // }
    public function addseatBlock($data)
    {
        // Log::info($data);
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

    

    public function updateseatBlock($data, $id)
    {
         $setblock = $this->seatBlock->find($id);
         $setblock->seatBlockSeats()->where('seat_block_id', $id)->delete();
         $setblock->delete();

        $seatBlock = new $this->seatBlock;
        $seatBlock->bus_id = $data['bus_id'];
        $seatBlock->operator_id = $data['bus_operator_id'];
        $seatBlock->reason = $data['reason'];
        $seatBlock->date_applied = $data['date'];
        $seatBlock->created_by = $data['created_by'];
        $seatBlock->save();
        $seats = [];
        foreach ($data['bus_seat_layout_data'] as $slayout)
        {
            foreach ($slayout['lowerBerth'] as $lberth) 
            {
                $seat = new seatBlockSeats();
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
                $seat = new seatBlockSeats();
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
         $seatBlock->seatBlockSeats()->saveMany($seats);
         return $seatBlock;


    }

    public function delete($request)
    {        
        $seatBlock = $this->busSeats
                         ->where('bus_id',$request['bus_id'])
                         ->where('ticket_price_id',$request['ticketPriceId'])
                         ->where('operation_date',$request['operationDate'])
                         ->where('type',$request['type'])
                         ->update(['status'=> '2']);
        return $seatBlock;
    }



     public function getseatBlockDT($request)
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
        $totalRecords=$this->seatBlock->with('seatBlockSeats.seats')->with('bus.busOperator')->with('seats')->whereNotIn('status', [2])->count();
        $totalRecordswithFilter=$this->seatBlock
            ->with('seatBlockSeats.seats')->with('bus.busOperator')  
            ->whereHas('bus', function ($query) use ($searchValue){
                $query->where('name', 'like', '%' .$searchValue . '%');               
            })
            ->orWhereHas('bus.busOperator', function ($query) use ($searchValue){
                $query->where('operator_name', 'like', '%' .$searchValue . '%');
            })
            ->whereNotIn('status', [2])->count();
        //Fetch records//
        $records = $this->seatBlock
            ->with('seatBlockSeats.seats','bus.busOperator') 
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

    public function seatblockData($request)
    {
            
         $paginate = $request['rows_number'] ;
         $name = $request['name'] ;
       

         $data= $this->bus->with('busOperator')
                         ->with(['ticketPrice.getBusSeats' => function ($a){
                                $a->where('type',2)->whereNotIn('status',[2])->with('seats');
                                }])                         
                         ->whereNotIn('status', [2])
                         ->whereHas('ticketPrice.getBusSeats', function ($query){
                           $query->where('type', "2")->whereNotIn('status',[2]);  
                                     
                         });

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
            $data = $data->whereHas('bus', function ($query) use ($name){
                $query->where('name', 'like', '%' .$name . '%');               
            })
            

            ->orWhereHas('bus.busOperator', function ($query) use ($name){
                $query->where('operator_name', 'like', '%' .$name . '%');
            });
            
        }     

        $data=$data->paginate($paginate);

         if($data){
            foreach($data as $v){ 
             foreach($v->ticketPrice as $k => $a)
             {             
             
                $a['source']=$this->location->where('id', $a->source_id)->get();
                $a['destination']=$this->location->where('id', $a->destination_id)->get(); 
           }
            
       }}

        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
           return $response;
    }


    public function changeStatus($id)
    {
        $post = $this->seatBlock->find($id);
        if($post->status==0){
            $post->status = 1;
        }elseif($post->status==1){
            $post->status = 0;
        }
        $post->update();
        return $post;
    }
   

}