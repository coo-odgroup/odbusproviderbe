<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\BusSeats;
use App\Models\TicketPrice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
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
    $data =  $this->busSeats
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
    return $this->busSeats ->where('id', $id)->get();
}
public function getModel(BusSeats $busseats,$data,$berthData)
{
    
    $busseats->bus_id = $data['bus_id'];
    $busseats->category = $data['category'];
    $busseats->seats_id = $berthData['seatId'];
    $busseats->ticket_price_id = $data['ticket_price_id'];
    $busseats->duration = $data['duration'];
    $busseats->status = '1';
    $busseats->created_by = "Admin";
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

    $this->bus->where("id",$bus_id)->update(array("bus_seat_layout_id"=>$data['bus_seat_layout_id']));
    //Log::info($layoutArray);
       
        //NEED TO CREATE A NEW SET OF RECORD STATUS

     $get_ticket_price_id=$this->ticketPrice->where('bus_id',$bus_id)->where("status","1")->get();
    
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
                            //Log::info("UPPER BERTH: Add A new seat ".$upperBerthData['seatId']);
                            $busseats = new $this->busSeats;
                            $data['ticket_price_id']=$ticketpriceID->id;
                            $data['category']='0';
                            $data['duration']=$upperBerthData['extraSeat'];
                            $data['status']=1;
                            $busseats=$this->getModel($busseats,$data,$upperBerthData);
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
                            //Log::info("LOWER BERTH: Add A new seat ".$lowerBerthData['seatId']);
                            $busseats = new $this->busSeats;
                            $data['ticket_price_id']=$ticketpriceID->id;
                            $data['category']='0';
                            $data['duration']=$lowerBerthData['extraSeat'];
                            $data['status']=1;
                            $busseats=$this->getModel($busseats,$data,$lowerBerthData);
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

}