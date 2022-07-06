<?php

namespace App\Repositories;
use App\Models\Bus;
use App\Models\FestivalFare;
use App\Models\BusFestivalFare;
use Illuminate\Support\Facades\Log;
use App\Models\Location;


class FestivalFareRepository
{
    protected $festivalFare;
    protected $busFestivalFare;
    protected $bus;
    protected $Location;
    
    public function __construct(Location $Location,BusFestivalFare $busFestivalFare,Bus $bus,FestivalFare $festivalFare)
    {
        $this->busFestivalFare = $busFestivalFare;
        $this->bus = $bus;
        $this->festivalFare = $festivalFare;
        $this->Location = $Location;
    }
    /**
     * Get Bus Secial Fare List
     *
     * @param $id
     * @return mixed
     */
    public function getAll()
    {
        return $this->festivalFare->whereNotIn('status', [2])->get();
    }

    public function festivalFareData($request)
    {
        
        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;
        $fromDate = $request['fromDate'] ;
        $toDate = $request['toDate'] ;
        $bus_operator_id = $request['bus_operator_id'] ;
       

        $data= $this->festivalFare->with('bus','bus.busOperator')
                    ->whereNotIn('status', [2]) ->orderBy('id','DESC');


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
            $data = $data->where('date', 'like', '%' .$name . '%')
                         ->orWhereHas('bus', function ($query) use ($name){
                            $query->where('name', 'like', '%' .$name . '%');
                                 })  
                         ->orWhereHas('bus.busOperator', function ($query) use ($name){
                             $query->where('operator_name', 'like', '%' .$name . '%');});                       
        }    
        if($bus_operator_id!= null)
        {
            $data=$data->whereHas('bus.busOperator', function ($query) use ($bus_operator_id){
               $query->where('bus_operator_id', $bus_operator_id);               
           });
        }


        if($toDate!= null && $fromDate!=null)
        {
              if($fromDate==$toDate){
                      $data = $data->where('date',$toDate);
              }else{
                  $data = $data->whereBetween('date', [$fromDate, $toDate]);
              } 
        }  
 

        $data=$data->paginate($paginate);
        
         if($data){
            foreach($data as $key=>$v){
                 
                foreach ($v->bus as $ky => $val) {
                    $stoppage = $this->bus->with('ticketPrice')->where('id', $val->id)->where('status', 1)->get();

                    if(count($stoppage)>0){
                        foreach ($stoppage[0]['ticketPrice'] as $k => $a) 
                        {
                             
                            $stoppages['source'][$k]=$this->Location->where('id', $a->source_id)->get();
                            $stoppages['destination'][$k]=$this->Location->where('id', $a->destination_id)->get(); 
                        }
                        $val['source']= $stoppages['source'];
                        $val['destination']= $stoppages['destination'];
                    }
                }               
            }
        }

        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
           return $response;  

    }
    
    public function getDatatable($request)
    {

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        if(!is_numeric($rowperpage))
        {
            $rowperpage=10000;
        }
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
      
        $totalRecords=$this->festivalFare->whereHas('bus')->whereNotIn('status', [2])->count();


        $totalRecordswithFilter=$this->festivalFare->with('bus')  
        ->whereHas('bus', function ($query) use ($searchValue){
               $query->where('name', 'like', '%' .$searchValue . '%');               
           })->whereNotIn('status', [2])->count();

           $records =  $this->festivalFare->with('bus')
           ->orderBy($columnName,$columnSortOrder)   
           ->whereHas('bus', function ($query) use ($searchValue){
                  $query->where('name', 'like', '%' .$searchValue . '%');               
              })
              ->skip($start)
              ->take($rowperpage)
              ->whereNotIn('status', [2])
              ->get();
              $data_arr = array();        
              foreach($records as $key=>$record)
        {       
           $buses= $record->bus; 
           $busNames="";     
          foreach($buses as $bus)
           {           
            $busNames .=  ($busNames=="")?$bus->name:",".$bus->name; 
           }
           $data_arr[]=$record->toArray(); 
           $data_arr[$key]['name']=$busNames;
           $data_arr[$key]['created_at']=date('j M Y h:i a',strtotime($record->created_at));
           $data_arr[$key]['updated_at']=date('j M Y h:i a',strtotime($record->updated_at));
        } 
              $response = array(
                  "draw" => intval($draw),
                  "iTotalRecords" => $totalRecords,
                  "iTotalDisplayRecords" => $totalRecordswithFilter,
                  "aaData" => $data_arr
              ); 
              return ($response);
     
        }   
        
    /**
     * Get Bus Owner Fare by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->festivalFare->with('bus')->where('id', $id)->get();
    }

    public function getModel($data, FestivalFare $festivalFare)
    {
        $festivalFare->bus_operator_id = $data['bus_operator_id'];
        $festivalFare->source_id = $data['source_id'];
        $festivalFare->destination_id = $data['destination_id'];
        $festivalFare->date = $data['date'];
        $festivalFare->seater_price = $data['seater_price'];
        $festivalFare->sleeper_price = $data['sleeper_price'];
        $festivalFare->reason = $data['reason'];
        $festivalFare->created_by = $data['created_by'];
        return $festivalFare;
    }

    /**
     * Save Bus Owner Fare
     *
     * @param $data
     * @return bus Owner fare
     */
    public function save($data)
    {
        
        foreach ($data['date'] as $d) {
           $date=$d['day'];
            if($d['day']<10)
            {
                $date='0'.$d['day'];
            }
            $month = $d['month'];
            if($d['month']<10)
            {
               $month='0'.$d['month']; 
            }

            $dt = $d['year'].'-'.$month.'-'.$date;


            $festivalFare = new $this->festivalFare;
            $festivalFare->bus_operator_id = $data['bus_operator_id'];
            $festivalFare->source_id = $data['source_id'];
            $festivalFare->destination_id = $data['destination_id'];
            $festivalFare->date = $dt;
            $festivalFare->seater_price = $data['seater_price'];
            $festivalFare->sleeper_price = $data['sleeper_price'];
            $festivalFare->reason = $data['reason'];
            $festivalFare->created_by = $data['created_by'];
            $festivalFare->status = 1;
            $festivalFare->save();

            $bus_id = $this->bus::find($data['bus_id']);
            $festivalFare->bus()->attach($data['bus_id']);
            }
        
        return 'Data Added Successfully';
    }
    /**
     * Update Bus Owner fare
     *
     * @param $data
     * @return busfestivalFare
     */
    public function update($data, $id)
    {
        $festivalFare = $this->festivalFare->find($id);
        $festivalFare=$this->getModel($data,$festivalFare);
        $festivalFare->update();
        $bus_id = $this->bus::find($data['bus_id']);
        $festivalFare->bus()->sync($data['bus_id']);
        return $festivalFare;
    }
    /**
     * Delete Bus Owner Fare
     *
     * @param $data
     * @return busfestivalFare
    */
    public function delete($id)
    {
        $busfestivalFare = $this->festivalFare->find($id);
        $busfestivalFare->status = 2;
        $busfestivalFare->update();
        $busfestivalFare->bus()->detach();
        return $busfestivalFare;
    }
    public function changeStatus($id)
    {
        $post = $this->festivalFare->find($id);
        if($post->status==0){
            $post->status = 1;
        }elseif($post->status==1){
            $post->status = 0;
        }
        $post->update();
        return $post;
    }
    
 
}