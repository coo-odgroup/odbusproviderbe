<?php

namespace App\Repositories;
use App\Models\Amenities;
use Illuminate\Support\Facades\Log;
class AmenitiesRepository 
//extends AbstractRepository
{
    protected $amenities;
    public function __construct(Amenities $amenities)
    {
        $this->amenities = $amenities;
    }
    public function getAll()
    {
        return $this->amenities->whereNotIn('status', [2])->get();
    }

    public function getAmenitiesData($request)
    {
        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;
       

        $data= $this->amenities->whereNotIn('status', [2])
                             ->orderBy('id','DESC');

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
            $data=$data->where('name', $name);
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
        $totalRecords = $this->amenities
        ->whereNotIn('status', [2])
        ->count();
        $totalRecordswithFilter = $this->amenities
        ->where('name', 'like', "%" .$searchValue . "%")
        ->whereNotIn('status', [2])
        ->count();
        
        $records = $this->amenities->orderBy($columnName,$columnSortOrder)
            ->where('name', "like", "%" .$searchValue . "%")
            ->whereNotIn('status', [2])
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();
        foreach($records as $key=>$record)
        {
            $data_arr[]=$record->toArray();
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

    public function getById($id)
    {
        return $this->amenities
            ->where('id', $id)
            ->get();
    }
    public function getModel($data, Amenities $amenity)
    {
        $amenity->name = $data['name'];
        $amenity->icon = $data['icon'];
        $amenity->created_by = $data['created_by'];
        return $amenity;
    }
    /**
     * Save amenities
     *
     * @param $data
     * @return Amenities
     */
    public function save($data)
    {
        $amenity = new $this->amenities;
        $amenity=$this->getModel($data,$amenity);
        $amenity->save();
        return $amenity;
    }
    /**
     * Update amenities
     *
     * @param $data
     * @return Amenities
     */
    public function update($data, $id)
    {
        Log::info($data);
        $amenity = $this->amenities->find($id);
        $amenity=$this->getModel($data,$amenity);
        $amenity->update();
        return $amenity;
    }
    /**
     * Update amenities
     *
     * @param $data
     * @return Amenities
     */
    public function delete($id)
    {
        $amenity = $this->amenities->find($id);
        $amenity->status = 2;
        $amenity->update();
        return $amenity;
    }

    public function changeStatus($data,$id)
    {
        $amenity = $this->amenities->find($id);
        $amenity->reason = $data['reason'];
        if($amenity->status==0){
            $amenity->status = 1;
        }elseif($amenity->status==1){
            $amenity->status = 0;
        }
        $amenity->update();
        return $amenity;
    }

}