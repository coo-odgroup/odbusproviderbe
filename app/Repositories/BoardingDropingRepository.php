<?php

namespace App\Repositories;

use App\Models\BoardingDroping;
use App\Models\Location;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class BoardingDropingRepository
{
    protected $boardingDroping;
    protected $location;
    public function __construct(BoardingDroping $boardingDroping,Location $location)
    {
        $this->boardingDroping = $boardingDroping;
        $this->location = $location;
    }
    public function getAll()
    {
        return $this->location->with(['boardingDropping'=>
        function($q){
            $q->whereNotIn('status',[2]);  
        }])
        ->select('id','name','status','created_at','updated_at','created_by')
        ->where('status',"!=",'2')
        ->orderBy('id','DESC')
        ->get();
        //return $this->boardingDroping->whereNotIn('status', [2])->get();
    }
    public function getById($id)
    {
        return $this->boardingDroping ->where('id', $id)->get();
    }
    public function getByLocationId($id)
    {
        return $this->boardingDroping->where('location_id', $id)->where("status",1)->get();
    }

    public function getModel($data, BoardingDroping $boardingdroping, $stoppage)
    {
        $boardingdroping->location_id = $data['location_id'];
        $boardingdroping->boarding_point = $stoppage;
        //$boardingdroping->dropping_point = $data['dropping_point'];
        $boardingdroping->created_by = $data['created_by'];
        $boardingdroping->status = 0;
        return $boardingdroping;
    }
    public function save($data)
    {
        $this->location=$this->location->find($data['location_id']);
        $stoppages=[];
        foreach($data['boarding_point'] as $stoppage)
        {
            
            $boardingdroping = new $this->boardingDroping;
            $boardingdroping->location_id = $data['location_id'];
            $boardingdroping->boarding_point = $stoppage['boarding_point'];
            $boardingdroping->landmark = $stoppage['landmark'];
            $boardingdroping->created_by = $data['created_by'];
            $boardingdroping->status = 0;
            $stoppages[]=$boardingdroping;
        }
        $this->location->boardingDropping()->saveMany($stoppages);
        return $data;
    }

    public function getUpdateModel(BoardingDroping $boardingDroping,$data)
    {
        $boardingDroping->location_id = $data['location_id'];
        $boardingDroping->boarding_point = $data['boarding_point'];
        $boardingDroping->landmark = $data['landmark'];       
        $boardingDroping->created_by = $data['created_by'];       
        return $boardingDroping;
    }
    public function update($data, $id)
    {

        $this->location=$this->location->find($data['location_id']);

        $allboarding_droping=$this->boardingDroping->where("location_id",$data['location_id'])->get();

        $allbdarr=[];

        if(count($allboarding_droping)>0){

            foreach($allboarding_droping as $ad){
                $allbdarr[]=$ad->id;
            }

        }
      

        $stoppages=[];
        foreach($data['boarding_point'] as $stoppage)
        { 
       
        if(isset($stoppage['id']) && $stoppage['id'] != null && $stoppage['id'] !=''){  /////////// update existing and add new ones ///
           
          //////// check if posted array has less items than table data and make status=2

          if(count($allbdarr) > 0 && in_array($stoppage['id'],$allbdarr)){

            if (($key = array_search($stoppage['id'], $allbdarr)) !== false) {
                unset($allbdarr[$key]);
            }

          }

          ////////////////////////////////

          
          
            $updateBoarding = $this->boardingDroping->find($stoppage['id']);

            $UpdRecord=$stoppage;
            $UpdRecord['location_id']=$data['location_id'];
            $UpdRecord['created_by']=$data['created_by'];
            $boardingDroping=$this->getUpdateModel($updateBoarding,$UpdRecord);
            $boardingDroping->update();

        }else{    
            $boardingdrop = new $this->boardingDroping;          
             $boardingdrop->location_id = $data['location_id'];
             $boardingdrop->boarding_point = $stoppage['boarding_point'];
             $boardingdrop->landmark = $stoppage['landmark'];
             $boardingdrop->created_by = $data['created_by'];
             $stoppages[]=$boardingdrop;

        }
     }

      //////// check if posted array has less items than table data and make status=2

     if(count($allbdarr) > 0){
         foreach($allbdarr as $a){
            $this->boardingDroping=$this->boardingDroping->find($a);
            $this->boardingDroping->where("id",$a)->update([ 'status' => 2 ]);
         }
     }

     /////////////////
        $this->location->boardingDropping()->saveMany($stoppages);
        return $data;
    }
    /**
     * Update Post
     *
     * @param $data
     * @return Post
     */
    public function delete($id)
    {
        $this->location=$this->location->find($id);
        $this->location->boardingDropping()->delete();
        return $id;
    }
    //BoardingDroping Data Table
    public function getBoardingDropingDT($request)   
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
        // Total records
        $totalRecords = $this->location
        ->whereNotIn('status', [2])->count();

        $totalRecordswithFilter = $this->location->with('boardingDropping')
        ->where('name', 'like', '%' .$searchValue . '%')
        ->where('status', '!=','2')->count();   

        $records = $this->location->with('boardingDropping')
        ->orderBy($columnName,$columnSortOrder)
        ->where('name', 'like', '%' .$searchValue . '%')
        ->skip($start)
        ->take($rowperpage)
        ->whereNotIn('status', [2])
        ->get();      
        
        $data_arr = array();
        foreach($records as $key=>$record)
        {
            $data_arr[]=$record->toArray();
            $data_arr[$key]['created_at']=date('j M Y h:i a',strtotime($record->created_at));
            $data_arr[$key]['created_by']=$record->created_by;
            //$data_arr[$key]['location_name']= $record->location->name;
            $data_arr[$key]['location_name']= $record->name;
        }
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        ); 
        return $response;
        
    }


    public function boardingData($request)   
    {
        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;

        $data= $this->location->with('boardingDropping')
                    ->where('status','!=' ,2)
                    ->whereHas('boardingDropping', function ($query){
                     $query->where('status', '!=','2');               
                     })
                    ->orderBy('name','ASC'); 

        // $data= $this->location->with(['boardingDropping' => function ($q){
        //                          $q->orderBy('id', 'DESC');}])
        //                      ->where('status','!=' ,2)
        //                     ->whereHas('boardingDropping', function ($query){
        //                            $query->where('status', '!=','2');               
        //                        });
                               
        
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
            $data = $data->where(function($query) use ($name) {
                        $query->where('name','like', '%' .$name . '%');
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
    public function changeStatus($locationId)
    {
        $boardingdroping = $this->boardingDroping->where('location_id',$locationId)
                                                    ->where('status', '!=', '2')->get();
                                            
        if($boardingdroping[0]->status==0){
            $this->boardingDroping->where('location_id', $locationId)
                                    ->where('status', '0')->update([ 'status' => 1 ]);
        }elseif($boardingdroping[0]->status==1){
            $this->boardingDroping->where('location_id', $locationId)
                                    ->where('status', '1')->update([ 'status' => 0 ]);
        }
        return $this->boardingDroping->where('location_id', $locationId)->where('status', '!=', '2')->get();
    }

}