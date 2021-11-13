<?php

namespace App\Repositories;

// use App\Models\Bus;
use App\Models\SeatOpen;
use App\Models\SeatOpenSeats;
use App\Models\ExtraSeatOpen;
use App\Models\Bus;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class ExtraSeatOpenReportRepository
{
    
    protected $seatOpen;
    protected $extraseatOpen;
    protected $bus;

    
    public function __construct(SeatOpen $seatOpen , SeatOpenSeats $seatsOpenSeats,ExtraSeatOpen $extraseatOpen,Bus $bus)
    {
        $this->seatOpen = $seatOpen;
        $this->bus = $bus;
        $this->seatOpenSeats = $seatsOpenSeats;
        $this->extraseatOpen = $extraseatOpen;
    }   


    public function getAll($request)
    {
        $paginate = $request->rows_number;
        $bus_operator_id = $request->bus_operator_id;
        $bus_id = $request->bus_id;

        $data= $this->bus->with('busSeats.seats','busOperator')
                    ->whereHas('busSeats', function ($query) {$query->where('duration', '>','0 ');})
                    ->whereHas('busSeats', function ($query) {$query->where('status', '1');})
                    ->orderBy('id','DESC');

        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) {
            $paginate = 10 ;
        }
        if($bus_operator_id!=null)
        {
           $data=$data->whereHas('busOperator', function ($query) use ($bus_operator_id) {$query->where('id', $bus_operator_id );});
        }
        if($bus_id!=null)
        {
            $data=$data->where('id', $bus_id );
        }

         $data=$data->paginate($paginate);


          $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );    
           return $response; 
     

    }
}