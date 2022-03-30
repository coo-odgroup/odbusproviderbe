<?php

namespace App\Repositories;

// use App\Models\Bus;
use App\Models\SeatOpen;
use App\Models\SeatOpenSeats;
use App\Models\ExtraSeatOpen;
use App\Models\Bus;
use App\Models\Location;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class ExtraSeatOpenReportRepository
{
    
    protected $seatOpen;
    protected $extraseatOpen;
    protected $bus;
    protected $Location;

    
    public function __construct(SeatOpen $seatOpen , SeatOpenSeats $seatsOpenSeats,ExtraSeatOpen $extraseatOpen,Bus $bus,Location $Location)
    {
        $this->seatOpen = $seatOpen;
        $this->bus = $bus;
        $this->seatOpenSeats = $seatsOpenSeats;
        $this->extraseatOpen = $extraseatOpen;
        $this->Location = $Location;
    }   


    public function getAll($request)
    {
        $paginate = $request->rows_number;
        $bus_operator_id = $request->bus_operator_id;
        $bus_id = $request->bus_id;
        // ->with(['busSeats' => function ($a){
        //     $a->where('status',1);
        //     }])->with('busSeats.seats','busOperator')

        // $data= $this->bus->with(['busSeats' => function ($a){
        //                     $a->where('status',1)->where('duration', '>','0 ')->with('seats','ticketPrice'); }])->with('busOperator')
        //                  ->whereHas('busSeats', function ($query) {$query->where('duration', '>','0 ');})
        //                  ->whereHas('busSeats', function ($query) {$query->where('status', '1');})
        //                  ->orderBy('id','DESC');
         $data= $this->bus->with(['ticketPrice.getBusSeats' => function ($a){
                            $a->where('status',1)->where('duration', '>','0 ')->with('seats'); }])->with('busOperator')
                         ->whereHas('busSeats', function ($query) {$query->where('duration', '>','0 ');})
                         ->whereHas('busSeats', function ($query) {$query->where('status', '1');})
                         ->orderBy('id','DESC');
        if($request['USER_BUS_OPERATOR_ID']!="")
        {
            $data=$data->where('bus_operator_id',$request['USER_BUS_OPERATOR_ID']);
        }                 
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

         if($data){
            foreach($data as $key=>$v){
              // log::info($v);exit;
                $stoppages['source']=[];
                $stoppages['destination']=[];

               foreach ($v->ticketPrice as $k => $a) 
                {

                    $stoppages['source'][$k]=$this->Location->where('id', $a->source_id)->get();
                    $stoppages['destination'][$k]=$this->Location->where('id', $a->destination_id)->get(); 
                }

                $v['source']= $stoppages['source'];
                $v['destination']= $stoppages['destination'];
            }
        }
         Log::info($data);  
          $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );    
           return $response; 
     

    }
}