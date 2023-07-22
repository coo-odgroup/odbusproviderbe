<?php

namespace App\Repositories;

// use App\Models\Bus;
use App\Models\SeatOpen;
use App\Models\SeatOpenSeats;

use App\Models\BusSeats;
use App\Models\Bus;
use App\Models\Location;
use App\Models\TicketPrice;
use App\Models\Booking;
use App\Models\BookingDetail;




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
    protected $ticketPrice;
    protected $booking;
    protected $bookingDetail;
    
    public function __construct(SeatOpen $seatOpen , SeatOpenSeats $seatsOpenSeats ,BusSeats  
        $busSeats,Bus $bus,Location $location, TicketPrice $ticketPrice ,Booking $booking, BookingDetail $bookingDetail)
    {
        $this->seatOpen = $seatOpen;
        $this->seatOpenSeats = $seatsOpenSeats;
        $this->busSeats = $busSeats;
        $this->bus = $bus;
        $this->location = $location;  
        $this->ticketPrice = $ticketPrice; 
        $this->booking = $booking; 
        $this->bookingDetail = $bookingDetail; 
       
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
        // $date= $data->date;
        // $all_date=[];
        // if(!empty($date))
        // {
        //     foreach ($date as  $d) {
        //         if(strlen($d['month'])==1)
        //         {
        //             $d['month']="0".$d['month'];
        //         }
        //         if(strlen($d['day'])==1)
        //         {
        //             $d['day']="0".$d['day'];
        //         }

        //         $all_date[] = $d['year'].'-'.$d['month'].'-'.$d['day'] ;   
        //     }
        // }
        $date= $data->date;
        $all_date=[];


        
        if(!empty($date))
        {
            foreach ($date as  $d) {
                $all_date[] = date('Y-m-d', strtotime($d)); 
            }
        }
       
        $layoutArray=$data['bus_seat_layout_data'];
        $get_ticket_price_id= $data['busRoute'];

        ////////// check Duplicate Data (return if exist or proceed to insert)

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
                                            $error['message']="Seat no ".$upperBerthData['seatText']." is already Opened for date - ".$dt;

                                            return $error;

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
                                            $error['message']="Seat no ".$lowerBerthData['seatText']." is already Opened for date - ".$dt;

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
        

            ///////////////////////////////////////
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
                                            // Log::info($busseats);
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

                                            // Log::info($busseats);                                
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

    public function addseatOpenByOperator($data)
    {
        $date= $data->date;
        $all_date=[];


        
        if(!empty($date))
        {
            foreach ($date as  $d) {
                $all_date[] = date('Y-m-d', strtotime($d)); 
            }
        }

        $layoutArray=$data['bus_seat_layout_data'];
        $get_ticket_price_id= $data['busRoute'];

         ////////// check Duplicate Data (return if exist or proceed to insert)

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
                                         /////////////// check if same seat is already Opened
                                         $chk_duplicate=$this->busSeats->where("bus_id",$data['bus_id'])
                                         ->where("seats_id",$upperBerthData['seatId'])
                                         ->where("ticket_price_id",$ticketpriceID)
                                         ->where("operation_date",$dt)
                                         ->where("type",$data['type'])                                         
                                         ->where("status",1)
                                         ->get(); 

                                            if(count($chk_duplicate)>0){

                                            $error['status']='error';
                                            $error['message']="Seat no ".$upperBerthData['seatText']." is already Opened for date - ".$dt;

                                            return $error;

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
                                         /////////////// check if same seat is already Opened

                                         $chk_duplicate=$this->busSeats->where("bus_id",$data['bus_id'])
                                         ->where("seats_id",$lowerBerthData['seatId'])
                                         ->where("ticket_price_id",$ticketpriceID)
                                         ->where("operation_date",$dt)
                                         ->where("type",$data['type'])                                         
                                         ->where("status",1)
                                         ->get(); 

                                            if(count($chk_duplicate)>0){

                                            $error['status']='error';
                                            $error['message']="Seat no ".$lowerBerthData['seatText']." is already Opened for date - ".$dt;

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
        
        ////////////////////////////////

        
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
                                        // Log::info($busseats);
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

                                        // Log::info($busseats);                                
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

    public function updateSeatOpenData($data)
    {
       

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


        $seatOpendt = $this->busSeats
                         ->where('bus_id',$data['bus_id'])
                         ->where('operation_date',$data['date'])
                         ->where('type',$data['type'])
                         ->update(['status'=> '2']);
        

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


    public function seatopenData($request)
    {
        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;
        $bus_id = $request['bus_id'] ;
        $page_no = str_replace("/api/seatopenData?&page=","",$request['page_no']);  ;
        $fromDate = $request['fromDate'] ;
        $toDate = $request['toDate'] ;
        $bus_operator_id = $request['bus_operator_id'] ;
        $source_id = $request['source_id'] ;
        $destination_id = $request['destination_id'] ;
       
        // exit;
    
        $data= $this->busSeats->with('bus.busOperator','bus.ticketPrice','seats','ticketPrice')
                                // ->with(["ticketPrice" => function($t){
                                //     $t->with("source");
                                //     $t->with("destination");
                                // }])
                              ->where('type',1)
                              ->whereNotIn('status', [2]);

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
        
        if(!empty($bus_id))
        {
            $data=$data->where('bus_id',$bus_id);
        }  
 
       
        $data=$data->get()->groupBy(['bus_id','operation_date','ticket_price_id']);
     
       // return $data;
     

        if($data)
        {
             foreach($data as $date){

                foreach ($date as $route) {
                   foreach ($route as $seatOp)
                    {
                       foreach ($seatOp as $SingleseatOp)
                        {
                          
                            // $SingleseatOp['source']=$this->location->where('id', $SingleseatOp->ticketPrice->source_id)->get();
                            // $SingleseatOp['destination']=$this->location->where('id', $SingleseatOp->ticketPrice->destination_id)->get(); 

                            $SingleseatOp['bus_source']=$this->location->where('id', $SingleseatOp->bus->ticketPrice[0]->source_id)->get();
                            $SingleseatOp['bus_destination']=$this->location->where('id', $SingleseatOp->bus->ticketPrice[0]->destination_id)->get(); 
                        }break;
                    }
                }
            }
        }

        

       $result = $this->customPaginate($data,$paginate,$page_no)->withPath('/api/seatopenData?');
        return $result;          

    }

    public function customPaginate($items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function alreadyOpen($request)
    {
        $check_dt = date('Y-m-d', strtotime('today - 1 days'));

        $ticketPrice = $this->ticketPrice->where('bus_id',$request->bus_id)->get();
        $data = $this->busSeats->with('seats')
                          ->where('bus_id',$request->bus_id)
                          ->where('operation_date','>',$check_dt)
                          ->where('ticket_price_id',$ticketPrice[0]->id)
                          ->where('type',1)
                          ->where('status', 1)->get()->groupBy(['operation_date']);
        return $data;
    } 

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
                         ->where('operation_date',$request['operationDate'])
                         ->where('type',$request['type'])
                         ->update(['status'=> '2']);
        return $seatOpen;
    }


    public function editseatOpen($request)
    {
           $seatOpen = $this->busSeats->with('bus','seats')
                         ->where('bus_id',$request['bus_id'])
                         ->where('operation_date',$request['operation_date'])
                         ->where('type',$request['type'])
                         ->where('ticket_price_id',$request['ticket_price_id'])
                         ->where('status',1)
                         ->get();
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