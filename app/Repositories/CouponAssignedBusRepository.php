<?php

namespace App\Repositories;

use App\Models\CouponAssignedBus;

class couponAssignedBusRepository
{
    
    protected $couponAssignedBus;

    
    public function __construct(CouponAssignedBus $couponAssignedBus)
    {
        $this->couponAssignedBus = $couponAssignedBus;
    }

    
    public function getAll()
    {
        return $this->couponAssignedBus->get();
    }

    
    public function getById($id)
    {
        return $this->couponAssignedBus ->where('id', $id)->get();
    }

    
    public function save($data)
    {
        $couponassignedBus = new $this->couponAssignedBus;
        $couponassignedBus->bus_id = $data['bus_id'];
        $couponassignedBus->coupon_id = $data['coupon_id'];
        $couponassignedBus->created_by = $data['created_by'];
        
        $couponassignedBus->save();

        return $couponassignedBus->fresh();
    }

    
    public function update($data, $id)
    {
        
        $couponassignedBus = $this->couponAssignedBus->find($id);

        
        $reasons->name = $data['name'];
        $couponassignedBus->bus_id = $data['bus_id'];
        $couponassignedBus->coupon_id = $data['coupon_id'];
        $couponassignedBus->created_by = $data['created_by'];

        return $couponassignedBus;
    }

    
    public function delete($id)
    {
        
        $couponassignedBus = $this->couponAssignedBus->find($id);
        $couponassignedBus->delete();

        return $reasons;
    }

}