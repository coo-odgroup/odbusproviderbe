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
use App\Models\BusSeatCount;
use App\Models\BookingDetail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;


/*Priyadarshi to Review*/
class SeatBlockRepository
{
    
    protected $seatBlock;
    protected $ticketPrice;
    protected $booking;
    protected $bookingDetail;
    protected $busSeats;

    
    public function __construct(SeatBlock $seatBlock , SeatBlockSeats $seatsBlockSeats,BusSeats $busSeats,Bus $bus,Location $location, TicketPrice $ticketPrice,Booking $booking, BookingDetail $bookingDetail)
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
        DB::beginTransaction();

        try {

            // 1️⃣ Prepare dates
            $dates = collect($data->date ?? [])
                ->map(fn($d) => date('Y-m-d', strtotime($d)))
                ->toArray();

            // 2️⃣ Collect selected seats (upper + lower)
            $selectedSeats = [];

            foreach ($data['bus_seat_layout_data'] as $layout) {
                foreach (['upperBerth', 'lowerBerth'] as $berth) {
                    if (!empty($layout[$berth])) {
                        foreach ($layout[$berth] as $seat) {
                            if (($seat['seatChecked'] ?? false) === true) {
                                    $selectedSeats[] = $seat;
                            }

                        }
                    }
                }
            }

            if (empty($selectedSeats)) {
                return ['status' => 'error', 'message' => 'No seats selected'];
            }

            // 3️⃣ Load ticket routes once
            $routes = $this->ticketPrice
                ->whereIn('id', $data['busRoute'])
                ->get()
                ->keyBy('id');

            // 4️⃣ Validation: blocked / booked check
            foreach ($selectedSeats as $seat) {
                foreach ($data['busRoute'] as $ticketPriceId) {
                    foreach ($dates as $dt) {

                        // Already blocked?
                        $isBlocked = $this->busSeats
                            ->where([
                                'bus_id' => $data['bus_id'],
                                'seats_id' => $seat['seatId'],
                                'ticket_price_id' => $ticketPriceId,
                                'operation_date' => $dt,
                                'type' => $data['type'],
                                'status' => 1,
                            ])->exists();

                        if ($isBlocked) {
                            return [
                                'status' => 'error',
                                'message' => "Seat no {$seat['seatText']} is already blocked for date - {$dt}"
                            ];
                        }

                        // Already booked?
                        $route = $routes[$ticketPriceId];

                        $isBooked = $this->bookingDetail
                            ->whereHas('booking', function ($q) use ($data, $dt, $route) {
                                $q->where([
                                    'bus_id' => $data['bus_id'],
                                    'journey_dt' => $dt,
                                    'source_id' => $route->source_id,
                                    'destination_id' => $route->destination_id,
                                ])->whereIn('status', [1, 4]);
                            })
                            ->whereHas('BusSeats', function ($q) use ($seat) {
                                $q->where('seats_id', $seat['seatId']);
                            })
                            ->exists();

                        if ($isBooked) {
                            return [
                                'status' => 'error',
                                'message' => "Seat no {$seat['seatText']} is already booked"
                            ];
                        }
                    }
                }
            }

            // 5️⃣ Insert blocked seats
            foreach ($selectedSeats as $seat) {
                foreach ($data['busRoute'] as $ticketPriceId) {
                    foreach ($dates as $dt) {
                        $ins=[
                            'bus_id' => $data['bus_id'],
                            'category' => 0,
                            'seats_id' => $seat['seatId'],
                            'ticket_price_id' => $ticketPriceId,
                            'operation_date' => $dt,
                            'status' => 1,
                            'type' => $data['type'],
                            'created_by' => $data['created_by'],
                            'reason' => $data['reason'],
                            'other_reason' => $data['other_reson'],
                        ];
                        $this->busSeats->create($ins);
                    }
                }
            }

            $inventory = app(\App\Services\InventoryService::class);

            $seatCount = count($selectedSeats);

            foreach ($data['busRoute'] as $ticketPriceId) {

                foreach ($dates as $dt) {

                    $inventory->blockSeatsByTicketPrice(
                        $ticketPriceId,
                        $dt,
                        $seatCount
                    );
                }
            }


            DB::commit();
            return ['status' => 'success'];

        } catch (\Exception $e) {
            DB::rollBack();
             Log::info("rollback");
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }


