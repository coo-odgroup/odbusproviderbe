<?php

namespace App\Repositories;

use App\Models\BusSeatsExtra;

class BusSeatsExtraRepository
{
    
    protected $busSeatsExtra;

    
    public function __construct(BusSeatsExtra $busSeatsExtra)
    {
        $this->busSeatsExtra = $busSeatsExtra;
    }

    
    public function getAll()
    {
        return $this->busSeatsExtra->get();
    }

    
    public function getById($id)
    {
        return $this->busSeatsExtra ->where('id', $id)->get();
    }

    
    public function save($data)
    {
        $busseatsExtra = new $this->busSeatsExtra;
        $busseatsExtra->bus_id = $data['bus_id'];
        $busseatsExtra->journey_dt = $data['journey_dt'];
        $busseatsExtra->type = $data['type'];
        $busseatsExtra->seat_type = $data['seat_type'];
        $busseatsExtra->seat_number = $data['seat_number'];
        $busseatsExtra->created_by = $data['created_by'];
        
        
        $busseatsExtra->save();

        return $busseatsExtra->fresh();
    }

    
    public function update($data, $id)
    {
        
        $busseatsExtra = $this->busSeatsExtra->find($id);

        $busseatsExtra->bus_id = $data['bus_id'];
        $busseatsExtra->journey_dt = $data['journey_dt'];
        $busseatsExtra->type = $data['type'];
        $busseatsExtra->seat_type = $data['seat_type'];
        $busseatsExtra->seat_number = $data['seat_number'];
        $busseatsExtra->created_by = $data['created_by'];

        $busseatsExtra->update();

        return $busseatsExtra;
    }

    
    public function delete($id)
    {
        
        $busseatsExtra = $this->busSeatsExtra->find($id);
        $busseatsExtra->delete();

        return $busseatsExtra;
    }

}