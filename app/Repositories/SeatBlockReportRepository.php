<?php
namespace App\Repositories;
use App\Models\SeatBlock;
use App\Models\SeatBlockSeats;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Models\Location;
/*Priyadarshi to Review*/
class SeatBlockReportRepository
{
    protected $location;
    protected $seatBlock;
    public function __construct(SeatBlock $seatBlock , SeatBlockSeats $seatsBlockSeats ,Location $location)
    {
        $this->seatBlock = $seatBlock;
        $this->seatBlockSeats = $seatsBlockSeats;
        $this->location = $location;        
    }    
     public function getAll()
    {
        
        $data = $this->seatBlock ->with('seatBlockSeats.seats')->with('bus','bus.busOperator','bus.ticketPrice')->get();
        $data_arr = array();
        foreach ($data as $key => $v)
        {
            $data_arr[]=$v->toArray(); 
            $data_arr[$key]['date_applied']=date('j M Y',strtotime($v->date_applied));
            foreach ($data_arr as $e) 
            {
                $counter=0;
                foreach ($e['bus']['ticket_price'] as $sub=>$a) 
                {
                    $counter++;
                    if($counter==1)
                    {
                        $data_arr[$key]['base_from_location']=$this->location->where('id', $a['source_id'])->get();
                        $data_arr[$key]['base_to_location']=$this->location->where('id', $a['destination_id'])->get();
                    }
                    $data_arr[$key]['from_location'][$sub]=$this->location->where('id', $a['source_id'])->get();
                    $data_arr[$key]['to_location'][$sub]=$this->location->where('id', $a['destination_id'])->get();
                }                           
            }           
        }
        return $data_arr;
    }
}