<?php

namespace App\Repositories;

use App\Models\PreBooking;
use App\Models\PreBookingDetail;


class PreBookingRepository
{
    
    protected $preBooking;
    protected $preBookingDetail;
    
    public function __construct(PreBooking $preBooking, PreBookingDetail $preBookingDetail)
    {
        
        $this->preBooking = $preBooking;
        $this->preBookingDetail = $preBookingDetail;
    }

    
    public function getAll()
    {
        return $this->preBooking::with('preBookingDetail')->get();
    }

    
    public function getById($id)
    {
        return $this->preBooking::with('preBookingDetail') ->where('id', $id)->get();
    }

    //save preBooking***same like phase_one
    public function savePreBooking($data)
    {
        $prebooking = new $this->preBooking;
        
         do {
            $transactionId = date('YmdHis') . gettimeofday()['usec'];
        //     //$random = mt_rand( 1000, 9999 );
        
          } while ( $prebooking ->where( 'transaction_id', $transactionId )->exists());

        $prebooking->transaction_id =  $transactionId;
        $prebooking->user_id = $data['user_id'];
        $prebooking->bus_id = $data['bus_id'];
        $prebooking->j_day = $data['j_day'];
        $prebooking->journey_dt = $data['journey_dt'];
        $prebooking->bus_info = $data['bus_info'];
        $prebooking->customer_info = $data['customer_info'];
        $prebooking->total_fare = $data['total_fare'];
        $prebooking->is_coupon = $data['is_coupon'];
        $prebooking->coupon_code = $data['coupon_code'];
        $prebooking->coupon_discount = $data['coupon_discount'];
        $prebooking->discounted_fare = $data['discounted_fare'];
        $prebooking->customer_id = $data['customer_id'];
        $prebooking->created_by = $data['created_by'];
        
        $prebooking->save();
        foreach($data['preBookingDetail'] as $preBooking_Detail){
            $preBookingDetailRecord = new $this->preBookingDetail;   
            $preBookingDetailRecord->pre_booking_id = $prebooking->id;     
            $preBookingDetailRecord->journey_date =  $preBooking_Detail['journey_date'];
            $preBookingDetailRecord->j_day =  $preBooking_Detail['j_day'];
            $preBookingDetailRecord->bus_id =  $preBooking_Detail['bus_id'];
            $preBookingDetailRecord->seat_name =  $preBooking_Detail['seat_name'];
            $preBookingDetailRecord->created_by =  $preBooking_Detail['created_by'];
            $prebooking->preBookingDetail[] = $preBookingDetailRecord;
              
          }   
          $prebooking->push();
          
        return $prebooking->fresh();
       // var_dump($prebooking);
    }
    public function update($data, $id)
    {
        
        $prebooking = $this->preBooking->find($id);

        $prebooking->transaction_id = $data['transaction_id'];
        $prebooking->user_id = $data['user_id'];
        $prebooking->bus_id = $data['bus_id'];
        $prebooking->j_day = $data['j_day'];
        $prebooking->journey_dt = $data['journey_dt'];
        $prebooking->bus_info = $data['bus_info'];
        $prebooking->customer_info = $data['customer_info'];
        $prebooking->total_fare = $data['total_fare'];
        $prebooking->is_coupon = $data['is_coupon'];
        $prebooking->coupon_code = $data['coupon_code'];
        $prebooking->coupon_discount = $data['coupon_discount'];
        $prebooking->discounted_fare = $data['discounted_fare'];
        $prebooking->customer_id = $data['customer_id'];
        $prebooking->created_by = $data['created_by'];
        

        $prebooking->update();

        return $prebooking;
    }

    
    public function delete($id)
    {
        
        $prebooking = $this->preBooking->find($id);
        $prebooking->delete();

        return $prebooking;
    }


/*****************************/
/****Create Pre_booking_phase_one****/


    public function savePreBookingPhaseOne($data)
    {
        {
            $prebooking = new $this->preBooking;
            do {
                $transactionId = date('YmdHis') . gettimeofday()['usec'];
                 //$random = mt_rand( 1000, 9999 );
            
              } while ( $prebooking ->where( 'transaction_id', $transactionId )->exists());
            $prebooking->transaction_id =  $transactionId;
            $prebooking->user_id = $data['user_id'];
            $prebooking->bus_id = $data['bus_id'];
            $prebooking->j_day = $data['j_day'];
            $prebooking->journey_dt = $data['journey_dt'];
            $prebooking->bus_info = $data['bus_info'];
            $prebooking->customer_info = $data['customer_info'];
            $prebooking->total_fare = $data['total_fare'];
            $prebooking->is_coupon = $data['is_coupon'];
            $prebooking->coupon_code = $data['coupon_code'];
            $prebooking->coupon_discount = $data['coupon_discount'];
            $prebooking->discounted_fare = $data['discounted_fare'];
            $prebooking->customer_id = $data['customer_id'];
            $prebooking->created_by = $data['created_by'];
            
    
            $prebooking->save();
            foreach($data['preBookingDetails'] as $preBooking_Detail){
                $preBookingDetailRecord = new $this->preBookingDetail;   
                $preBookingDetailRecord->pre_booking_id = $prebooking->id;     
                $preBookingDetailRecord->journey_date =  $preBooking_Detail['journey_date'];
                $preBookingDetailRecord->j_day =  $preBooking_Detail['j_day'];
                $preBookingDetailRecord->bus_id =  $preBooking_Detail['bus_id'];
                $preBookingDetailRecord->seat_name =  $preBooking_Detail['seat_name'];
                $preBookingDetailRecord->created_by =  $preBooking_Detail['created_by'];
                $prebooking->preBookingDetail[] = $preBookingDetailRecord;
                  
              }   
              $prebooking->push();
              
            return $prebooking->fresh();
    }

    }
    /*****************************/
    /****Pre_booking_phase_two****/

    public function updatePreBookingTwo($data, $transaction_id)
    {
        
        $prebooking = $this->preBooking->where('transaction_id',$transaction_id)->first();

        //$prebooking->transaction_id = $data['transaction_id'];
        
        
        $prebooking->customer_info = $data['customer_info'];
        $prebooking->customer_id = $data['customer_id'];
       
        

        $prebooking->update();

        return $prebooking;
    }

}