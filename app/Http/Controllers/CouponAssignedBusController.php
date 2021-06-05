<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CouponAssignedBus;
use Illuminate\Support\Facades\Validator;
use App\Services\CouponAssignedBusService;
use Exception;
use InvalidArgumentException;

class CouponAssignedBusController extends Controller
{
    protected $couponAssignedBusService;

    
    public function __construct(CouponAssignedBusService $couponAssignedBusService)
    {
        $this->couponAssignedBusService = $couponAssignedBusService;
    }


    public function getAllCouponAssignedBus() {

        $couponassignedBus = $this->couponAssignedBusService->getAll();
        $output ['status']=1;
        $output ['message']='All Data Fetched Successfully';
        $output ['result']=$couponassignedBus;
        return response($output, 200);
    }

    public function createCouponAssignedBus(Request $request) {
        $data = $request->only([

            'bus_id', 'coupon_id','created_by'
            
          ]);
        
          $couponAssignedBusRules = [
            'bus_id' => 'required',
            'coupon_id' => 'required',
            'created_by' => 'required',
             
        ];
        
        $couponAssignedBusValidation = Validator::make($data, $couponAssignedBusRules);


        if ($couponAssignedBusValidation->fails()) {
            $errors = $couponAssignedBusValidation->errors();
            return $errors->toJson();
          }
      $result = ['status' => 200];

      try {
          $result['data'] = $this->couponAssignedBusService->savePostData($data);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }

      return response()->json($result, $result['status']);

    } 

    public function updateCouponAssignedBus(Request $request, $id) {
        $data = $request->only([
            'bus_id','coupon_id','created_by'
            
        ]);
        $couponAssignedBusRules = [
            
            'bus_id' => 'required',
            'coupon_id' => 'required',
            'created_by' => 'required',
            
        ];
        
        $couponAssignedBusValidation = Validator::make($data, $couponAssignedBusRules);


        if ($couponAssignedBusValidation->fails()) {
            $errors = $couponAssignedBusValidation->errors();
            return $errors->toJson();
          }

        $result = ['status' => 200];

        try {
            $result['data'] = $this->couponAssignedBusService->updatePost($data, $id);

        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function deleteCouponAssignedBus($id) {
      $result = ['status' => 200];

      try {
          $result['data'] = $this->couponAssignedBusService->deleteById($id);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }
      return response()->json($result, $result['status']);
    }

    public function getCouponAssignedBus($id) {
      $couponassignedBus = $this->couponAssignedBusService->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$couponassignedBus;
      return response($output, 200);
    }      
	     

}
