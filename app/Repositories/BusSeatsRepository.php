<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\BusSeats;
use App\Models\TicketPrice;
use Illuminate\Support\Facades\Log;
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
        return $this->busSeats->get();
    }
    public function getAllFare($busId)
    {
       // $result['busSeats']=$this->busSeats->where('bus_id',$busId)->orderBy('source_id','ASC')->get();//busSeats
       // $result['stoppageInfo']=$this->busStoppage->where('bus_id', $busId)->get();
       return $this->ticketPrice->with('getBusSeats')->where('bus_id',$busId)->get();
    }
    public function getByBusId($busId)
    {
        return $this->busSeats
        
        ->where('bus_id', $busId)->get();
    }
    public function getById($id)
    {
        return $this->busSeats ->where('id', $id)->get();
    }
    public function getModel(BusSeats $busseats,$data,$berthData)
    {
        Log::info($data);
        $busseats->bus_id = $data['bus_id'];
       // $busseats->berth_type=$berthData['berthType'];
        $busseats->category = $data['category'];
        $busseats->seats_id = $berthData['seatId'];
        //Find Value for Seat ID
        $busseats->ticket_price_id = $data['ticket_price_id'];
        $busseats->duration = $data['duration'];
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
        foreach($layoutArray as $sLayoutData)
        {
            if(isset($sLayoutData['upperBerth']))
            {
                if(count($sLayoutData['upperBerth'])>0)
                {
                    foreach($sLayoutData['upperBerth'] as $upperBerthData)
                    {
                        if(!isset($upperBerthData['seatChecked']))
                        {
                            continue;
                        }
                        if($upperBerthData['seatChecked']!=true && $upperBerthData['seatId']!=NULL)
                        {
                            $busseats = $this->busSeats->find($upperBerthData['seatId']);
                            $busseats->delete();
                        }
                        if($upperBerthData['seatChecked']==true)
                        {
                            if($upperBerthData['seatId']=="")
                            {
                                $busseats = new $this->busSeats;
                                $busseats=$this->getModel($busseats,$data,$upperBerthData);
                                $busseats->save();
                            }
                            else if($upperBerthData['seatId']!="" && intval($upperBerthData['extraSeat'])>0)
                            {
                                $busseats = $this->busSeats->find($upperBerthData['seatId']);
                                $busseats=$this->getModel($busseats,$data,$upperBerthData);
                                $busseats->update();
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
                        if(!isset($lowerBerthData['seatChecked']))
                        {
                            continue;
                        }
                        if($lowerBerthData['seatChecked']!=true && $lowerBerthData['seatId']!=NULL)
                        {
                            $busseats = $this->busSeats->find($lowerBerthData['seatId']);
                            $busseats->delete();
                        }
                        if($lowerBerthData['seatChecked']==true)
                        {
                            if($lowerBerthData['seatId']=="")
                            {
                                $busseats = new $this->busSeats;
                                $busseats=$this->getModel($busseats,$data,$lowerBerthData);
                                $busseats->save();
                            }
                            else if($lowerBerthData['seatId']!="" && intval($lowerBerthData['extraSeat'])>0)
                            {
                                $busseats = $this->busSeats->find($lowerBerthData['seatId']);
                                $busseats=$this->getModel($busseats,$data,$lowerBerthData);
                                $busseats->update();
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
        foreach($layoutArray as $sLayoutData)
        {
            if(isset($sLayoutData['upperBerth']))
            {
                if(count($sLayoutData['upperBerth'])>0)
                {
                    foreach($sLayoutData['upperBerth'] as $upperBerthData)
                    {
                        if($upperBerthData['seatChecked']!=true && $upperBerthData['seatId']!=NULL)
                        {
                            $busseats = $this->busSeats->find($upperBerthData['seatId']);
                            $busseats->delete();
                        }
                        if($upperBerthData['seatChecked']==true)
                        {
                            if($upperBerthData['seatId']=="")
                            {
                                $busseats = new $this->busSeats;
                            }
                            else
                            {
                                $busseats = $this->busSeats->find($upperBerthData['seatId']);
                                
                            }
                            $busseats=$this->getModel($busseats,$data,$upperBerthData);
                            if($upperBerthData['seatId']=="")
                            {
                                $busseats->save();
                            }
                            else
                            {
                                $busseats->update();
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
                        if($lowerBerthData['seatChecked']!=true && $lowerBerthData['seatId']!=NULL)
                        {
                            $busseats = $this->busSeats->find($lowerBerthData['seatId']);
                            $busseats->delete();
                        }
                        if($lowerBerthData['seatChecked']==true)
                        {
                            if($lowerBerthData['seatId']=="")
                            {
                                $busseats = new $this->busSeats;
                            }
                            else
                            {
                                $busseats = $this->busSeats->find($lowerBerthData['seatId']);
                            }
                            $busseats=$this->getModel($busseats,$data,$lowerBerthData);
                            if($lowerBerthData['seatId']=="")
                            {                                
                                $busseats->save();
                            }
                            else
                            {
                                $busseats->update();
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