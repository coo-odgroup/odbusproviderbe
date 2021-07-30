<?php

namespace App\Repositories;

use App\Models\BusAmenities;

class BusAmenitiesRepository
{
    
    protected $busAmenities;

    
    public function __construct(BusAmenities $busAmenities)
    {
        $this->busAmenities = $busAmenities;
    }

    
    public function getAll()
    {
        return $this->busAmenities->whereNotIn('status', [2])->get();
    }

    
    public function getById($id)
    {
        return $this->busAmenities->where('bus_id', $id)->get();
    }
    public function save($data)
    {
        $busamenities = new $this->busAmenities;
        $busamenities->bus_id = $data['bus_id'];
        $busamenities->amenities_id = $data['amenities_id'];
        $busamenities->created_by = $data['created_by'];
        $busamenities->save();
        return $busamenities->fresh();
    }
    public function update($data, $id)
    {
        $busamenities = $this->busAmenities->find($id);
        $busamenities->bus_id = $data['bus_id'];
        $busamenities->amenities_id = $data['amenities_id'];
        $busamenities->created_by = $data['created_by'];
        $busamenities->update();
        return $busamenities;
    }

    
    public function delete($id)
    {
        $post = $this->busAmenities->find($id);
        $post->status = 2;
        $post->update();
        return $post;
    }
    //BusAmenities Data Table
    public function getAllBusAmenitiesDT($request)   
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
        $totalRecords = $this->busAmenities->select('count(*) as allcount')->count();
        $totalRecordswithFilter = $this->busAmenities->select('count(*) as allcount')->where('bus_id', 'like', '%' .$searchValue . '%')->count();

        // Fetch records
        $records = $this->busAmenities->orderBy($columnName,$columnSortOrder)
            ->where('bus_id', 'like', '%' .$searchValue . '%')
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        $sno = $start+1;
        foreach($records as $record)
        {
            $id = $record->id;
            $bus_id = $record->bus_id;
            $amenities_id = $record->amenities_id;
            $created_at = date('j M Y h:i a',strtotime($record->created_at));
            $updated_at = date('j M Y h:i a',strtotime($record->updated_at));            
            $created_by = $record->created_by;
            $status = $record->status;

            $data_arr[] = array(
                "id" => $id,
                "bus_id" => $bus_id,
                "amenities_id" => $amenities_id,
                "created_at" => $created_at,
                "updated_at" => $updated_at,
                "created_by" => $created_by,
                "status" => $status
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        ); 
        return $response;
        
    }

}