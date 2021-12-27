<?php

namespace App\Repositories;
use App\Models\Bus;
use App\Models\SpecialFare;
use App\Models\BusSpecialFare;
use Illuminate\Support\Facades\Log;

class BusSpecialFareRepository
{
    protected $specialFare;
    protected $bus;
    protected $busSpecialFare;
    
    public function __construct(SpecialFare $specialFare,Bus $bus,BusSpecialFare $busSpecialFare)
    {
        $this->specialFare = $specialFare;
        $this->bus = $bus;
        $this->busSpecialFare = $busSpecialFare;
    }
    public function getPivotData($id){
        return $this->busSpecialFare->where('special_fare_id', $id)->get();
    }
    /**
     * Get Bus Secial Fare List
     *
     * @param $id
     * @return mixed
     */
    public function getAll()
    {
        return $this->specialFare->whereNotIn('status', [2])->get();
    } 

    public function busSpecialFareData($request)
    {
        

         $paginate = $request['rows_number'] ;
         $name = $request['name'] ;
       

        $data= $this->specialFare->with('bus','bus.busOperator')
                    ->whereNotIn('status', [2])->orderBy('id','DESC');


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

        $data=$data->paginate($paginate);

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
            $rowperpage=Config::get('constants.ALL_RECORDS');
        }
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');
        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value
        // Total records//
        $totalRecords=$this->specialFare->whereHas('bus')->whereNotIn('status', [2])->count();
        $totalRecordswithFilter=$this->specialFare->with('bus')  
        ->whereHas('bus', function ($query) use ($searchValue){
               $query->where('name', 'like', '%' .$searchValue . '%');               
           })->whereNotIn('status', [2])->count();
        //Fetch records//
        $records = $this->specialFare->with('bus')
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
     * Get Bus Special Fare by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->specialFare->with('bus')->where('id', $id)->get();
    }

    /**
     * Save Bus Special Fare
     *
     * @param $data
     * @return bus special fare
     */
    public function getModel($data, SpecialFare $specialfare)
    {
        $specialfare->bus_operator_id = $data['bus_operator_id'];
        $specialfare->source_id = $data['source_id'];
        $specialfare->destination_id = $data['destination_id'];
        $specialfare->date = $data['date'];
        $specialfare->seater_price = $data['seater_price'];
        $specialfare->sleeper_price = $data['sleeper_price'];
        $specialfare->reason = $data['reason'];
        $specialfare->created_by = $data['created_by'];
    
        return $specialfare;
    }

    public function save($data)
    {
        $specialfare = new $this->specialFare;
        $specialfare=$this->getModel($data,$specialfare);
        $specialfare->status = 0;
        $specialfare->save();
        $bus_id = $this->bus::find($data['bus_id']);
        $specialfare->bus()->attach($data['bus_id']);
        return $specialfare;
    }
    /**
     * Update Bus Special fare
     *
     * @param $data
     * @return busSpecialFare
     */
    public function update($data, $id)
    {
        $specialfare = $this->specialFare->find($id);
        $specialfare=$this->getModel($data,$specialfare);
        $specialfare->update();
        $bus_id = $this->bus::find($data['bus_id']);
        $specialfare->bus()->sync($data['bus_id']);
        return $specialfare;
    }
    /**
     * Delete Bus Special Fare
     *
     * @param $data
     * @return busSpecialFare
    */
    public function delete($id)
    {
        $busspecialFare = $this->specialFare->find($id);
        $busspecialFare->status = 2;
        $busspecialFare->update();
        $busspecialFare->bus()->detach();
        return $busspecialFare;
    }
    public function changeStatus($id)
    {
        $post = $this->specialFare->find($id);
        if($post->status==0){
            $post->status = 1;
        }elseif($post->status==1){
            $post->status = 0;
        }
        $post->update();
        return $post;
    }
    
 
}