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
class SeatBlockRepository
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

    // public function removeSeatBlockCornJob()
    // {
    //    $today=date('Y-m-d');
    //    $checkdate =date('Y-m-d', strtotime($today. ' -70 days'));

    //     $data = $this->busSeats
    //                       ->where('type',2)
    //                       ->whereNotIn('status', [2])
    //                       ->where('operation_date','<',$checkdate)->get(); 

    //     // $seatblockData =$this->busSeats->where('type',2)
    //     //                 ->whereNotIn('status', [2])
    //     //                 ->where('operation_date','<',$checkdate)->delete();
    //     Log::info('The Total Seat Block Data Deleted '.$data->count()); 

    //     return $data ;
    // }

    public function addseatBlock($data)
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
        // Log::info($all_date);exit;

        $layoutArray=$data['bus_seat_layout_data'];
        $get_ticket_price_id= $data['busRoute'];

        ////////// check blocked / booked/hold seats (return if exist or proceed to insert)

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


                                         /////////////// check if same seat is already booked


                                         $chk_duplicate=$this->busSeats->where("bus_id",$data['bus_id'])
                                         ->where("seats_id",$upperBerthData['seatId'])
                                         ->where("ticket_price_id",$ticketpriceID)
                                         ->where("operation_date",$dt)
                                         ->where("type",$data['type'])                                         
                                         ->where("status",1)
                                         ->get(); 

                                            if(count($chk_duplicate)>0){

                                            $error['status']='error';
                                            $error['message']="Seat no ".$upperBerthData['seatText']." is already blocked for date - ".$dt;

                                            return $error;

                                            }
                                        
                                        
                                        /////// before insert we need to check if the seat is booked by customer or not

                                         $getRoutes=  $this->ticketPrice->where("id",$ticketpriceID)->get();

                                         $src_id=$getRoutes[0]->source_id;
                                         $dest_id=$getRoutes[0]->destination_id;

                                        $bookedSeatList= $this->booking->where("bus_id",$data['bus_id'])
                                                        ->where("journey_dt",$dt)
                                                        ->where("source_id",$src_id)
                                                        ->where("destination_id",$dest_id)
                                                        ->where("status",[1,4])
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


                                         /////////////// check if same seat is already booked


                                         $chk_duplicate=$this->busSeats->where("bus_id",$data['bus_id'])
                                         ->where("seats_id",$lowerBerthData['seatId'])
                                         ->where("ticket_price_id",$ticketpriceID)
                                         ->where("operation_date",$dt)
                                         ->where("type",$data['type'])                                         
                                         ->where("status",1)
                                         ->get(); 

                                            if(count($chk_duplicate)>0){

                                            $error['status']='error';
                                            $error['message']="Seat no ".$lowerBerthData['seatText']." is already blocked for date - ".$dt;

                                            return $error;

                                            }


                                         /////// before insert we need to check if the seat is booked by customer or not

                                         $getRoutes=  $this->ticketPrice->where("id",$ticketpriceID)->get();

                                         $src_id=$getRoutes[0]->source_id;
                                         $dest_id=$getRoutes[0]->destination_id;

                                        $bookedSeatList= $this->booking->where("bus_id",$data['bus_id'])
                                                        ->where("journey_dt",$dt)
                                                        ->where("source_id",$src_id)
                                                        ->where("destination_id",$dest_id)
                                                        ->where("status",[1,4])
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

                                  
                                    }
                                }
                            }
                        }                      
                    }
                }
            }
        }
        

        ////////////////////////////////////
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


                                         /////////////// check if same seat is already booked


                                         $chk_duplicate=$this->busSeats->where("bus_id",$data['bus_id'])
                                         ->where("seats_id",$upperBerthData['seatId'])
                                         ->where("ticket_price_id",$ticketpriceID)
                                         ->where("operation_date",$dt)
                                         ->where("type",$data['type'])                                         
                                         ->where("status",1)
                                         ->get(); 

                                            if(count($chk_duplicate)>0){

                                            $error['status']='error';
                                            $error['message']="Seat no ".$upperBerthData['seatText']." is already blocked for date - ".$dt;

                                            return $error;

                                            }
                                        
                                        
                                        /////// before insert we need to check if the seat is booked by customer or not

                                         $getRoutes=  $this->ticketPrice->where("id",$ticketpriceID)->get();

                                         $src_id=$getRoutes[0]->source_id;
                                         $dest_id=$getRoutes[0]->destination_id;

                                        $bookedSeatList= $this->booking->where("bus_id",$data['bus_id'])
                                                        ->where("journey_dt",$dt)
                                                        ->where("source_id",$src_id)
                                                        ->where("destination_id",$dest_id)
                                                        ->where("status",[1,4])
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
                                        $busseats->type = $data['type'];
                                        $busseats->created_by = $data['created_by'];
                                        $busseats->reason = $data['reason'];   
                                        $busseats->other_reason = $data['other_reson'];

                                        $busseats->save(); 

                                        ///////////////////////////////////////////////////
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


                                         /////////////// check if same seat is already booked


                                         $chk_duplicate=$this->busSeats->where("bus_id",$data['bus_id'])
                                         ->where("seats_id",$lowerBerthData['seatId'])
                                         ->where("ticket_price_id",$ticketpriceID)
                                         ->where("operation_date",$dt)
                                         ->where("type",$data['type'])                                         
                                         ->where("status",1)
                                         ->get(); 

                                            if(count($chk_duplicate)>0){

                                            $error['status']='error';
                                            $error['message']="Seat no ".$lowerBerthData['seatText']." is already blocked for date - ".$dt;

                                            return $error;

                                            }


                                         /////// before insert we need to check if the seat is booked by customer or not

                                         $getRoutes=  $this->ticketPrice->where("id",$ticketpriceID)->get();

                                         $src_id=$getRoutes[0]->source_id;
                                         $dest_id=$getRoutes[0]->destination_id;

                                        $bookedSeatList= $this->booking->where("bus_id",$data['bus_id'])
                                                        ->where("journey_dt",$dt)
                                                        ->where("source_id",$src_id)
                                                        ->where("destination_id",$dest_id)
                                                        ->where("status",[1,4])
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
                                        $busseats->type = $data['type'];
                                        $busseats->created_by = $data['created_by'];
                                        $busseats->reason = $data['reason'];                  
                                        $busseats->other_reason = $data['other_reson'];
                                                 // log::info($busseats);
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

    public function addseatBlockByOperator($data)
    {
        $date= $data->date;
        $all_date=[];
        
        if(!empty($date))
        {
            foreach ($date as  $d) {
                $all_date[] = date('Y-m-d', strtotime($d)); 
            }
        }

        // Log::info($all_date);exit;

        $layoutArray=$data['bus_seat_layout_data'];
        $get_ticket_price_id= $data['busRoute'];

        ////////// check blocked / booked/hold seats (return if exist or proceed to insert)

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


                                         /////////////// check if same seat is already booked


                                         $chk_duplicate=$this->busSeats->where("bus_id",$data['bus_id'])
                                         ->where("seats_id",$upperBerthData['seatId'])
                                         ->where("ticket_price_id",$ticketpriceID)
                                         ->where("operation_date",$dt)
                                         ->where("type",$data['type'])                                         
                                         ->where("status",1)
                                         ->get(); 

                                            if(count($chk_duplicate)>0){

                                            $error['status']='error';
                                            $error['message']="Seat no ".$upperBerthData['seatText']." is already blocked for date - ".$dt;

                                            return $error;

                                            }
                                        
                                        
                                        /////// before insert we need to check if the seat is booked by customer or not

                                         $getRoutes=  $this->ticketPrice->where("id",$ticketpriceID)->get();

                                         $src_id=$getRoutes[0]->source_id;
                                         $dest_id=$getRoutes[0]->destination_id;

                                        $bookedSeatList= $this->booking->where("bus_id",$data['bus_id'])
                                                        ->where("journey_dt",$dt)
                                                        ->where("source_id",$src_id)
                                                        ->where("destination_id",$dest_id)
                                                        ->where("status",[1,4])
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


                                         /////////////// check if same seat is already booked


                                         $chk_duplicate=$this->busSeats->where("bus_id",$data['bus_id'])
                                         ->where("seats_id",$lowerBerthData['seatId'])
                                         ->where("ticket_price_id",$ticketpriceID)
                                         ->where("operation_date",$dt)
                                         ->where("type",$data['type'])                                         
                                         ->where("status",1)
                                         ->get(); 

                                            if(count($chk_duplicate)>0){

                                            $error['status']='error';
                                            $error['message']="Seat no ".$lowerBerthData['seatText']." is already blocked for date - ".$dt;

                                            return $error;

                                            }


                                         /////// before insert we need to check if the seat is booked by customer or not

                                         $getRoutes=  $this->ticketPrice->where("id",$ticketpriceID)->get();

                                         $src_id=$getRoutes[0]->source_id;
                                         $dest_id=$getRoutes[0]->destination_id;

                                        $bookedSeatList= $this->booking->where("bus_id",$data['bus_id'])
                                                        ->where("journey_dt",$dt)
                                                        ->where("source_id",$src_id)
                                                        ->where("destination_id",$dest_id)
                                                        ->where("status",[1,4])
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

                                  
                                    }
                                }
                            }
                        }                      
                    }
                }
            }
        }
        

        ////////////////////////////////////
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


                                         /////////////// check if same seat is already booked


                                         $chk_duplicate=$this->busSeats->where("bus_id",$data['bus_id'])
                                         ->where("seats_id",$upperBerthData['seatId'])
                                         ->where("ticket_price_id",$ticketpriceID)
                                         ->where("operation_date",$dt)
                                         ->where("type",$data['type'])                                         
                                         ->where("status",1)
                                         ->get(); 

                                            if(count($chk_duplicate)>0){

                                            $error['status']='error';
                                            $error['message']="Seat no ".$upperBerthData['seatText']." is already blocked for date - ".$dt;

                                            return $error;

                                            }
                                        
                                        
                                        /////// before insert we need to check if the seat is booked by customer or not

                                         $getRoutes=  $this->ticketPrice->where("id",$ticketpriceID)->get();

                                         $src_id=$getRoutes[0]->source_id;
                                         $dest_id=$getRoutes[0]->destination_id;

                                        $bookedSeatList= $this->booking->where("bus_id",$data['bus_id'])
                                                        ->where("journey_dt",$dt)
                                                        ->where("source_id",$src_id)
                                                        ->where("destination_id",$dest_id)
                                                        ->where("status",[1,4])
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
                                        $busseats->type = $data['type'];
                                        $busseats->created_by = $data['created_by'];
                                        $busseats->reason = $data['reason'];   
                                        $busseats->other_reason = $data['other_reson'];

                                        $busseats->save(); 

                                        ///////////////////////////////////////////////////
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


                                         /////////////// check if same seat is already booked


                                         $chk_duplicate=$this->busSeats->where("bus_id",$data['bus_id'])
                                         ->where("seats_id",$lowerBerthData['seatId'])
                                         ->where("ticket_price_id",$ticketpriceID)
                                         ->where("operation_date",$dt)
                                         ->where("type",$data['type'])                                         
                                         ->where("status",1)
                                         ->get(); 

                                            if(count($chk_duplicate)>0){

                                            $error['status']='error';
                                            $error['message']="Seat no ".$lowerBerthData['seatText']." is already blocked for date - ".$dt;

                                            return $error;

                                            }


                                         /////// before insert we need to check if the seat is booked by customer or not

                                         $getRoutes=  $this->ticketPrice->where("id",$ticketpriceID)->get();

                                         $src_id=$getRoutes[0]->source_id;
                                         $dest_id=$getRoutes[0]->destination_id;

                                        $bookedSeatList= $this->booking->where("bus_id",$data['bus_id'])
                                                        ->where("journey_dt",$dt)
                                                        ->where("source_id",$src_id)
                                                        ->where("destination_id",$dest_id)
                                                        ->where("status",[1,4])
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
                                        $busseats->type = $data['type'];
                                        $busseats->created_by = $data['created_by'];
                                        $busseats->reason = $data['reason'];                  
                                        $busseats->other_reason = $data['other_reson'];
                                                 // log::info($busseats);
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


    
    public function updateSeatBlockData($data)
    {
       // log::info($data);
       // exit;

        $layoutArray=$data['bus_seat_layout_data'];
        $get_ticket_price_id= $data['busRoute'];

        ////////// check blocked / booked/hold seats (return if exist or proceed to insert)

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
                                    // foreach ($all_date as $dt) 
                                    // {    


                                         /////////////// check if same seat is already booked


                                         // $chk_duplicate=$this->busSeats->where("bus_id",$data['bus_id'])
                                         // ->where("seats_id",$upperBerthData['seatId'])
                                         // ->where("ticket_price_id",$ticketpriceID)
                                         // ->where("operation_date",$data['date'])
                                         // ->where("type",$data['type'])                                         
                                         // ->where("status",1)
                                         // ->get(); 

                                         //    if(count($chk_duplicate)>0){

                                         //    $error['status']='error';
                                         //    $error['message']="Seat no ".$upperBerthData['seatText']." is already blocked for date - ".$data['date'];

                                         //    return $error;

                                         //    }
                                        
                                        
                                        /////// before insert we need to check if the seat is booked by customer or not

                                         $getRoutes=  $this->ticketPrice->where("id",$ticketpriceID)->get();

                                         $src_id=$getRoutes[0]->source_id;
                                         $dest_id=$getRoutes[0]->destination_id;

                                        $bookedSeatList= $this->booking->where("bus_id",$data['bus_id'])
                                                        ->where("journey_dt",$data['date'])
                                                        ->where("source_id",$src_id)
                                                        ->where("destination_id",$dest_id)
                                                        ->where("status",[1,4])
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

                                    // }
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
                                    


                                         /////////////// check if same seat is already booked


                                         // $chk_duplicate=$this->busSeats->where("bus_id",$data['bus_id'])
                                         // ->where("seats_id",$lowerBerthData['seatId'])
                                         // ->where("ticket_price_id",$ticketpriceID)
                                         // ->where("operation_date",$data['date'])
                                         // ->where("type",$data['type'])                                         
                                         // ->where("status",1)
                                         // ->get(); 

                                         //    if(count($chk_duplicate)>0){

                                         //    $error['status']='error';
                                         //    $error['message']="Seat no ".$lowerBerthData['seatText']." is already blocked for date - ".$data['date'];

                                         //    return $error;

                                         //    }


                                         /////// before insert we need to check if the seat is booked by customer or not

                                         $getRoutes=  $this->ticketPrice->where("id",$ticketpriceID)->get();

                                         $src_id=$getRoutes[0]->source_id;
                                         $dest_id=$getRoutes[0]->destination_id;

                                        $bookedSeatList= $this->booking->where("bus_id",$data['bus_id'])
                                                        ->where("journey_dt",$data['date'])
                                                        ->where("source_id",$src_id)
                                                        ->where("destination_id",$dest_id)
                                                        ->where("status",[1,4])
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
                                }
                            }
                        }                      
                    }
                }
            }
        }


        $seatBlock = $this->busSeats
                         ->where('bus_id',$data['bus_id'])
                         ->where('operation_date',$data['date'])
                         ->where('type',$data['type'])
                         ->delete();
        

        ////////////////////////////////////
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
                                       


                                         /////////////// check if same seat is already booked


                                         $chk_duplicate=$this->busSeats->where("bus_id",$data['bus_id'])
                                         ->where("seats_id",$upperBerthData['seatId'])
                                         ->where("ticket_price_id",$ticketpriceID)
                                         ->where("operation_date",$data['date'])
                                         ->where("type",$data['type'])                                         
                                         ->where("status",1)
                                         ->get(); 

                                            if(count($chk_duplicate)>0){

                                            $error['status']='error';
                                            $error['message']="Seat no ".$upperBerthData['seatText']." is already blocked for date - ".$data['date'];

                                            return $error;

                                            }
                                        
                                        
                                        /////// before insert we need to check if the seat is booked by customer or not

                                         $getRoutes=  $this->ticketPrice->where("id",$ticketpriceID)->get();

                                         $src_id=$getRoutes[0]->source_id;
                                         $dest_id=$getRoutes[0]->destination_id;

                                        $bookedSeatList= $this->booking->where("bus_id",$data['bus_id'])
                                                        ->where("journey_dt",$data['date'])
                                                        ->where("source_id",$src_id)
                                                        ->where("destination_id",$dest_id)
                                                        ->where("status",[1,4])
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
                                        $busseats->operation_date = $data['date'];
                                        $busseats->status = '1';
                                        $busseats->type = $data['type'];
                                        $busseats->created_by = $data['created_by'];
                                        $busseats->reason = $data['reason'];   
                                        $busseats->other_reason = $data['other_reson'];

                                        $busseats->save(); 

                                        ///////////////////////////////////////////////////
                                    
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
                                    


                                         /////////////// check if same seat is already booked


                                         $chk_duplicate=$this->busSeats->where("bus_id",$data['bus_id'])
                                         ->where("seats_id",$lowerBerthData['seatId'])
                                         ->where("ticket_price_id",$ticketpriceID)
                                         ->where("operation_date",$data['date'])
                                         ->where("type",$data['type'])                                         
                                         ->where("status",1)
                                         ->get(); 

                                            if(count($chk_duplicate)>0){

                                            $error['status']='error';
                                            $error['message']="Seat no ".$lowerBerthData['seatText']." is already blocked for date - ".$data['date'];

                                            return $error;

                                            }


                                         /////// before insert we need to check if the seat is booked by customer or not

                                         $getRoutes=  $this->ticketPrice->where("id",$ticketpriceID)->get();

                                         $src_id=$getRoutes[0]->source_id;
                                         $dest_id=$getRoutes[0]->destination_id;

                                        $bookedSeatList= $this->booking->where("bus_id",$data['bus_id'])
                                                        ->where("journey_dt",$data['date'])
                                                        ->where("source_id",$src_id)
                                                        ->where("destination_id",$dest_id)
                                                        ->where("status",[1,4])
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
                                        $busseats->operation_date = $data['date'];
                                        $busseats->status = '1';
                                        $busseats->type = $data['type'];
                                        $busseats->created_by = $data['created_by'];
                                        $busseats->reason = $data['reason'];                  
                                        $busseats->other_reason = $data['other_reson'];
                                                 // log::info($busseats);
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
        // log::info($request);exit;
        $seatBlock = $this->busSeats
                         ->where('bus_id',$request['bus_id'])
                         ->where('operation_date',$request['operationDate'])
                         ->where('type',$request['type'])
                         ->delete();
        return $seatBlock;
    }

    public function editseatblock($request)
    {   
        // log::info($request);

        $seatBlock = $this->busSeats->with('bus','seats')
                         ->where('bus_id',$request['bus_id'])
                         ->where('operation_date',$request['operation_date'])
                         ->where('type',$request['type'])
                         ->where('ticket_price_id',$request['ticket_price_id'])
                         ->get();

        // log::info($seatBlock);

        return $seatBlock;
    }




    public function seatblockData($request)
    {
        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;
        $bus_id = $request['bus_id'] ;
        $page_no = $request['page_no'] ;
        $fromDate = $request['fromDate'] ;
        $toDate = $request['toDate'] ;
        $bus_operator_id = $request['bus_operator_id'] ;
        $source_id = $request['source_id'] ;
        $destination_id = $request['destination_id'] ;
         $check_dt = date('Y-m-d', strtotime('today - 3 days'));

        $data= $this->busSeats->with('bus.busOperator','seats','ticketPrice')
                              ->where('type',2)
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
        
        if($bus_operator_id!= null)
        {
            $data=$data->whereHas('bus', function ($query) use ($bus_operator_id){
               $query->where('bus_operator_id', $bus_operator_id);               
           });
        }

       

        if($toDate!= null && $fromDate!=null)
        {
              if($fromDate==$toDate){
                      $data = $data->where('operation_date',$toDate);
              }else{
                  $data = $data->whereBetween('operation_date', [$fromDate, $toDate]);
              } 
        } else{

                $data=$data->where('operation_date',date('Y-m-d'));
        } 


        if($name!=null)
        {
            $data = $data->whereHas('bus', function ($query) use ($name){
                $query->where('name', 'like', '%' .$name . '%');               
            })->orwhere('reason','like','%'.$name.'%') ;          
            
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

        if(!empty($bus_id))
        {
            $data=$data->where('bus_id',$bus_id);
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
                          
                            $SingleseatOp['source']=$this->location->where('id', $SingleseatOp->ticketPrice->source_id)->get();
                            $SingleseatOp['destination']=$this->location->where('id', $SingleseatOp->ticketPrice->destination_id)->get(); 
                            $SingleseatOp['bus_source']=$this->location->where('id', $SingleseatOp->bus->ticketPrice[0]->source_id)->get();
                            $SingleseatOp['bus_destination']=$this->location->where('id', $SingleseatOp->bus->ticketPrice[0]->destination_id)->get(); 
                        }
                    }
                }
            }
        }

        $result = $this->customPaginate($data,$paginate,$page_no)->withPath('/api/seatblockData');
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

    

    public function alreadyBlocks($request)
    {
          $check_dt = date('Y-m-d', strtotime('today - 1 days'));

          $ticketPrice = $this->ticketPrice->where('bus_id',$request->bus_id)->get();
          $data = $this->busSeats->with('seats')
                              ->where('bus_id',$request->bus_id)
                              ->where('operation_date','>',$check_dt)
                              ->where('ticket_price_id',$ticketPrice[0]->id)
                              ->where('type',2)
                              ->where('status', 1)->get()->groupBy(['operation_date']);
        return $data;

    }
   

}