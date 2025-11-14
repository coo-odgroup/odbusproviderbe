<?php

namespace App\Repositories;

use App\Models\CouponAssignedBus;

class CouponAssignedBusRepository
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
        return $this->couponAssignedBus->where('id', $id)->first();
    }

    public function save($data)
    {
        $couponassignedBus = new CouponAssignedBus();
        $couponassignedBus->bus_id       = $data['bus_id'];
        $couponassignedBus->coupon_id    = $data['coupon_id'];
        $couponassignedBus->created_by   = $data['created_by'];

        $couponassignedBus->save();

        return $couponassignedBus->fresh();
    }

    public function update($data, $id)
    {
        $couponassignedBus = $this->couponAssignedBus->find($id);

        if (!$couponassignedBus) {
            return null;
        }

        $couponassignedBus->bus_id       = $data['bus_id'];
        $couponassignedBus->coupon_id    = $data['coupon_id'];
        $couponassignedBus->created_by   = $data['created_by'];

        $couponassignedBus->save();

        return $couponassignedBus;
    }

    public function delete($id)
    {
        $couponassignedBus = $this->couponAssignedBus->find($id);

        if (!$couponassignedBus) {
            return false;
        }

        return $couponassignedBus->delete();
    }
}
