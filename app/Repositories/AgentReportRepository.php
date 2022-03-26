<?php
namespace App\Repositories;
use App\Models\Bus;
use App\Models\Booking;
use App\Models\Location;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class  AgentReportRepository
{
    protected $booking;
    protected $location;
    protected $bus;

    public function __construct(Booking $booking ,Location $location ,Bus $bus)
    {       
        $this->booking = $booking;       
        $this->location = $location;       
        $this->bus = $bus;       
    }    
    
    public function getData($request)
    {
    
        $paginate = $request->rows_number;    
        $pnr = $request->pnr;
        $date_type = $request->date_type;
        $start_date  =  $request->rangeFromDate;
        $end_date  =  $request->rangeToDate;
        $user_id  =  $request->user_id;

        
        $data= $this->booking->with('BookingDetail.BusSeats.seats',
                                    'BookingDetail.BusSeats.ticketPrice',
                                    'Bus','User','Users')
                             ->with('bus.busstoppage')
                             ->where('status',1)
                             ->where('user_id','!=',0)
                             ->orderBy('id','DESC');
        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) {
            $paginate = 10 ;
        }

        if(!empty($user_id))
        {
           $data=$data->where('user_id', $user_id );
        }
        

        if(!empty($pnr))
        {
           $data=$data->whereHas('User', function ($query) use ($pnr) {$query->where('name', $pnr );});
        }

       
        if($date_type == 'booking' && $start_date == null && $end_date == null)
        {
            $data =$data->orderBy('created_at','DESC');
        }
        else if($date_type == 'booking' && $start_date != null && $end_date != null)
        {         
            if($start_date == $end_date){
                $data =$data->where('created_at','like','%'.$start_date.'%')
                        ->orderBy('created_at','DESC');
                       
            }else{
                $data =$data->whereBetween('created_at', [$start_date, $end_date])
                        ->orderBy('created_at','DESC');
            }
            
        }
        else if($date_type == 'journey' && $start_date == null && $end_date == null)
        {
            $data =$data->orderBy('journey_dt','DESC');
        }
         else if($date_type == 'journey' && $start_date != null && $end_date != null)
        {
             if($start_date == $end_date){
                $data =$data->where('journey_dt', 'like','%'.$start_date.'%')
                        ->orderBy('journey_dt','DESC');
            }else{
                 $data =$data-> whereBetween('journey_dt', [$start_date, $end_date])
                        ->orderBy('journey_dt','DESC');
            }
        }
        
        $data=$data->paginate($paginate); 
        
   
        if($data){
            foreach($data as $key=>$v){

               $v['from_location']=$this->location->where('id', $v->source_id)->get();
               $v['to_location']=$this->location->where('id', $v->destination_id)->get();

               $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get();
               // $v['source']=[];
               // $v['destination']=[];

               $stoppages['source']=[];
               $stoppages['destination']=[];
               
               foreach ($stoppage[0]['ticketPrice'] as $k => $a) 
                {                          
                    $stoppages['source'][$k]=$this->location->where('id', $a->source_id)->get();
                    $stoppages['destination'][$k]=$this->location->where('id', $a->destination_id)->get(); 
                }
                $v['source']= $stoppages['source'];
                $v['destination']= $stoppages['destination'];
            }
        }

      
        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
           return $response;      

    }

    public function agentcancelreport($request)
    {
             
        $paginate = $request->rows_number;    
        $user_id = $request->user_id;
        $pnr = $request->pnr;
        $date_type = $request->date_type;
        $start_date  =  $request->rangeFromDate;
        $end_date  =  $request->rangeToDate;

       

        $data= $this->booking->with('BookingDetail.BusSeats.seats',
                                    'BookingDetail.BusSeats.ticketPrice',
                                    'Bus','User','Users')
                             ->with('bus.busstoppage')
                             ->where('status',2)
                             ->where('user_id','!=',0)
                             ->orderBy('id','DESC');
        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) {
            $paginate = 10 ;
        }

        if(!empty($user_id))
        {
           $data=$data->where('user_id', $user_id );
        }
        
        if(!empty($pnr))
        {
           $data=$data->whereHas('User', function ($query) use ($pnr) {$query->where('name', $pnr );});
        }

         if($date_type == 'booking' && $start_date == null && $end_date == null)
        {
            $data =$data->orderBy('created_at','DESC');
        }
        else if($date_type == 'booking' && $start_date != null && $end_date != null)
        {         
            if($start_date == $end_date){
                $data =$data->where('created_at','like','%'.$start_date.'%')
                        ->orderBy('created_at','DESC');
                       
            }else{
                $data =$data->whereBetween('created_at', [$start_date, $end_date])
                        ->orderBy('created_at','DESC');
            }
            
        }
        else if($date_type == 'journey' && $start_date == null && $end_date == null)
        {
            $data =$data->orderBy('journey_dt','DESC');
        }
         else if($date_type == 'journey' && $start_date != null && $end_date != null)
        {
             if($start_date == $end_date){
                $data =$data->where('journey_dt', 'like','%'.$start_date.'%')
                        ->orderBy('journey_dt','DESC');
            }else{
                 $data =$data-> whereBetween('journey_dt', [$start_date, $end_date])
                        ->orderBy('journey_dt','DESC');
            }
        }
        
        $data=$data->paginate($paginate); 
        
   
        if($data){
            foreach($data as $key=>$v){

               $v['from_location']=$this->location->where('id', $v->source_id)->get();
               $v['to_location']=$this->location->where('id', $v->destination_id)->get();

               $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get();
               // $v['source']=[];
               // $v['destination']=[];
               foreach ($stoppage[0]['ticketPrice'] as $k => $a) 
                {                          
                    $stoppages['source'][$k]=$this->location->where('id', $a->source_id)->get();
                    $stoppages['destination'][$k]=$this->location->where('id', $a->destination_id)->get(); 
                }
                $v['source']= $stoppages['source'];
                $v['destination']= $stoppages['destination'];
            }
        }

      
        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
           return $response;      

    }

    public function agentCommissionreport($request)
    {
       
        $paginate = $request->rows_number;    
    
        $pnr = $request->pnr;
        $date_type = $request->date_type;
        $start_date  =  $request->rangeFromDate;
        $end_date  =  $request->rangeToDate;

        $data= $this->booking->with('BookingDetail.BusSeats.seats',
                                    'BookingDetail.BusSeats.ticketPrice',
                                    'Bus','User')
                             ->with('bus.busstoppage')
                             ->where('status',2)
                             ->where('user_id','!=',0)
                             ->orderBy('id','DESC');
        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) {
            $paginate = 10 ;
        }

        if(!empty($pnr))
        {
           $data=$data->whereHas('User', function ($query) use ($pnr) {$query->where('name', $pnr );});
        }

          if($date_type == 'booking' && $start_date == null && $end_date == null)
        {
            $data =$data->orderBy('created_at','DESC');
        }
        else if($date_type == 'booking' && $start_date != null && $end_date != null)
        {         
            if($start_date == $end_date){
                $data =$data->where('created_at','like','%'.$start_date.'%')
                        ->orderBy('created_at','DESC');
                       
            }else{
                $data =$data->whereBetween('created_at', [$start_date, $end_date])
                        ->orderBy('created_at','DESC');
            }
            
        }
        else if($date_type == 'journey' && $start_date == null && $end_date == null)
        {
            $data =$data->orderBy('journey_dt','DESC');
        }
         else if($date_type == 'journey' && $start_date != null && $end_date != null)
        {
             if($start_date == $end_date){
                $data =$data->where('journey_dt', 'like','%'.$start_date.'%')
                        ->orderBy('journey_dt','DESC');
            }else{
                 $data =$data-> whereBetween('journey_dt', [$start_date, $end_date])
                        ->orderBy('journey_dt','DESC');
            }
        }
        

        $data=$data->paginate($paginate); 
        
   
        if($data){
            foreach($data as $key=>$v){

               $v['from_location']=$this->location->where('id', $v->source_id)->get();
               $v['to_location']=$this->location->where('id', $v->destination_id)->get();

               $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get();
               // $v['source']=[];
               // $v['destination']=[];
               foreach ($stoppage[0]['ticketPrice'] as $k => $a) 
                {                          
                    $stoppages['source'][$k]=$this->location->where('id', $a->source_id)->get();
                    $stoppages['destination'][$k]=$this->location->where('id', $a->destination_id)->get(); 
                }
                $v['source']= $stoppages['source'];
                $v['destination']= $stoppages['destination'];
            }
        }

      
        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
           return $response;      

    }
    
}