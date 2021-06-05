<?php

namespace App\Repositories;

use App\Models\PreBookingDetail;

class PreBookingDetailRepository
{
    
    protected $preBookingDetail;

    
    public function __construct(PreBookingDetail $preBookingDetail)
    {
        $this->preBookingDetail = $preBookingDetail;
    }

    
    public function getAll()
    {
        return $this->preBookingDetail->get();
    }

    
    public function getById($id)
    {
        return $this->preBookingDetail ->where('id', $id)->get();
    }

    
    public function save($data)
    {
        $prebookingDetail = new $this->preBookingDetail;
        $prebookingDetail->pre_booking_id = $data['pre_booking_id'];
        $prebookingDetail->journey_date = $data['journey_date'];
        $prebookingDetail->j_day = $data['j_day'];
        $prebookingDetail->bus_id = $data['bus_id'];
        $prebookingDetail->seat_name = $data['seat_name'];
        $prebookingDetail->created_by = $data['created_by'];
        

        $prebookingDetail->save();

        return $prebookingDetail->fresh();

    }
    public function update($data, $id)
    {
        
        $prebookingDetail = $this->preBookingDetail->find($id);

        $prebookingDetail->pre_booking_id = $data['pre_booking_id'];
        $prebookingDetail->journey_date = $data['journey_date'];
        $prebookingDetail->j_day = $data['j_day'];
        $prebookingDetail->bus_id = $data['bus_id'];
        $prebookingDetail->seat_name = $data['seat_name'];
        $prebookingDetail->created_by = $data['created_by'];

        $prebookingDetail->update();

        return $prebookingDetail;
    }

    
    public function delete($id)
    {
        
        $prebookingDetail = $this->preBookingDetail->find($id);
        $prebookingDetail->delete();

        return $prebookingDetail;
    }

}