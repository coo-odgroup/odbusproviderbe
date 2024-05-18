<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\BusSeats;
use App\Models\TicketPrice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use DB;

/*Priyadarshi to Review*/
class BusSeatsRepository
{
    
    protected $busSeats;

    
    public function __construct(BusSeats $busSeats, TicketPrice $ticketPrice, Bus $bus)
    {
        $this->busSeats = $busSeats;
        $this->ticketPrice =$ticketPrice;
        $this->bus = $bus;
    }    
    public function getAll()
    {
        return $this->busSeats
        ->where('status','1')
        ->get();
    }
    public function getAllFare($busId)
    {
       // $result['busSeats']=$this->busSeats->where('bus_id',$busId)->orderBy('source_id','ASC')->get();//busSeats
       // $result['stoppageInfo']=$this->busStoppage->where('bus_id', $busId)->get();
     return $this->ticketPrice->with('getBusSeats.seats')
            ->whereHas('getBusSeats', function ($query) { 
                                                $query->where('status', '!=', '2');               
                                            })
            ->whereHas('getBusSeats.seats', function ($query) { 
                                                $query->where('status', '!=', '2');               
                                            })
            ->where('bus_id',$busId)->get();
 }
 public function getByBusId($busId)
 {
    $data =  $this->busSeats->whereHas('ticketPrice', function($q){
        $q->where('status', 1);
    })
    ->where('status','1')
    ->where('bus_id', $busId)
    //->where('type',null)
    ->get();
    // log::info($data);
    $duration = $this->busSeats
                    ->where('status','1')
                    ->where('bus_id', $busId)
                    ->where('type',null)
                    ->where('duration','!=',0)
                    ->limit(1)
                    ->get();
    // log::info($duration);


      $response = array(
            "seat" => $data, 
            "duration" => $duration
           ); 
      // log::info($response);

    return $response;
}

public function busextraSeatsByBus($busId)
 {
    $data = $this->busSeats
    ->where('status','1')
    ->where('bus_id', $busId)
    ->where('duration','>',0)
    ->where('type',null)
    ->get();
    // log::info($data);
    return $data;
}
public function getById($id)
{
    return $this->busSeats ->where('id', $id)->where('status','!=',2)->get();
}
public function getModel(BusSeats $busseats,$data,$berthData)
{
    
    $busseats->bus_id = $data['bus_id'];
    $busseats->category = $data['category'];
    $busseats->seats_id = $berthData['seatId'];
    $busseats->new_fare = $data['new_fare'];
    $busseats->ticket_price_id = $data['ticket_price_id'];
    $busseats->duration = $data['duration'];
    $busseats->status = '1';
    $busseats->created_by = $data['created_by'];
    return $busseats;
}
public function saveBusSeats($busseats,$data,$berthData)
{
    $busseats = new $this->busSeats;
    $busseats=$this->getModel($busseats,$data,$berthData);
    $busseats->save();
}
public function save($data)
{
 
    $layoutArray=$data['bus_seat_layout_data'];
    foreach($layoutArray as $sLayoutData)
    {
        if(isset($sLayoutData['upperBerth']))
        {
            if(count($sLayoutData['upperBerth'])>0)
            {
                foreach($sLayoutData['upperBerth'] as $upperBerthData)
                {
                    if($upperBerthData['seatChecked']==true)
                    {
                        $busseats = new $this->busSeats;
                        $busseats=$this->getModel($busseats,$data,$upperBerthData);
                        $busseats->save();
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
                    if($lowerBerthData['seatChecked']==true)
                    {
                        $busseats = new $this->busSeats;
                        $busseats= $this->getModel($busseats,$data,$lowerBerthData);
                        $busseats->save();
                    }
                    
                }
            }
        }
    }
    return $data;
}
public function updateNewFare($data)
{
    foreach ($data['fare_info'] as $singleSeat)   
    {
        
        $this->busSeats = $this->busSeats->find($singleSeat['id']);
        $this->busSeats->new_fare = $singleSeat['new_fare'];  
        $this->busSeats->update();
    }
    return $data;
}
public function updateBusSeatsExtra($data,$id)
{
    $layoutArray=$data['bus_seat_layout_data'];
    $get_ticket_price_id=$this->ticketPrice->where('bus_id',$data['bus_id'])->get();
    $bus =$this->busSeats->where('bus_id', $data['bus_id'])->where('duration','>',0)->update(['status'=>2]);
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
                                
                                $data['ticket_price_id']=$ticketpriceID->id;
                                $data['category']='0';                                   
                                $busseats=$this->getModel($busseats,$data,$upperBerthData);
                                $busseats->save(); 
                            }
                        }
                    }
                    
                    
                }
            }
        }
        if(isset($sLayoutData['lowerBerth']))
        {
                // Log::info($sLayoutData['lowerBerth']);
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
                               
                                $data['ticket_price_id']=$ticketpriceID->id;
                                $data['category']='0';                                   
                                $busseats=$this->getModel($busseats,$data,$lowerBerthData);
                              
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
public function update($data, $id)
{
   
    $layoutArray=$data['bus_seat_layout_data'];
    $bus_id=$data['bus_id'];

    //////////  get prev seatLayout_id (if doesnot match then update bus seats table all record for that bus status to 2) ///////

    $getBus_data=$this->bus->where("id",$bus_id)->get();

    if($getBus_data[0]->bus_seat_layout_id != $data['bus_seat_layout_id']){
        //Log::info('status 2');
        $this->busSeats->where('bus_id',$bus_id)->update(array("status"=>2));
    }


    $this->bus->where("id",$bus_id)->update(array("bus_seat_layout_id"=>$data['bus_seat_layout_id']));
    //Log::info($layoutArray);
       
        //NEED TO CREATE A NEW SET OF RECORD STATUS

     $get_ticket_price_id=$this->ticketPrice->where('bus_id',$bus_id)->get(); // ->where("status","1") (11:30AM, 4 Nov,2023)
    
    foreach($layoutArray as $sLayoutData)
    {


        if(isset($sLayoutData['upperBerth']))
        {

            if(count($sLayoutData['upperBerth'])>0)
            {
                foreach($sLayoutData['upperBerth'] as $upperBerthData)
                {
                    foreach($get_ticket_price_id as $ticketpriceID)
                    {
                        if((isset($upperBerthData['seatChecked']) && ($upperBerthData['seatChecked']==true || $upperBerthData['seatChecked']=="true")) || (!isset($upperBerthData['seatChecked']) && isset($upperBerthData['extraSeat'])  && $upperBerthData['extraSeat'] > 0))
                        {

                           // \DB::connection()->enableQueryLog();
                            
                            $find_existing = $this->busSeats
                                            ->where('bus_id',$bus_id)
                                            ->where('seats_id',$upperBerthData['seatId'])
                                            ->where('ticket_price_id',$ticketpriceID->id)
                                            ->where('status',1)
                                            ->where('operation_date','=',null)
                                            ->get();

                         // Log::info($bus_id."-".$upperBerthData['seatId']."-".$ticketpriceID->id);
                          //Log::info($find_existing);

                         
                            //$queries = \DB::getQueryLog();
                            //$last_query = end($queries);

                            //Log::info($queries->toSql());



                            if(count($find_existing)>0)
                            {
                               // Log::info("UPPER BERTH: Found Duplicate and Return ".$upperBerthData['seatId']);
                                continue;
                            }

                            $find_existing_new_fare = $this->busSeats
                            ->where('bus_id',$bus_id)
                            ->where('seats_id',$upperBerthData['seatId'])
                            ->where('ticket_price_id',$ticketpriceID->id)
                            ->where('status',2)
                            ->where('operation_date','=',null)
                            ->orderBy('id','desc')
                            ->limit(1)
                            ->first();

                            


                            //Log::info("UPPER BERTH: Add A new seat ".$upperBerthData['seatId']);
                            $busseats = new $this->busSeats;
                            $upd_data['bus_id']=$data['bus_id'];
                            $upd_data['ticket_price_id']=$ticketpriceID->id;
                            $upd_data['category']='0';
                            $upd_data['duration']=(isset($upperBerthData['extraSeat'])) ? $upperBerthData['extraSeat'] : 0;
                            $upd_data['status']=1;
                            $upd_data['new_fare']=($find_existing_new_fare) ? $find_existing_new_fare->new_fare : 0;
                            $upd_data['created_by']=(isset($data['created_by'])) ? $data['created_by'] : 'Admin';
                            
                            $busseats=$this->getModel($busseats,$upd_data,$upperBerthData);
                            $busseats->save();
                        }
                        if(isset($upperBerthData['seatChecked']) && $upperBerthData['seatChecked']==false)
                        {
                           // Log::info("UPPER BERTH: Made status 2 for seat ".$upperBerthData['seatId']);
                            $busseats = $this->busSeats
                                             ->where('seats_id',$upperBerthData['seatId'])
                                             ->where('bus_id',$bus_id)
                                             ->where('ticket_price_id',$ticketpriceID->id)
                                             ->update(['status'=>2]);
                        }

                    }
                }
            }
        }
        if(isset($sLayoutData['lowerBerth']))
        {
            if(count($sLayoutData['lowerBerth'])>0)
            {  
               // Log::info($sLayoutData['lowerBerth']);
                foreach($sLayoutData['lowerBerth'] as $lowerBerthData)
                {                     
                    foreach($get_ticket_price_id as $ticketpriceID)
                    {
                        if((isset($lowerBerthData['seatChecked']) && ($lowerBerthData['seatChecked']==true || $lowerBerthData['seatChecked']=="true")) || (!isset($lowerBerthData['seatChecked']) && isset($lowerBerthData['extraSeat']) && $lowerBerthData['extraSeat'] > 0))
                        {
                            $find_existing = $this->busSeats
                                            ->where('bus_id',$bus_id)
                                            ->where('seats_id',$lowerBerthData['seatId'])
                                            ->where('ticket_price_id',$ticketpriceID->id)
                                            ->where('status',1)
                                            ->where('operation_date','=',null)
                                            ->get();

                         // Log::info($bus_id."-".$lowerBerthData['seatId']."-".$ticketpriceID->id);
                         // Log::info($find_existing);



                            if(count($find_existing)>0)
                            {
                               // Log::info("LOWER BERTH: Found Duplicate and Return ".$lowerBerthData['seatId']);
                                continue;
                            }

                            $find_existing_new_fare = $this->busSeats
                            ->where('bus_id',$bus_id)
                            ->where('seats_id',$lowerBerthData['seatId'])
                            ->where('ticket_price_id',$ticketpriceID->id)
                            ->where('status',2)
                            ->where('operation_date','=',null)
                            ->orderBy('id','desc')
                            ->limit(1)
                            ->first();

                            //Log::info("LOWER BERTH: Add A new seat ".$lowerBerthData['seatId']);
                            $busseats = new $this->busSeats;
                            $upd_data['bus_id']=$data['bus_id'];
                            $upd_data['ticket_price_id']=$ticketpriceID->id;
                            $upd_data['category']='0';
                            $upd_data['duration']=(isset($lowerBerthData['extraSeat'])) ? $lowerBerthData['extraSeat'] : 0;
                            $upd_data['status']=1;
                            $upd_data['new_fare']=($find_existing_new_fare) ? $find_existing_new_fare->new_fare : 0;
                            $upd_data['created_by']=(isset($data['created_by'])) ? $data['created_by'] : 'Admin';

                            log::info($upd_data);
                            $busseats=$this->getModel($busseats,$upd_data,$lowerBerthData);
                            $busseats->save();
                        }
                        if(isset($lowerBerthData['seatChecked']) && $lowerBerthData['seatChecked']==false)
                        {
                            //Log::info("LOWER BERTH: Made status 2 for seat ".$lowerBerthData['seatId']);
                            $busseats = $this->busSeats
                                             ->where('seats_id',$lowerBerthData['seatId'])
                                             ->where('bus_id',$bus_id)
                                             ->where('ticket_price_id',$ticketpriceID->id)
                                             ->update(['status'=>2]);
                        }

                    }

                   
                   
                }
            }
        }
    }
    return $data;
}


public function delete($id)
{
    
    $busseats = $this->busSeats->find($id);
    $busseats->delete();

    return $busseats;
}

public function cronjob_cleanbusseat(){
   $date = date('Y-m-d', strtotime('-10 day'));

    $seat_block =  DB::delete("DELETE FROM `bus_seats` WHERE  type=2 AND id NOT IN (SELECT id FROM `bus_seats` WHERE type=2 AND id IN (select bus_seats_id from booking_detail) ORDER BY `id` DESC) AND operation_date < '$date' ");

    $seat_open =  DB::delete("DELETE FROM `bus_seats` WHERE  type=1 AND id NOT IN (SELECT id FROM `bus_seats` WHERE type=1 AND id IN (select bus_seats_id from booking_detail) ORDER BY `id` DESC) AND operation_date < '$date'   ORDER BY `id` ASC");

    $deleted_seat=  DB::delete("DELETE FROM `bus_seats` WHERE `status` = 2 AND id NOT IN (SELECT id FROM `bus_seats` WHERE status=2 AND id IN (select bus_seats_id from booking_detail) ORDER BY `id` DESC);");

   

    log::info('seatBlock - '.$seat_block);
    log::info('seatOpen - '.$seat_open);
    log::info('deletedSeats - '.$deleted_seat);
}

}