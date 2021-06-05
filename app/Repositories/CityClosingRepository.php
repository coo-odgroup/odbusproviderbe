<?php

namespace App\Repositories;

use App\Models\CityClosing;

class CityClosingRepository
{
    
    protected $cityClosing;

    
    public function __construct(CityClosing $cityClosing)
    {
        $this->cityClosing = $cityClosing;
    }

    
    public function getAll()
    {
        return $this->cityClosing->get();
    }

    
    public function getById($id)
    {
        return $this->cityClosing ->where('id', $id)->get();
    }

    
    public function save($data)
    {
        $cityclosing = new $this->cityClosing;
        $cityclosing->bus_id = $data['bus_id'];
        $cityclosing->location_id = $data['location_id'];
        $cityclosing->closing_hours = $data['closing_hours'];
        $cityclosing->created_by = $data['created_by'];
        
        
        $cityclosing->save();

        return $cityclosing->fresh();
    }

    
    public function update($data, $id)
    {
        
        $cityclosing = $this->cityClosing->find($id);

        $cityclosing->bus_id = $data['bus_id'];
        $cityclosing->location_id = $data['location_id'];
        $cityclosing->closing_hours = $data['closing_hours'];
        $cityclosing->created_by = $data['created_by'];

        $cityclosing->update();

        return $cityclosing;
    }

    
    public function delete($id)
    {
        
        $cityclosing = $this->cityClosing->find($id);
        $cityclosing->delete();

        return $cityclosing;
    }

}