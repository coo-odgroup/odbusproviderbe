<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingDetail;
use Illuminate\Support\Facades\Validator;
use App\Services\BookingDetailService;
use Exception;
use InvalidArgumentException;

class BookingDetailController extends Controller
{
    protected $bookingDetailService;

    
    public function __construct(BookingDetailService $bookingDetailService)
    {
        $this->bookingDetailService = $bookingDetailService;
    }


    public function getAllBookingDetail() {

        $bookingDetails = $this->bookingDetailService->getAll();
        $output ['status']=1;
        $output ['message']='All Data Fetched Successfully';
        $output ['result']=$bookingDetails;
        return response($output, 200);
    }

    public function createBookingDetail(Request $request) {
        $data = $request->only([

            'booking_id','pnr','jrny_dt','j_day','bus_id','seat_no',
            'passenger_name','passenger_gender','passenger_age',
            'created_by'
          ]);
        
          $bookingDetailsRules = [
            'booking_id' => 'required',
            'pnr' => 'required',
            'jrny_dt' => 'required',
            'j_day' => 'required',
            'bus_id' => 'required',
            'passenger_name' => 'required',
            'passenger_age' => 'required',
            'created_by' => 'required',
            
             
        ];
        
        $bookingDetailValidation = Validator::make($data, $bookingDetailsRules);


        if ($bookingDetailValidation->fails()) {
            $errors = $bookingDetailValidation->errors();
            return $errors->toJson();
          }
      $result = ['status' => 200];

      try {
          $result['data'] = $this->bookingDetailService->savePostData($data);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }

      return response()->json($result, $result['status']);

    } 

    public function updateBookingDetail(Request $request, $id) {
        $data = $request->only([
            'booking_id','pnr','jrny_dt','j_day','bus_id','seat_no',
            'passenger_name','passenger_gender','passenger_age',
            'created_by'
            
        ]);print_r("hello");
        $bookingDetailsRules = [
            
            'booking_id' => 'required',
            'pnr' => 'required',
            'jrny_dt' => 'required',
            'j_day' => 'required',
            'bus_id' => 'required',
            'passenger_name' => 'required',
            'passenger_age' => 'required',
            'created_by' => 'required',
            
        ];
       // print_r($request);exit();
        
        $bookingDetailValidation = Validator::make($data, $bookingDetailsRules);


        if ($bookingDetailValidation->fails()) {
            $errors = $bookingDetailValidation->errors();
            return $errors->toJson();
          }

        $result = ['status' => 200];

        try {
            $result['data'] = $this->bookingDetailService->updatePost($data, $id);

        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function deleteBookingDetail($id) {
      $result = ['status' => 200];

      try {
          $result['data'] = $this->bookingDetailService->deleteById($id);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }
      return response()->json($result, $result['status']);
    }

    public function getBookingDetail($id) {
      $bookings = $this->bookingDetailService->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$bookings;
      return response($output, 200);
    }      
	     

}
