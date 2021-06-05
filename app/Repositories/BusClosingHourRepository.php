<?php

namespace App\Repositories;

use App\Models\BusClosingHours;

class BusClosingHourRepository
{
    
    protected $busClosingHour;

    
    public function __construct(BusClosingHours $busClosingHours)
    {
        $this->busClosingHours = $busClosingHours;
    }
    public function getAll()
    {
        return $this->busClosingHours->whereNotIn('status', [2])->get();
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

        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[0]['data']; // Column name
        
        $searchValue = $search_arr[0]['value']; // Search value
        $totalRecords = $this->busClosingHours->select('COUNT(*) as allcount')->count();
        $totalRecordswithFilter = $this->busClosingHours
        ->where('city_id', 'like', "%" .$searchValue . "%")
        ->count();
        
        $records = $this->busClosingHours->orderBy($columnName,$columnSortOrder)
            ->where('city_id', "like", "%" .$searchValue . "%")

            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        $sno = $start+1;
        foreach($records as $record){
            $id = $record->id;
            $bus_id = $record->bus_id;
            $city_id = $record->city_id;
            $dep_time = $record->dep_time;
            $closing_hours = $record->closing_hours;
            $status = $record->status;

            $data_arr[] = array(
                "id" => $id,
                "bus_id" => $bus_id,
                "city_id" => $city_id,
                "dep_time" => $dep_time,
                "closing_hours" => $closing_hours,
                "status" => $status
            );
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
        return $this->busClosingHours->where('id', $id)->get();
    }
    public function save($data)
    {
        $busClosingHours = new $this->busClosingHours;
        $busClosingHours->bus_id = $data['bus_id'];
        $busClosingHours->city_id = $data['city_id'];
        $busClosingHours->dep_time = $data['dep_time'];
        $busClosingHours->closing_hours = $data['closing_hours'];
        $busClosingHours->save();
        return $busClosingHours->fresh();
    }

    
    public function update($data, $id)
    {
        $busClosingHours = $this->busClosingHours->findOrFail($id);
        $busClosingHours->bus_id = $data['bus_id'];
        $busClosingHours->city_id = $data['city_id'];
        $busClosingHours->dep_time = $data['dep_time'];
        $busClosingHours->closing_hours = $data['closing_hours'];

        $busClosingHours->update();

        return $busClosingHours;
    }

    
    public function delete($id)
    {
        $busClosingHours = $this->busClosingHours->whereNotIn('status', [2])->findOrFail($id);
        $busClosingHours->status = 2;
        $busClosingHours->update();
        return $busClosingHours;
    }

}