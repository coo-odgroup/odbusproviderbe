<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Validator;
use App\Services\BookingService;
use App\Repositories\BookingRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use InvalidArgumentException;

class BookingController extends Controller
{
    protected $bookingService;
    protected $bookingRepository;


    
    public function __construct(BookingService $bookingService,
                             BookingRepository $bookingRepository)
    {
        $this->bookingService = $bookingService;
        $this->bookingRepository = $bookingRepository;
    }


    public function getAllBooking() {

        //$bookings = $this->bookingService->getAll();
        $bookings = $this->bookingRepository->getAll();
        $output ['status']=1;
        $output ['message']='All Data Fetched Successfully';
        $output ['result']=$bookings;
        return response($output, 200);
    }

    public function createBooking(Request $request) {
        $data = $request->only([

                            'transaction_id','pnr','customer_id','user_id','bus_id','source_id',
                            'destination_id','j_day','journey_dt','boarding_id','dropping_id',
                            'boarding_time','dropping_time','bus_info','customer_info','total_fare',
                            'ownr_fare','is_coupon','coupon_code','coupon_discount','discounted_fare',
                            'origin','app_type','typ_id','created_by'
            
          ]);
        
          $BookingRules = [
            'transaction_id' => 'required',
            //'pnr' => 'required',
            'customer_id' => 'required',
            'user_id' => 'required',
            'bus_id' => 'required',
            'source_id' => 'required',
            'destination_id' => 'required',
            'j_day' => 'required',
            'journey_dt' => 'required',
            'boarding_id' => 'required',
            'dropping_id' => 'required',
            'boarding_time' => 'required',
            'dropping_time' => 'required',
            'bus_info' => 'required',
            'customer_info' => 'required',
            'total_fare' => 'required',
            'ownr_fare' => 'required',
            'is_coupon' => 'required',
            'coupon_code' => 'required',
            'coupon_discount' => 'required',
            'discounted_fare' => 'required',
            'origin' => 'required',
            'app_type' => 'required',
            'typ_id' => 'required',
            'created_by' => 'required',
             
        ];
        
        $BookingValidation = Validator::make($data, $BookingRules);


        if ($BookingValidation->fails()) {
            $errors = $BookingValidation->errors();
            return $errors->toJson();
          }
      $result = ['status' => 200];

      try {
          //$result['data'] = $this->bookingService->saveBookingData($data);
          $result['data'] = $this->bookingRepository->saveBooking($data);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }

      return response()->json($result, $result['status']);

    } 

    public function updateBooking(Request $request, $id) {
        $data = $request->only([
                            'transaction_id','pnr','customer_id','user_id','bus_id','source_id',
                            'destination_id','j_day','journey_dt','boarding_id','dropping_id',
                            'boarding_time','dropping_time','bus_info','customer_info',
                            'total_fare','ownr_fare','is_coupon','coupon_code','coupon_discount',
                            'discounted_fare','origin','app_type','typ_id','created_by'
            
        ]);
        $BookingRules = [
            
            'transaction_id' => 'required',
            'pnr' => 'required',
            'customer_id' => 'required',
            'user_id' => 'required',
            'bus_id' => 'required',
            'source_id' => 'required',
            'destination_id' => 'required',
            'j_day' => 'required',
            'journey_dt' => 'required',
            'boarding_id' => 'required',
            'dropping_id' => 'required',
            'boarding_time' => 'required',
            'dropping_time' => 'required',
            'bus_info' => 'required',
            'customer_info' => 'required',
            'total_fare' => 'required',
            'ownr_fare' => 'required',
            'is_coupon' => 'required',
            'coupon_code' => 'required',
            'coupon_discount' => 'required',
            'discounted_fare' => 'required',
            'origin' => 'required',
            'app_type' => 'required',
            'typ_id' => 'required',
            'created_by' => 'required',
            
        ];
        
        $preBookingValidation = Validator::make($data, $BookingRules);


        if ($preBookingValidation->fails()) {
            $errors = $preBookingValidation->errors();
            return $errors->toJson();
          }

        $result = ['status' => 200];
         DB::beginTransaction();

        try {
            //$result['data'] = $this->bookingService->updatePost($data, $id);
            $result['data'] = $this->bookingRepository->saveBooking($data);
            DB::Commit();
        } catch (Exception $e) {
            DB::Rollback();

            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function deleteBooking($id) {
      $result = ['status' => 200];
      DB::beginTransaction();

      try {
          //$result['data'] = $this->bookingService->deleteById($id);
          $result['data'] = $this->bookingRepository->delete($id);
          DB::Commit();
              } catch (Exception $e) {
                DB::Rollback();
        $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }
      return response()->json($result, $result['status']);
    }

    public function getBooking($id) {
      //$bookings = $this->bookingService->getById($id);
      $bookings = $this->bookingRepository->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$bookings;
      return response($output, 200);
    }      
	     

}
