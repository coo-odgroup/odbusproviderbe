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
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


/*Priyadarshi to Review*/
class ExtraSeatBlockRepository
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
    public function addExtraSeatBlock($data)
    {
        // Log::info($data);
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
                                    $busseats = new $this->busSeats;                           
                                    $busseats->bus_id = $data['bus_id'];
                                    $busseats->category = '0';
                                    $busseats->seats_id = $upperBerthData['seatId'];
                                    $busseats->ticket_price_id = $ticketpriceID;
                                    $busseats->operation_date = $data['date'];
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