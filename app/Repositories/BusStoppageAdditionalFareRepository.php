<?php

namespace App\Repositories;

use App\Models\BusStoppageAdditionalFare;

class BusStoppageAdditionalFareRepository
{
    
    protected $busStoppageAdditionalFare;

    
    public function __construct( BusStoppageAdditionalFare $busStoppageAdditionalFare)
    {
        $this->busStoppageAdditionalFare = $busStoppageAdditionalFare;
    }

    
    public function getAll()
    {
        return $this->busStoppageAdditionalFare->get();
    }

    
    public function getById($id)
    {
        return $this->busStoppageAdditionalFare ->where('id', $id)->get();
    }

    
    public function save($data)
    {
        $busfare = new $this->busStoppageAdditionalFare;
        $busfare->ticket_price_id = $data['ticket_price_id'];
        $busfare->bus_seats_id = $data['bus_seats_id'];
        $busfare->additional_fare = $data['additional_fare'];
        $busfare->created_by = $data['created_by'];
        
        
        $busfare->save();

        return $busfare->fresh();
    }

    
    public function update($data, $id)
    {
        
        $busfare = $this->busStoppageAdditionalFare->find($id);

        // $boardingdroping = new $this->boardingDroping;
        $busfare = new $this->busStoppageAdditionalFare;
        $busfare->ticket_price_id = $data['ticket_price_id'];
        $busfare->bus_seats_id = $data['bus_seats_id'];
        $busfare->additional_fare = $data['additional_fare'];
        $busfare->created_by = $data['created_by'];

        $busfare->update();

        return $busfare;
    }

    /**
     * Update Post
     *
     * @param $data
     * @return Post
     */
    public function delete($id)
    {
        
        $busfare = $this->busStoppageAdditionalFare->find($id);
        $busfare->delete();

        return $busfare;
    }

}