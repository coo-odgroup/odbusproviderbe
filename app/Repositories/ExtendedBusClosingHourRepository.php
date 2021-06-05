<?php

namespace App\Repositories;

use App\Models\ExtendedBusClosingHours;

class ExtendedBusClosingHourRepository
{
    protected $extendedbusClosingHour;
    public function __construct(ExtendedBusClosingHours $extendedbusClosingHour)
    {
        $this->extendedbusClosingHour = $extendedbusClosingHour;
    }
    public function getAll()
    {
        return $this->extendedbusClosingHour->whereNotIn('status', [2])->get();
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
        $totalRecords = $this->extendedbusClosingHour->select('COUNT(*) as allcount')->count();
        $totalRecordswithFilter = $this->extendedbusClosingHour
        ->where('city_id', 'like', "%" .$searchValue . "%")
        ->count();
        
        $records = $this->extendedbusClosingHour->orderBy($columnName,$columnSortOrder)
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
        return $this->extendedbusClosingHour->where('id', $id)->get();
    }

    
    public function save($data)
    {
        $extendedbusClosingHour = new $this->extendedbusClosingHour;
        $extendedbusClosingHour->bus_id = $data['bus_id'];
        $extendedbusClosingHour->city_id = $data['city_id'];
        $extendedbusClosingHour->dep_time = $data['dep_time'];
        $extendedbusClosingHour->closing_hours = $data['closing_hours'];
        
        
        $extendedbusClosingHour->save();

        return $extendedbusClosingHour->fresh();
    }

    
    public function update($data, $id)
    {
        
        $extendedbusClosingHour = $this->extendedbusClosingHour->findOrFail($id);

        $extendedbusClosingHour->bus_id = $data['bus_id'];
        $extendedbusClosingHour->city_id = $data['city_id'];
        $extendedbusClosingHour->dep_time = $data['dep_time'];
        $extendedbusClosingHour->closing_hours = $data['closing_hours'];

        $extendedbusClosingHour->update();

        return $extendedbusClosingHour;
    }

    
    public function delete($id)
    {
        $extendedbusClosingHour = $this->extendedbusClosingHour->whereNotIn('status', [1])->findOrFail($id);
        $extendedbusClosingHour->status = 2;
        $extendedbusClosingHour->update();

        return $extendedbusClosingHour;
    }

}