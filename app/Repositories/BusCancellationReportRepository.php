<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\BusCancelled;
use App\Models\BusCancelledDate;
use App\Models\BusOperator;
use App\Models\BusStoppage;
use App\Models\Location;


use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class BusCancellationReportRepository
{
    
    protected $seatOpen;
    protected $extraseatOpen;

    
    public function __construct(BusCancelled $busCancelled,Bus $bus,BusOperator $busOperator,BusStoppage $busStoppage,Location $location,BusCancelledDate $busCancelledDate)
    {
        $this->busCancelled = $busCancelled;
        $this->bus = $bus;
        $this->busOperator = $busOperator;
        $this->busStoppage = $busStoppage;
        $this->location = $location;
        $this->busCancelledDate = $busCancelledDate;
    }   

   
    public function getAll()
    {
         $data= $this->busCancelled->with('bus.BusSitting','bus.BusType','bus.busOperator','bus.ticketPrice','busCancelledDate')->whereNotIn('status', [2])->get();

        $data_arr = array();
        foreach($data as $key=>$v)
        {
            $data_arr[]=$v->toArray();
            

            $data_arr[$key]['from_location']=$this->location->where('id', $v->bus->ticketPrice[0]['source_id'])->get();
            $data_arr[$key]['to_location']=$this->location->where('id', $v->bus->ticketPrice[0]['destination_id'])->get();

        } 
       
        return $data_arr;     
    


   

    }
}