    public function addseatBlockByOperator($data)
    {
        DB::beginTransaction();

        try {

            /* 1️⃣ Prepare dates */
            $dates = collect($data->date ?? [])
                ->map(fn($d) => date('Y-m-d', strtotime($d)))
                ->toArray();

            /* 2️⃣ Collect selected seats (upper + lower) */
            $selectedSeats = [];

            foreach ($data['bus_seat_layout_data'] as $layout) {
                foreach (['upperBerth', 'lowerBerth'] as $berth) {
                    if (!empty($layout[$berth])) {
                        foreach ($layout[$berth] as $seat) {
                            if (($seat['seatChecked'] ?? false) === true) {
                                $selectedSeats[] = $seat;
                            }
                        }
                    }
                }
            }

            if (empty($selectedSeats)) {
                return ['status' => 'error', 'message' => 'No seats selected'];
            }

            /* 3️⃣ Load routes once */
            $routes = $this->ticketPrice
                ->whereIn('id', $data['busRoute'])
                ->get()
                ->keyBy('id');

            /* 4️⃣ Validation: blocked / booked check */
            foreach ($selectedSeats as $seat) {
                foreach ($data['busRoute'] as $ticketPriceId) {
                    foreach ($dates as $dt) {

                        // Already blocked?
                        $isBlocked = $this->busSeats
                            ->where([
                                'bus_id' => $data['bus_id'],
                                'seats_id' => $seat['seatId'],
                                'ticket_price_id' => $ticketPriceId,
                                'operation_date' => $dt,
                                'type' => $data['type'],
                                'status' => 1,
                            ])->exists();

                        if ($isBlocked) {
                            return [
                                'status' => 'error',
                                'message' => "Seat no {$seat['seatText']} is already blocked for date - {$dt}"
                            ];
                        }

                        // Already booked?
                        $route = $routes[$ticketPriceId];

                       $isBooked = $this->bookingDetail
                            ->whereHas('booking', function ($q) use ($data, $dt, $route) {
                                $q->where([
                                    'bus_id' => $data['bus_id'],
                                    'journey_dt' => $dt,
                                    'source_id' => $route->source_id,
                                    'destination_id' => $route->destination_id,
                                ])->whereIn('status', [1, 4]);
                            })
                            ->whereHas('BusSeats', function ($q) use ($seat) {
                                $q->where('seats_id', $seat['seatId']);
                            })
                            ->exists();

                        if ($isBooked) {
                            return [
                                'status' => 'error',
                                'message' => "Seat no {$seat['seatText']} is already booked"
                            ];
                        }
                    }
                }
            }

            /* 5️⃣ Insert blocked seats */
            foreach ($selectedSeats as $seat) {
                foreach ($data['busRoute'] as $ticketPriceId) {
                    foreach ($dates as $dt) {

                        $this->busSeats->create([
                            'bus_id' => $data['bus_id'],
                            'category' => 0,
                            'seats_id' => $seat['seatId'],
                            'ticket_price_id' => $ticketPriceId,
                            'operation_date' => $dt,
                            'status' => 1,
                            'type' => $data['type'],
                            'created_by' => $data['created_by'],
                            'reason' => $data['reason'],
                            'other_reason' => $data['other_reson'],
                        ]);
                    }
                }
            }


            $inventory = app(\App\Services\InventoryService::class);

            $seatCount = count($selectedSeats);

            foreach ($data['busRoute'] as $ticketPriceId) {

                foreach ($dates as $dt) {

                    $inventory->blockSeatsByTicketPrice(
                        $ticketPriceId,
                        $dt,
                        $seatCount
                    );
                }
            }


            DB::commit();
            return ['status' => 'success'];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function addseatBlock_old($data)
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

    public function addseatBlockByOperator_old($data)
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


    
    public function updateSeatBlockData_old($data)
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

    public function updateSeatBlockData($data)
    {
        DB::beginTransaction();

        try {

            $requestedSeats = [];

            foreach ($data['bus_seat_layout_data'] as $layout) {

                foreach (['upperBerth', 'lowerBerth'] as $berth) {

                    if (!empty($layout[$berth])) {

                        foreach ($layout[$berth] as $seat) {

                            $requestedSeats[$seat['seatId']] =
                                filter_var(
                                    $seat['seatChecked'] ?? false,
                                    FILTER_VALIDATE_BOOLEAN
                                );
                        }
                    }
                }
            }

            foreach ($data['busRoute'] as $ticketPriceId) {

                $oldBlockedSeatCount = $this->busSeats
                    ->where('bus_id', $data['bus_id'])
                    ->where('ticket_price_id', $ticketPriceId)
                    ->where('operation_date', $data['date'])
                    ->where('type', 2)
                    ->where('status', 1)
                    ->count();


                $route = $this->ticketPrice->find($ticketPriceId);

                // booked seats cannot be blocked
                $bookedSeatIds = $this->bookingDetail
                    ->whereHas('booking', function ($q) use ($data, $route) {

                        $q->where('bus_id', $data['bus_id'])
                            ->where('journey_dt', $data['date'])
                            ->where('source_id', $route->source_id)
                            ->where('destination_id', $route->destination_id)
                            ->whereIn('status', [1, 4]);

                    })
                    ->pluck('bus_seats_id')
                    ->toArray();

                $existingSeats = $this->busSeats
                    ->where('bus_id', $data['bus_id'])
                    ->where('ticket_price_id', $ticketPriceId)
                    ->where('operation_date', $data['date'])
                    ->where('type', 2) // block seat
                    ->get()
                    ->groupBy('seats_id');

                foreach ($requestedSeats as $seatId => $isChecked) {

                    // skip booked seats
                    if (in_array($seatId, $bookedSeatIds)) {
                        continue;
                    }

                    $seatRows = $existingSeats->get($seatId, collect());

                    /*
                    |--------------------------------------------------------------------------
                    | BLOCK SEAT
                    |--------------------------------------------------------------------------
                    */
                    if ($isChecked) {

                        if ($seatRows->count() > 0) {

                            $first = $seatRows->first();

                            $first->update([
                                'status' => 1,
                                'reason' => $data['reason'],
                                'other_reason' => $data['other_reson']
                            ]);

                            // remove duplicates
                            if ($seatRows->count() > 1) {

                                $duplicateIds = $seatRows
                                    ->pluck('id')
                                    ->slice(1)
                                    ->values()
                                    ->toArray();

                                $this->busSeats
                                    ->whereIn('id', $duplicateIds)
                                    ->delete();
                            }

                        } else {

                            $this->busSeats->create([
                                'bus_id'          => $data['bus_id'],
                                'category'        => 0,
                                'seats_id'        => $seatId,
                                'ticket_price_id' => $ticketPriceId,
                                'operation_date'  => $data['date'],
                                'status'          => 1,
                                'type'            => 2,
                                'created_by'      => $data['created_by'],
                                'reason'          => $data['reason'],
                                'other_reason'    => $data['other_reson'],
                            ]);
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | UNBLOCK SEAT
                    |--------------------------------------------------------------------------
                    */
                    else {

                        if ($seatRows->count()) {

                            $this->busSeats
                                ->whereIn(
                                    'id',
                                    $seatRows->pluck('id')->toArray()
                                )
                                ->update([
                                    'status' => 2
                                ]);
                        }
                    }
                }

                $newBlockedSeatCount = $this->busSeats
                                    ->where('bus_id', $data['bus_id'])
                                    ->where('ticket_price_id', $ticketPriceId)
                                    ->where('operation_date', $data['date'])
                                    ->where('type', 2)
                                    ->where('status', 1)
                                    ->count();

                   BusSeatCount::where('ticket_price_id', $ticketPriceId)
                                ->where('journey_date', $data['date'])
                                ->update([
                                    'blocked_seat' => $newBlockedSeatCount
                                ]);

                    $inventory = app(\App\Services\InventoryService::class);

                    $inventory->refreshAvailableSeats(
                        [$ticketPriceId],
                        $data['date']
                    );
            }

            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Seat block data synced successfully'
            ];

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error(
                'updateSeatBlockData Error : ' .
                $e->getMessage()
            );

            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
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
        $inventory = app(\App\Services\InventoryService::class);

        $seatBlock = $this->busSeats
            ->where('bus_id', $request['bus_id'])
            ->where('operation_date', $request['operationDate'])
            ->where('type', $request['type'])
            ->delete();

        $routeIds = TicketPrice::where('bus_id', $request['bus_id'])
            ->pluck('id')
            ->toArray();

        foreach ($routeIds as $ticketPriceId) {

            $normalBlocked = $this->busSeats
                ->where('bus_id', $request['bus_id'])
                ->where('ticket_price_id', $ticketPriceId)
                ->where('operation_date', $request['operationDate'])
                ->where('type', 2)
                ->where('status', 1)
                ->count();

            $extraBlocked = $this->busSeats
                ->where('bus_id', $request['bus_id'])
                ->where('ticket_price_id', $ticketPriceId)
                ->where('operation_date', $request['operationDate'])
                ->whereNull('type')
                ->whereNotNull('duration')
                ->where('status', 1)
                ->count();

            BusSeatCount::where('ticket_price_id', $ticketPriceId)
                ->where('journey_date', $request['operationDate'])
                ->update([
                    'blocked_seat' => ($normalBlocked + $extraBlocked)
                ]);

            $inventory->refreshAvailableSeats(
                [$ticketPriceId],
                $request['operationDate']
            );
        }

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
        $paginate   = $request['rows_number'] ?? 10;
        $name       = $request['name'] ?? null;
        $bus_id     = $request['bus_id'] ?? null;
        $page_no    = $request['page_no'] ?? 1;
        $fromDate   = $request['fromDate'] ?? null;
        $toDate     = $request['toDate'] ?? null;
        $busOperatorId = $request['bus_operator_id'] ?? null;
        $source_id  = $request['source_id'] ?? null;
        $destination_id = $request['destination_id'] ?? null;
        $userBusOperatorId = $request['USER_BUS_OPERATOR_ID'] ?? null;

        if ($paginate === 'all') {
            $paginate = Config::get('constants.ALL_RECORDS');
        }

        $query = $this->busSeats
            ->select(
                'id','bus_id','ticket_price_id','seats_id',
                'type','operation_date','reason','other_reason',
                'status','updated_at','created_by'
            )
            ->where('type', 2)
            ->whereNotIn('status', [2])
            ->with([
                'bus:id,bus_operator_id,name,bus_number',
                'bus.busOperator:id,operator_name,organisation_name',
                'seats:id,seatText,berthType,bus_seat_layout_id',
                'ticketPrice:id,bus_id,source_id,destination_id'
            ]);

        /* ================= FILTERS ================= */

        if ($userBusOperatorId) {
            $query->whereHas('bus', fn($q) =>
                $q->where('bus_operator_id', $userBusOperatorId)
            );
        }

        if ($busOperatorId) {
            $query->whereHas('bus', fn($q) =>
                $q->where('bus_operator_id', $busOperatorId)
            );
        }

        if ($bus_id) {
            $query->where('bus_id', $bus_id);
        }

        if ($fromDate && $toDate) {
            $query->whereBetween('operation_date', [$fromDate, $toDate]);
        } else {
            $query->where('operation_date', now()->toDateString());
        }

        if ($name) {
            $query->where(function ($q) use ($name) {
                $q->whereHas('bus', fn($b) =>
                    $b->where('name', 'like', "%{$name}%")
                )->orWhere('reason', 'like', "%{$name}%");
            });
        }

        if ($source_id && $destination_id) {
            $query->whereHas('ticketPrice', fn($q) =>
                $q->where('source_id', $source_id)
                ->where('destination_id', $destination_id)
            );
        }

        /* ================= FETCH ================= */

        $data = $query
            ->orderBy('operation_date', 'desc')
            ->get()
            ->groupBy(['bus_id','operation_date','ticket_price_id']);

        /* ================= SOURCE / DESTINATION ================= */

        $locationIds = [];

        foreach ($data as $busGroup) {
            foreach ($busGroup as $dateGroup) {
                foreach ($dateGroup as $routeGroup) {
                    $tp = $routeGroup->first()->ticketPrice;
                    if ($tp) {
                        $locationIds[] = $tp->source_id;
                        $locationIds[] = $tp->destination_id;
                    }
                }
            }
        }

        $locations = $this->location
            ->whereIn('id', array_unique($locationIds))
            ->pluck('name','id');

        foreach ($data as $busGroup) {
            foreach ($busGroup as $dateGroup) {
                foreach ($dateGroup as $routeGroup) {
                    foreach ($routeGroup as $seat) {
                        $tp = $seat->ticketPrice;
                        $seat->bus_source = $locations[$tp->source_id] ?? null;
                        $seat->bus_destination = $locations[$tp->destination_id] ?? null;
                    }
                }
            }
        }

        /* ================= PAGINATION ================= */

        return $this->customPaginate($data, $paginate, $page_no)
            ->withPath('/api/seatblockData');
    }



    public function seatblockData_backup($request)
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
        

        $data= $this->busSeats->select('id','bus_id','ticket_price_id','seats_id','type','operation_date','reason','other_reason','status','updated_at','created_by')
        ->with(['bus' => function($query){
                        $query->select('id','bus_operator_id','name','bus_number') 
                        ->with(['busOperator' => function($quer) {
                             $quer->select('id','operator_name','organisation_name');
                            }]);
                        $query ->with(['ticketPrice' => function($quer) {
                             $quer->select('id','bus_id','source_id','destination_id');
                            }]);
                        }])

        ->with(['seats' => function($quer) {
                             $quer->select('id','berthType','seatText','bus_seat_layout_id');
                            }])
        ->with(['ticketPrice' => function($quer) {
                             $quer->select('id');
                            }])
        ->where('type',2)
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
                   foreach ($route as $kk=>$seatOp)
                    { 
                       foreach ($seatOp as $SingleseatOp)
                        {
                            $SingleseatOp['bus_source']=$this->location->select('name')->where('id', $SingleseatOp->bus->ticketPrice[0]->source_id)->get();
                            $SingleseatOp['bus_destination']=$this->location->select('name')->where('id', $SingleseatOp->bus->ticketPrice[0]->destination_id)->get(); 
                        }
                        break;
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