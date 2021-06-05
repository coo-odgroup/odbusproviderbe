<?php

namespace App\Repositories;

use App\Models\BusSlots;

class BusSlotsRepository
{
    
    protected $busSlots;

    
    public function __construct(BusSlots $busSlots)
    {
        $this->busSlots = $busSlots;
    }

    
    public function getAll()
    {
        return $this->busSlots->get();
    }

    
    public function getById($id)
    {
        return $this->busSlots ->where('id', $id)->get();
    }

    
    public function save($data)
    {
        $busslots = new $this->busSlots;
        $busslots->bus_id = $data['bus_id'];
        $busslots->name = $data['name'];
        $busslots->type = $data['type'];
        $busslots->created_by = $data['created_by'];
        
        
        $busslots->save();

        return $busslots->fresh();
    }

    
    public function update($data, $id)
    {
        
        $busextraFare = $this->busSlots->find($id);

        $busslots->bus_id = $data['bus_id'];
        $busslots->bus_id = $data['bus_id'];
        $busslots->name = $data['name'];
        $busslots->type = $data['type'];
        $busslots->created_by = $data['created_by'];

        $busslots->update();

        return $busslots;
    }

    
    public function delete($id)
    {
        
        $busslots = $this->busSlots->find($id);
        $busslots->delete();
      
        return $busslots;
    }

}