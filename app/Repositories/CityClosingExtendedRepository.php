<?php

namespace App\Repositories;

use App\Models\CityClosingExtended;

class CityClosingExtendedRepository
{
    
    protected $cityClosingExtended;

    
    public function __construct(CityClosingExtended $cityClosingExtended)
    {
        $this->cityClosingExtended = $cityClosingExtended;
    }

    
    public function getAll()
    {
        return $this->cityClosingExtended->get();
    }

    
    public function getById($id)
    {
        return $this->cityClosingExtended ->where('id', $id)->get();
    }

    
    public function save($data)
    {
        $cityclosingExtended = new $this->cityClosingExtended;
        $cityclosingExtended->bus_id = $data['bus_id'];
        $cityclosingExtended->location_id = $data['location_id'];
        $cityclosingExtended->journey_date = $data['journey_date'];
        $cityclosingExtended->closing_hours = $data['closing_hours'];
        $cityclosingExtended->created_by = $data['created_by'];
        
        
        $cityclosingExtended->save();

        return $cityclosingExtended->fresh();
    }

    
    public function update($data, $id)
    {
        
        $cityclosingExtended = $this->cityClosingExtended->find($id);

        $cityclosingExtended->bus_id = $data['bus_id'];
        $cityclosingExtended->location_id = $data['location_id'];
        $cityclosingExtended->journey_date = $data['journey_date'];
        $cityclosingExtended->closing_hours = $data['closing_hours'];
        $cityclosingExtended->created_by = $data['created_by'];

        $cityclosingExtended->update();

        return $cityclosingExtended;
    }

    
    public function delete($id)
    {
        
        $cityclosingExtended = $this->cityClosingExtended->find($id);
        $cityclosingExtended->delete();

        return $cityclosingExtended;
    }

}