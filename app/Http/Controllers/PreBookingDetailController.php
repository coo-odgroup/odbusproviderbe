<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PreBookingDetail;
use Illuminate\Support\Facades\Validator;
use App\Services\PreBookingDetailService;
use Exception;
use InvalidArgumentException;

class PreBookingDetailController extends Controller
{
    protected $preBookingDetailService;

    
    public function __construct(PreBookingDetailService $preBookingDetailService)
    {
        $this->preBookingDetailService = $preBookingDetailService;
    }


    public function getAllPreBookingDetail() {

        $preBookingDetail = $this->preBookingDetailService->getAll();
        $output ['status']=1;
        $output ['message']='All Data Fetched Successfully';
        $output ['result']=$preBookingDetail;
        return response($output, 200);
    }

    public function createPreBookingDetail(Request $request) {
        $data = $request->only([

            'pre_booking_id','journey_date','j_day','bus_id','seat_name','created_by'
            
          ]);
        
          $preBookingDetailBusRules = [
            'pre_booking_id' => 'required',
            'journey_date' => 'required',
            'j_day' => 'required',
            'bus_id' => 'required',
            'seat_name' => 'required',
            'created_by' => 'required',
             
        ];
        
        $preBookingDetailValidation = Validator::make($data, $preBookingDetailBusRules);


        if ($preBookingDetailValidation->fails()) {
            $errors = $preBookingDetailValidation->errors();
            return $errors->toJson();
          }
      $result = ['status' => 200];

      try {
          $result['data'] = $this->preBookingDetailService->savePostData($data);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }

      return response()->json($result, $result['status']);

    } 

    public function updatePreBookingDetail(Request $request, $id) {
        $data = $request->only([
            'pre_booking_id','journey_date','j_day','bus_id','seat_name','created_by'
            
        ]);
        $preBookingDetailBusRules = [
            
            'pre_booking_id' => 'required',
            'journey_date' => 'required',
            'j_day' => 'required',
            'bus_id' => 'required',
            'seat_name' => 'required',
            'created_by' => 'required',
        ];
        
        $preBookingDetailValidation = Validator::make($data, $preBookingDetailBusRules);


        if ($preBookingDetailValidation->fails()) {
            $errors = $preBookingDetailValidation->errors();
            return $errors->toJson();
          }

        $result = ['status' => 200];

        try {
            $result['data'] = $this->preBookingDetailService->updatePost($data, $id);

        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function deletePreBookingDetail($id) {
      $result = ['status' => 200];

      try {
          $result['data'] = $this->preBookingDetailService->deleteById($id);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }
      return response()->json($result, $result['status']);
    }

    public function getPreBookingDetail($id) {
      $preBookingDetail = $this->preBookingDetailService->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$preBookingDetail;
      return response($output, 200);
    }      
	     
}
