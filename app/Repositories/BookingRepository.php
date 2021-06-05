<?php

namespace App\Repositories;

use App\Models\Booking;

class BookingRepository
{
    
    protected $booking;

    
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    
    public function getAll()
    {
        return $this->booking->whereNotIn('status', [2])->get();
    }

    
    public function getById($id)
    {
        return $this->booking ->where('id', $id)->get();
    }

    
    public function saveBooking($data)
    {
        $bookings = new $this->booking;

        do {
            $PNR = date('YmdHis');
             //$random = mt_rand( 1000, 9999 );
        
          } while ( $bookings ->where( 'pnr', $PNR )->exists());

        $bookings->pnr = $PNR;

        $bookings->transaction_id = $data['transaction_id'];
       // $bookings->pnr = $data['pnr'];
        $bookings->customer_id = $data['customer_id'];
        $bookings->user_id = $data['user_id'];
        $bookings->bus_id = $data['bus_id'];
        $bookings->source_id = $data['source_id'];
        $bookings->destination_id = $data['destination_id'];
        $bookings->j_day = $data['j_day'];
        $bookings->journey_dt = $data['journey_dt'];
        $bookings->boarding_id = $data['boarding_id'];
        $bookings->dropping_id = $data['dropping_id'];
        $bookings->boarding_time = $data['boarding_time'];
        $bookings->dropping_time = $data['dropping_time'];
        $bookings->bus_info = $data['bus_info'];
        $bookings->customer_info = $data['customer_info'];
        $bookings->total_fare = $data['total_fare'];
        $bookings->ownr_fare = $data['ownr_fare'];
        $bookings->is_coupon = $data['is_coupon'];
        $bookings->coupon_code = $data['coupon_code'];
        $bookings->coupon_discount = $data['coupon_discount'];
        $bookings->discounted_fare = $data['discounted_fare'];
        $bookings->origin = $data['origin'];
        $bookings->app_type = $data['app_type'];
        $bookings->typ_id = $data['typ_id'];
        $bookings->created_by = $data['created_by'];


        $bookings->save();
        
        return $bookings->fresh();

    }
    public function update($data, $id)
    {
        
        $bookings = $this->booking->find($id);

        $bookings->transaction_id = $data['transaction_id'];
        $bookings->pnr = $data['pnr'];
        $bookings->customer_id = $data['customer_id'];
        $bookings->user_id = $data['user_id'];
        $bookings->bus_id = $data['bus_id'];
        $bookings->source_id = $data['source_id'];
        $bookings->destination_id = $data['destination_id'];
        $bookings->j_day = $data['j_day'];
        $bookings->journey_dt = $data['journey_dt'];
        $bookings->boarding_id = $data['boarding_id'];
        $bookings->dropping_id = $data['dropping_id'];
        $bookings->boarding_time = $data['boarding_time'];
        $bookings->dropping_time = $data['dropping_time'];
        $bookings->bus_info = $data['bus_info'];
        $bookings->customer_info = $data['customer_info'];
        $bookings->total_fare = $data['total_fare'];
        $bookings->ownr_fare = $data['ownr_fare'];
        $bookings->is_coupon = $data['is_coupon'];
        $bookings->coupon_code = $data['coupon_code'];
        $bookings->coupon_discount = $data['coupon_discount'];
        $bookings->discounted_fare = $data['discounted_fare'];
        $bookings->origin = $data['origin'];
        $bookings->app_type = $data['app_type'];
        $bookings->typ_id = $data['typ_id'];
        $bookings->created_by = $data['created_by'];
        $bookings->update();

        return $bookings;
    }

    
    public function delete($id)
    {
        
        $bookings = $this->booking->find($id);
        $bookings->status = 2;
        $bookings->delete();

        return $bookings;
    }

}