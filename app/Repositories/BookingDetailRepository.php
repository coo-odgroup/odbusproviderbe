<?php

namespace App\Repositories;

use App\Models\BookingDetail;

class BookingDetailRepository
{
    
    protected $bookingDetail;

    
    public function __construct(BookingDetail $bookingDetail)
    {
        $this->bookingDetail = $bookingDetail;
    }

    
    public function getAll()
    {
        return $this->bookingDetail->whereNotIn('status', [2])->get();
    }

    
    public function getById($id)
    {
        return $this->bookingDetail ->where('id', $id)->get();
    }

    
    public function save($data)
    {
        $bookingdetail = new $this->bookingDetail;
        $bookingdetail->booking_id = $data['booking_id'];
        $bookingdetail->pnr = $data['pnr'];
        $bookingdetail->jrny_dt = $data['jrny_dt'];
        $bookingdetail->j_day = $data['j_day'];
        $bookingdetail->bus_id = $data['bus_id'];
        $bookingdetail->seat_no = $data['seat_no'];
        $bookingdetail->passenger_name = $data['passenger_name'];
        $bookingdetail->passenger_gender = $data['passenger_gender'];
        $bookingdetail->passenger_age = $data['passenger_age'];
        $bookingdetail->created_by = $data['created_by'];
        


        $bookingdetail->save();

        return $bookingdetail->fresh();

    }
    public function update($data, $id)
    {
        
        $bookingdetail = $this->bookingDetail->find($id);

        $bookingdetail->booking_id = $data['booking_id'];
        $bookingdetail->pnr = $data['pnr'];
        $bookingdetail->jrny_dt = $data['jrny_dt'];
        $bookingdetail->j_day = $data['j_day'];
        $bookingdetail->bus_id = $data['bus_id'];
        $bookingdetail->seat_no = $data['seat_no'];
        $bookingdetail->passenger_name = $data['passenger_name'];
        $bookingdetail->passenger_gender = $data['passenger_gender'];
        $bookingdetail->passenger_age = $data['passenger_age'];
        $bookingdetail->created_by = $data['created_by'];
        
        $bookingdetail->update();

        return $bookingdetail;
    }

    
    public function delete($id)
    {
        
        $bookingdetail = $this->bookingDetail->find($id);
        $bookingdetail->status = 2;
        $bookingdetail->delete();

        return $bookingdetail;
    }

}