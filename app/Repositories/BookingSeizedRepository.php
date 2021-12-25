<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\BookingSeized;
use App\Models\Location;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class BookingSeizedRepository
{    
    protected $bookingSeized;
    protected $bus;
    protected $location;

    
    public function __construct(BookingSeized $bookingSeized,Bus $bus ,Location $location)
    {
        $this->bus = $bus;
        $this->bookingSeized = $bookingSeized;  
        $this->location = $location;       

    }    

    public function getAll()
    {
        return $this->bus->with('ticketPrice','busOperator')->get();

    }

    public function save($seizedData)
    {
        foreach($seizedData['busSeized'] as $record)
        {
            $seized = new $this->bookingSeized; 
            $seized->bus_id = $seizedData['bus_id'];   
            $seized->ticket_price_id  = $record['id'];  
            $seized->seize_booking_minute = $record['time'];   
            $seized->seized_date = $seizedData['date'];   
            $seized->created_by = $seizedData['created_by'];  
            $seized->reason = $seizedData['reason'];  
            $seized->other_reason = $seizedData['otherReson'];  
            $seized->save(); 
        }
        return $seizedData;    
    }

    public function update($seizedData)
    {   
        // Log::info($seizedData);exit;

        foreach($seizedData['busSeized'] as $record)
        {
            $seized = $this->bookingSeized->find($record['id']); 
            $seized->seize_booking_minute = $record['time']; 
            $seized->created_by = $seizedData['created_by'];        
            $seized->update();
        }
        return $seizedData;
    }


    public function bookingseizedData($request)
    {   
        // Log::info($request);exit;

         $paginate = $request['rows_number'] ;
         $name = $request['name'] ;
       

         $data= $this->bookingSeized->with('bus.busOperator')->with('ticketPrice')->where('status',1);


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
            $data = $data->WhereHas('bus', function ($query) use ($name){
                            $query->where('name', 'like', '%' .$name . '%');
                        })->orWhereHas('bus', function ($query) use ($name){
                            $query->Where('bus_number', 'like', '%' .$name . '%');
                        })
         
                         ->orWhereHas('bus.busOperator', function ($query) use ($name){
                            $query->where('operator_name', 'like', '%' .$name . '%');
                        });                        
        }     

        $data=$data->paginate($paginate);

        if($data){
            foreach($data as $a)
            {       
                  $a['from_location']=$this->location->where('id', $a->ticketPrice->source_id)->get();
                $a['to_location']=$this->location->where('id',$a->ticketPrice->destination_id)->get(); 
                                   
            }
        }
  
        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
           return $response;   
    }  



    public function bookingseizedById($id)
    {         

         $data= $this->bus->with(['ticketPrice' => function ($a){
            $a->where('status',1);
            }])->where('id',$id)->where('status',1)->get(); 
        if($data){
            foreach($data as $v)
            { 
               foreach($v->ticketPrice as $k => $a)
               {      
                $a['from_location']=$this->location->where('id', $a->source_id)->get();
                $a['to_location']=$this->location->where('id',$a->destination_id)->get(); 
                }
            }
        }
           return $data;   
    }

    public function changeStatus($id)
    {
       
        $post = $this->bookingSeized->find($id);
        if($post->status==0){
            $post->status = 1;
        }elseif($post->status==1){
            $post->status = 0;
        }
        $post->update();
        return $post;
    }

    public function deletebookingseized($id)
    {       
        // log::info($id);
        // exit;
        $post = $this->bookingSeized->find($id);
        $post->status = 2;
        $post->update();
        return $post;
    }

   

}