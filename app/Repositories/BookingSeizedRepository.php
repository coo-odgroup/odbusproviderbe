<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\BookingSeized;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class BookingSeizedRepository
{    
    protected $bookingSeized;
    protected $bus;

    
    public function __construct(BookingSeized $bookingSeized,Bus $bus )
    {
        $this->bus = $bus;

        $this->bookingSeized = $bookingSeized;       
    }    

    public function getAll()
    {
        // return $this->bookingSeized->with('location')->with('bus.busOperator')->get();


        return $this->bus->with('bookingseized.location','busOperator')->get();

     
    }





    public function update($seizedData)
    {   


        foreach($seizedData['busSeized'] as $record)
        {
            $seized = $this->bookingSeized->find($record['id']); 
            $seized->seize_booking_minute = $record['time'];        
            $seized->update();
        }
        return $seizedData;
    }

    //  public function update($data, $id)
    // {

    //     // $festivalFare = new $this->festivalFare;

    //     // return $seized = $this->festivalFare->find($id);

    //     $seized->bus_id = $data['bus_id'];
    //     $seized->created_by = "Admin";

    //     foreach ($data['location_id '] as $k=>$location)
    //     {


    //     }
    //     // foreach ($data['seize_booking_minute '] as $booking_minute)
    //     // {


    //     // }


    //     $seized->update();
    // }


    public function changeStatus($id)
    {
       
        $post = $this->bookingSeized->find($id);
        if($post->status==0){
            $post->status = 1;
        }elseif($post->status==1){
            $post->status = 0;
        }
        $post->update();
        return $post;
    }


    //  public function delete($id)
    // {
        
    //     $setopen = $this->seatOpen->find($id);
    //     // Log::info($setopen);exit;
    //     // $setopen->delete();

    //      $setopen->seatOpenSeats()->where('seat_open_id', $id)->delete();
    //      $setopen->delete();

       

    //     return $setopen;
    // }



    //  public function getseatopenDT($request)
    // {  
    //     $draw = $request->get('draw');
    //     $start = $request->get("start");
    //     $rowperpage = $request->get("length"); // Rows display per page
    //     if(!is_numeric($rowperpage))
    //     {
    //         $rowperpage=Config::get('constants.ALL_RECORDS');
    //     }
    //     $columnIndex_arr = $request->get('order');
    //     $columnName_arr = $request->get('columns');
    //     $order_arr = $request->get('order');
    //     $search_arr = $request->get('search');
    //     $columnIndex = $columnIndex_arr[0]['column']; // Column index
    //     $columnName = $columnName_arr[$columnIndex]['data']; // Column name
    //     $columnSortOrder = $order_arr[0]['dir']; // asc or desc
    //     $searchValue = $search_arr['value']; // Search value
    //     // Total records//
    //     // $totalRecords=$this->specialFare->whereHas('bus')->whereNotIn('status', [2])->count();
    //     $totalRecords=$this->seatOpen->with('seatOpenSeats.seats')->with('bus.busOperator')->with('seats')->whereNotIn('status', [2])->count();
    //     $totalRecordswithFilter=$this->seatOpen
    //         ->with('seatOpenSeats.seats')->with('bus.busOperator')  
    //         ->whereHas('bus', function ($query) use ($searchValue){
    //             $query->where('name', 'like', '%' .$searchValue . '%');               
    //         })
    //         ->orWhereHas('bus.busOperator', function ($query) use ($searchValue){
    //             $query->where('operator_name', 'like', '%' .$searchValue . '%');
    //         })
    //         ->whereNotIn('status', [2])->count();
    //     //Fetch records//
    //     $records = $this->seatOpen
    //         ->with('seatOpenSeats.seats','bus.busOperator') 
    //         ->whereHas('bus', function ($query) use ($searchValue){
    //            $query->where('name', 'like', '%' .$searchValue . '%');               
    //         })
    //         ->orWhereHas('bus.busOperator', function ($query) use ($searchValue){
    //            $query->where('operator_name', 'like', '%' .$searchValue . '%');
    //         })
    //         ->orderBy($columnName,$columnSortOrder) 
    //        ->skip($start)
    //        ->take($rowperpage)
    //        ->whereNotIn('status', [2])
    //        ->get();
    //     // Log::info($records);      
    //     // $data_arr = array();        
    //     // foreach($records as $key=>$record)
    //     // {       
    //     //    $buses= $record->bus; 
    //     //    $busNames="";     
    //     //   foreach($buses as $bus)
    //     //    {           
    //     //     $busNames .=  ($busNames=="")?$bus->name:",".$bus->name; 
    //     //    }
    //     //    $data_arr[]=$record->toArray(); 
    //     //    $data_arr[$key]['name']=$busNames;
    //     //    $data_arr[$key]['created_at']=date('j M Y h:i a',strtotime($record->created_at));
    //     //    $data_arr[$key]['updated_at']=date('j M Y h:i a',strtotime($record->updated_at));
    //     // } 
    //    $data_arr = array();
    //     foreach($records as $key=>$record)
    //     {
    //         $data_arr[]=$record->toArray();
    //         $data_arr[$key]['created_at']=date('j M Y h:i a',strtotime($record->created_at));
    //         $data_arr[$key]['updated_at']=date('j M Y h:i a',strtotime($record->updated_at)); 
    //         $data_arr[$key]['date_applied']=date('m/d/Y',strtotime($record->date_applied));
    //     }
    //     $response = array(
    //         "draw" => intval($draw),
    //         "iTotalRecords" => $totalRecords,
    //         "iTotalDisplayRecords" => $totalRecordswithFilter,
    //         "aaData" => $data_arr
    //     ); 
    //     return ($response);
    // }


  

    
   

}