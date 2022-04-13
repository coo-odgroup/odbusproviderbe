<?php

namespace App\Repositories;
// use App\Models\Bus;
use App\Models\SeatBlock;
use App\Models\SeatBlockSeats;

use App\Models\BusSeats;
use App\Models\Bus;
use App\Models\Location;

use App\Models\TicketPrice;
use App\Models\Booking;
use App\Models\BookingDetail;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


/*Priyadarshi to Review*/
class ExtraSeatBlockRepository
{
    
    protected $seatBlock;
    protected $ticketPrice;
    protected $booking;
    protected $bookingDetail;

    
    public function __construct(SeatBlock $seatBlock , SeatBlockSeats $seatsBlockSeats,BusSeats 
        $busSeats,Bus $bus,Location $location, TicketPrice $ticketPrice,Booking $booking, BookingDetail $bookingDetail)
    {
        $this->seatBlock = $seatBlock;
        $this->seatBlockSeats = $seatsBlockSeats;
        $this->busSeats = $busSeats;
        $this->bus = $bus;
        $this->location = $location;  
        $this->ticketPrice = $ticketPrice;  
        $this->booking = $booking;  
        $this->bookingDetail = $bookingDetail;  
       
    }    
    public function getAll()
    {
        return $this->seatBlock->with('seatBlockSeats')->with('bus','bus.busOperator')->get();

    }
    public function addExtraSeatBlock($data)
    {
        
        $date= $data->date;
        $all_date=[];
        if(!empty($date))
        {
            foreach ($date as  $d) {
                if(strlen($d['month'])==1)
                {
                    $d['month']="0".$d['month'];
                }
                if(strlen($d['day'])==1)
                {
                    $d['day']="0".$d['day'];
                }

                $all_date[] = $d['year'].'-'.$d['month'].'-'.$d['day'] ;   
            }
        }
        // exit;
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
                                    foreach ($all_date as $dt) 
                                    {
                                        
                                         /////// before insert we need to check if the seat is booked by customer or not

                                         $getRoutes=  $this->ticketPrice->where("id",$ticketpriceID)->get();

                                         $src_id=$getRoutes[0]->source_id;
                                         $dest_id=$getRoutes[0]->destination_id;

                                        $bookedSeatList= $this->booking->where("bus_id",$data['bus_id'])
                                                        ->where("journey_dt",$dt)
                                                        ->where("source_id",$src_id)
                                                        ->where("destination_id",$dest_id)
                                                        ->where("status",'!=',2)
                                                        ->get();

                                        if(count($bookedSeatList)>0){
                                            foreach($bookedSeatList as $booked){

                                                $GetSeatIdList= $this->bookingDetail
                                                                ->with('BusSeats')
                                                                ->where("booking_id",$booked->id)
                                                                ->get();

                                                  if(count($GetSeatIdList)>0){

                                                    foreach($GetSeatIdList as $gs){

                                                        if($gs->BusSeats->seats_id == $upperBerthData['seatId']){

                                                            $error['status']='error';
                                                            $error['message']="Seat no ".$upperBerthData['seatText']." is already booked";

                                                            return $error;
                                                        }

                                                    }

                                                  }              



                                            }
                                        }   

                                        ////////////////////////////////////////////////

                                        $busseats = new $this->busSeats;                           
                                        $busseats->bus_id = $data['bus_id'];
                                        $busseats->category = '0';
                                        $busseats->seats_id = $upperBerthData['seatId'];
                                        $busseats->ticket_price_id = $ticketpriceID;
                                        $busseats->operation_date = $dt;
                                        $busseats->status = '1';
                                        $busseats->created_by = $data['created_by'];
                                        $busseats->reason = $data['reason'];   
                                        $busseats->other_reason = $data['other_reson'];
                                        $busseats->save(); 
                                    }
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
                                    foreach ($all_date as $dt) 
                                    {

                                         /////// before insert we need to check if the seat is booked by customer or not

                                         $getRoutes=  $this->ticketPrice->where("id",$ticketpriceID)->get();

                                         $src_id=$getRoutes[0]->source_id;
                                         $dest_id=$getRoutes[0]->destination_id;

                                        $bookedSeatList= $this->booking->where("bus_id",$data['bus_id'])
                                                        ->where("journey_dt",$dt)
                                                        ->where("source_id",$src_id)
                                                        ->where("destination_id",$dest_id)
                                                        ->where("status",'!=',2)
                                                        ->get();

                                        if(count($bookedSeatList)>0){
                                            foreach($bookedSeatList as $booked){

                                                $GetSeatIdList= $this->bookingDetail
                                                                ->with('BusSeats')
                                                                ->where("booking_id",$booked->id)
                                                                ->get();

                                                  if(count($GetSeatIdList)>0){

                                                    foreach($GetSeatIdList as $gs){

                                                        if($gs->BusSeats->seats_id == $lowerBerthData['seatId']){

                                                            $error['status']='error';
                                                            $error['message']="Seat no ".$lowerBerthData['seatText']." is already booked";

                                                            return $error;
                                                        }

                                                    }

                                                  }              



                                            }
                                        }                

                                              

                                        ////////////////////////////////////////////////

                                        $busseats = new $this->busSeats;                            
                                        $busseats->bus_id = $data['bus_id'];
                                        $busseats->category = '0';
                                        $busseats->seats_id = $lowerBerthData['seatId'];
                                        $busseats->ticket_price_id = $ticketpriceID;
                                        $busseats->operation_date = $dt;
                                        $busseats->status = '1';
                                        $busseats->created_by = $data['created_by'];
                                        $busseats->reason = $data['reason'];                
                                        $busseats->other_reason = $data['other_reson'];
                                        $busseats->save(); 
                                    }
                                }
                            }
                        }                      
                    }
                }
            }
        }
        return $data;
    }

    

   

    public function deleteExtraSeatBlock($request)
    {        
        $seatBlock = $this->busSeats
                         ->where('bus_id',$request['bus_id'])
                         ->where('ticket_price_id',$request['ticketPriceId'])
                         ->where('operation_date',$request['operationDate'])
                         ->update(['status'=> '2']);
        return $seatBlock;
    }

    public function extraSeatBlockData($request)
    {
        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;
        $page_no = $request['page_no'] ;
        $date = $request['date'] ;
        $source_id = $request['source_id'] ;
        $destination_id = $request['destination_id'] ;
    
        $data= $this->busSeats->with('bus.busOperator','seats','ticketPrice')
                              ->where('type',null)
                              ->where('operation_date','!=',null)
                              ->where('status','1');

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

        if($name!=null)
        {
            $data = $data->whereHas('bus', function ($query) use ($name){
                $query->where('name', 'like', '%' .$name . '%');               
            }) ;          
        }  
        if(!empty($source_id) && !empty($destination_id))
        {
            $data=$data->whereHas('ticketPrice', function ($query)use ($request){
               $query->where('source_id',$request['source_id'] )->where('destination_id',$request['destination_id']);               
           });
        }  
         if(!empty($date))
        {
            $data=$data->where('operation_date',$date);
        }  
 
       
        $data=$data->get()->groupBy(['bus_id','operation_date','ticket_price_id']);
         // log::info($data); 
         // exit;

        if($data)
        {
             foreach($data as $date){

                foreach ($date as $route) {
                   foreach ($route as $seatOp)
                    {
                       foreach ($seatOp as $SingleseatOp)
                        {
                            $SingleseatOp['source']=$this->location->where('id', $SingleseatOp->ticketPrice->source_id)->get();
                            $SingleseatOp['destination']=$this->location->where('id', $SingleseatOp->ticketPrice->destination_id)->get(); 
                        }
                    }
                }
            }
        }

        

       $result = $this->customPaginate($data,$paginate,$page_no)->withPath('/api/seatblockData');
         // log::info($result); 
        return $result;          
 
    }

    public function customPaginate($items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
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