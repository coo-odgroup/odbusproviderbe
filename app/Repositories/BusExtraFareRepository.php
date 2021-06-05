<?php

namespace App\Repositories;

use App\Models\BusExtraFare;

class BusExtraFareRepository
{
    
    protected $busExtraFare;

    
    public function __construct(BusExtraFare $busExtraFare)
    {
        $this->busExtraFare = $busExtraFare;
    }

    
    public function getAll()
    {
        return $this->busExtraFare->get();
    }

    
    public function getById($id)
    {
        return $this->busExtraFare ->where('id', $id)->get();
    }

    
    public function save($data)
    {
        $busextraFare = new $this->busExtraFare;
        $busextraFare->bus_id = $data['bus_id'];
        $busextraFare->type = $data['type'];
        $busextraFare->journey_date = $data['journey_date'];
        $busextraFare->seat_fare = $data['seat_fare'];
        $busextraFare->sleeper_fare = $data['sleeper_fare'];
        $busextraFare->created_by = $data['created_by'];
        
        
        $busextraFare->save();

        return $busextraFare->fresh();
    }

    
    public function update($data, $id)
    {
        
        $busextraFare = $this->busExtraFare->find($id);

        $busextraFare->bus_id = $data['bus_id'];
        $busextraFare->type = $data['type'];
        $busextraFare->journey_date = $data['journey_date'];
        $busextraFare->seat_fare = $data['seat_fare'];
        $busextraFare->sleeper_fare = $data['sleeper_fare'];
        $busextraFare->created_by = $data['created_by'];

        $busextraFare->update();

        return $busextraFare;
    }

    
    public function delete($id)
    {
        
        $busextraFare = $this->busExtraFare->find($id);
        $busextraFare->delete();

        return $busextraFare;
    }

}