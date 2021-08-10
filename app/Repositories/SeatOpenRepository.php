<?php

namespace App\Repositories;

// use App\Models\Bus;
use App\Models\SeatOpen;
use App\Models\SeatOpenSeats;

// use App\Models\TicketPrice;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class SeatOpenRepository
{
    
    protected $seatOpen;

    
    public function __construct(SeatOpen $seatOpen , SeatOpenSeats $seatsOpenSeats)
    {
        $this->seatOpen = $seatOpen;
        $this->seatOpenSeats = $seatsOpenSeats;
       
    }    
    public function getAll()
    {
        return $this->seatOpen->with('seatOpenSeats')->with('bus','bus.busOperator')->get();

    }
     public function addseatopen($data)
    {        
        $seatopen = new $this->seatOpen;
        $seatopen->bus_id = $data['bus_id'];
        $seatopen->operator_id = $data['bus_operator_id'];
        $seatopen->reason = $data['reason'];
        $seatopen->date_applied = $data['date'];
        $seatopen->created_by = "Admin";
        $seatopen->save();
        $seats = [];
        foreach ($data['bus_seat_layout_data'] as $slayout)
        {
            
            foreach ($slayout['lowerBerth'] as $lberth) 
            {
                $seat = new SeatOpenSeats();
                if(isset($lberth['seatChecked']))
                {
                    if($lberth["seatChecked"] == true)
                    {
                        $seat['seats_id'] = $lberth['seatId'];
                        $seat['created_by'] = "Admin";
                       
                        $seats[]=$seat;
                    }
                }
               
                
            }

            foreach ($slayout['upperBerth'] as $uberth) 
            {
                $seat = new SeatOpenSeats();
                //Log::info($uberth);
                if(isset($uberth['seatChecked']))
                {
                    if($uberth["seatChecked"] == true)
                    {
                        $seat['seats_id'] = $uberth['seatId'];
                        $seat['created_by'] = "Admin";

                        $seats[]=$seat;
                    }
                }
            }

        }          
         $seatopen->seatOpenSeats()->saveMany($seats);
         return $seatopen;        
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
        $seatopen->created_by = "Admin";
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
                        $seat['created_by'] = "Admin";
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
                        $seat['created_by'] = "Admin";
                        $seats[]=$seat;
                    }
                }
            }
        }
         $seatopen->seatOpenSeats()->saveMany($seats);
         return $seatopen;


    }

     public function delete($id)
    {
        
        $setopen = $this->seatOpen->find($id);
        // Log::info($setopen);exit;
        // $setopen->delete();

         $setopen->seatOpenSeats()->where('seat_open_id', $id)->delete();
         $setopen->delete();

       

        return $setopen;
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

    // public function getAllFare($busId)
    // {
    //    // $result['busSeats']=$this->busSeats->where('bus_id',$busId)->orderBy('source_id','ASC')->get();//busSeats
    //    // $result['stoppageInfo']=$this->busStoppage->where('bus_id', $busId)->get();
    //    return $this->ticketPrice->with('getBusSeats')->where('bus_id',$busId)->get();
    // }
    // public function getByBusId($busId)
    // {
    //     return $this->seatOpen->where('bus_id', $busId)->get();
    // }
    // public function getById($id)
    // {
    //     return $this->seatOpen ->where('id', $id)->get();
    // }
    // public function getModel(SeatOpen $seatopen, $data)
    // {
    //     // Log::info($data);
    //     $seatOpen->bus_id = $data['bus_id'];       
    //     $seatOpen->reason = $data['reason'];
    //     $seatOpen->date = $data['date'];       
        
    //     $seatOpen->created_by = "Admin";
    //     Log::info($seatOpen);
    //     return $seatOpen;
    // }



    // public function save($data)
    // {
       
    //     $layoutArray=$data['bus_seat_layout_data'];
    //     foreach($layoutArray as $sLayoutData)
    //     {
    //         if(isset($sLayoutData['upperBerth']))
    //         {
    //             if(count($sLayoutData['upperBerth'])>0)
    //             {
    //                 foreach($sLayoutData['upperBerth'] as $upperBerthData)
    //                 {
    //                     if($upperBerthData['seatChecked']==true)
    //                     {
    //                         $busseats = new $this->busSeats;
    //                         $busseats=$this->getModel($busseats,$data,$upperBerthData);
    //                         $busseats->save();
    //                     }
    //                 }
    //             }
    //         }
    //         if(isset($sLayoutData['lowerBerth']))
    //         {
    //             if(count($sLayoutData['lowerBerth'])>0)
    //             {       
    //                 foreach($sLayoutData['lowerBerth'] as $lowerBerthData)
    //                 {
    //                     if($lowerBerthData['seatChecked']==true)
    //                     {
    //                         $busseats = new $this->busSeats;
    //                         $busseats= $this->getModel($busseats,$data,$lowerBerthData);
    //                         $busseats->save();
    //                     }
                       
    //                 }
    //             }
    //         }
    //     }
    //     return $data;
    // }
    // public function updateNewFare($data)
    // {
    //     foreach ($data['fare_info'] as $singleSeat)   
    //     {
            
    //         $this->busSeats = $this->busSeats->find($singleSeat['id']);
    //         $this->busSeats->new_fare = $singleSeat['new_fare'];  
    //         $this->busSeats->update();
    //     }
    //     return $data;
    // }
    // public function updateBusSeatsExtra($data,$id)
    // {
    //     $layoutArray=$data['bus_seat_layout_data'];
    //     foreach($layoutArray as $sLayoutData)
    //     {
    //         if(isset($sLayoutData['upperBerth']))
    //         {
    //             if(count($sLayoutData['upperBerth'])>0)
    //             {
    //                 foreach($sLayoutData['upperBerth'] as $upperBerthData)
    //                 {
    //                     if(!isset($upperBerthData['seatChecked']))
    //                     {
    //                         continue;
    //                     }
    //                     if($upperBerthData['seatChecked']!=true && $upperBerthData['seatId']!=NULL)
    //                     {
    //                         $busseats = $this->busSeats->find($upperBerthData['seatId']);
    //                         $busseats->delete();
    //                     }
    //                     if($upperBerthData['seatChecked']==true)
    //                     {
    //                         if($upperBerthData['seatId']=="")
    //                         {
    //                             $busseats = new $this->busSeats;
    //                             $busseats=$this->getModel($busseats,$data,$upperBerthData);
    //                             $busseats->save();
    //                         }
    //                         else if($upperBerthData['seatId']!="" && intval($upperBerthData['extraSeat'])>0)
    //                         {
    //                             $busseats = $this->busSeats->find($upperBerthData['seatId']);
    //                             $busseats=$this->getModel($busseats,$data,$upperBerthData);
    //                             $busseats->update();
    //                         }
                            
    //                     }
    //                 }
    //             }
    //         }
    //         if(isset($sLayoutData['lowerBerth']))
    //         {
    //             if(count($sLayoutData['lowerBerth'])>0)
    //             {    
    //                 foreach($sLayoutData['lowerBerth'] as $lowerBerthData)
    //                 {
    //                     if(!isset($lowerBerthData['seatChecked']))
    //                     {
    //                         continue;
    //                     }
    //                     if($lowerBerthData['seatChecked']!=true && $lowerBerthData['seatId']!=NULL)
    //                     {
    //                         $busseats = $this->busSeats->find($lowerBerthData['seatId']);
    //                         $busseats->delete();
    //                     }
    //                     if($lowerBerthData['seatChecked']==true)
    //                     {
    //                         if($lowerBerthData['seatId']=="")
    //                         {
    //                             $busseats = new $this->busSeats;
    //                             $busseats=$this->getModel($busseats,$data,$lowerBerthData);
    //                             $busseats->save();
    //                         }
    //                         else if($lowerBerthData['seatId']!="" && intval($lowerBerthData['extraSeat'])>0)
    //                         {
    //                             $busseats = $this->busSeats->find($lowerBerthData['seatId']);
    //                             $busseats=$this->getModel($busseats,$data,$lowerBerthData);
    //                             $busseats->update();
    //                         }
                            
    //                     }
    //                 }
    //             }
    //         }
    //     }
    //     return $data;
    // }
    // public function update($data, $id)
    // {
    //     $layoutArray=$data['bus_seat_layout_data'];
    //     foreach($layoutArray as $sLayoutData)
    //     {
    //         if(isset($sLayoutData['upperBerth']))
    //         {
    //             if(count($sLayoutData['upperBerth'])>0)
    //             {
    //                 foreach($sLayoutData['upperBerth'] as $upperBerthData)
    //                 {
    //                     if($upperBerthData['seatChecked']!=true && $upperBerthData['seatId']!=NULL)
    //                     {
    //                         $busseats = $this->busSeats->find($upperBerthData['seatId']);
    //                         $busseats->delete();
    //                     }
    //                     if($upperBerthData['seatChecked']==true)
    //                     {
    //                         if($upperBerthData['seatId']=="")
    //                         {
    //                             $busseats = new $this->busSeats;
    //                         }
    //                         else
    //                         {
    //                             $busseats = $this->busSeats->find($upperBerthData['seatId']);
                                
    //                         }
    //                         $busseats=$this->getModel($busseats,$data,$upperBerthData);
    //                         if($upperBerthData['seatId']=="")
    //                         {
    //                             $busseats->save();
    //                         }
    //                         else
    //                         {
    //                             $busseats->update();
    //                         }                            
    //                     }
    //                 }
    //             }
    //         }
    //         if(isset($sLayoutData['lowerBerth']))
    //         {
    //             if(count($sLayoutData['lowerBerth'])>0)
    //             {    
    //                 foreach($sLayoutData['lowerBerth'] as $lowerBerthData)
    //                 {
    //                     if($lowerBerthData['seatChecked']!=true && $lowerBerthData['seatId']!=NULL)
    //                     {
    //                         $busseats = $this->busSeats->find($lowerBerthData['seatId']);
    //                         $busseats->delete();
    //                     }
    //                     if($lowerBerthData['seatChecked']==true)
    //                     {
    //                         if($lowerBerthData['seatId']=="")
    //                         {
    //                             $busseats = new $this->busSeats;
    //                         }
    //                         else
    //                         {
    //                             $busseats = $this->busSeats->find($lowerBerthData['seatId']);
    //                         }
    //                         $busseats=$this->getModel($busseats,$data,$lowerBerthData);
    //                         if($lowerBerthData['seatId']=="")
    //                         {                                
    //                             $busseats->save();
    //                         }
    //                         else
    //                         {
    //                             $busseats->update();
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }
    //     return $data;
    // }

    
   

}