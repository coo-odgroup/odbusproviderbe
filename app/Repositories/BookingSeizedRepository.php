<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\BookingSeized;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class BookingSeizedRepository
{    
    protected $bookingSeized;
    protected $bus;

    
    public function __construct(BookingSeized $bookingSeized,Bus $bus )
    {
        $this->bus = $bus;

        $this->bookingSeized = $bookingSeized;       
    }    

    public function getAll()
    {
        return $this->bus->with('bookingseized.location','busOperator')->get();

    }

    public function save($seizedData)
    {
        //Log::info($seizedData);
        $seized = new $this->bookingSeized; 
        $seized->bus_id = $seizedData['bus_id'];   
        $seized->location_id = $seizedData['location_id'];  
        $seized->seize_booking_minute = $seizedData['seize_booking_minute'];   
        $seized->created_by = $seizedData['created_by'];   

        $seized->save();
        return $seized->fresh();
      
    }

    public function update($seizedData)
    {   


        foreach($seizedData['busSeized'] as $record)
        {
            $seized = $this->bookingSeized->find($record['id']); 
            $seized->seize_booking_minute = $record['time'];        
            $seized->update();
        }
        return $seizedData;
    }


    public function bookingseizedData($request)
    {   
        // Log::info($request);exit;

         $paginate = $request['rows_number'] ;
         $name = $request['name'] ;
       

        $data= $this->bus->with('bookingseized.location','busOperator');
                         // ->whereNotIn('status', [2]);


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
            $data = $data->where('name', 'like', '%' .$name . '%')
                         ->orWhere('bus_number', 'like', '%' .$name . '%')
                         ->orWhereHas('busOperator', function ($query) use ($name){
                            $query->where('operator_name', 'like', '%' .$name . '%');
                        });                        
        }     

        $data=$data->paginate($paginate);

        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
           return $response;   
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

   

}