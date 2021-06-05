<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\Validator;
use App\Services\CouponService;
use Exception;
use InvalidArgumentException;

class CouponController extends Controller
{
    protected $couponService;

    
    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }


    public function getAllCoupon() {

        $coupon = $this->couponService->getAll();
        $output ['status']=1;
        $output ['message']='All Data Fetched Successfully';
        $output ['result']=$coupon;
        return response($output, 200);
    }

    public function createCoupon(Request $request) {
        $data = $request->only([

                             'coupon_title','coupon_code','type','amount', 
                            'max_discount_price','min_tran_amount','max_redeem',
                            'max_use_limit','category','from_date','to_date','short_desc','full_desc',
                            'created_by'
            
          ]);
        
          $couponRules = [
           
            'coupon_title' => 'required',
            'coupon_code' => 'required',
            'type' => 'required',
            'amount' => 'required',
            'max_discount_price' => 'required',
            'min_tran_amount' => 'required',
            'max_redeem' => 'required',
            'max_use_limit' => 'required',
            'category' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
            'short_desc' => 'required',
            'full_desc' => 'required',
            'created_by' => 'required',
        ];
        
        $couponValidation = Validator::make($data, $couponRules);


        if ($couponValidation->fails()) {
            $errors = $couponValidation->errors();
            return $errors->toJson();
          }
      $result = ['status' => 200];

      try {
          $result['data'] = $this->couponService->savePostData($data);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }

      return response()->json($result, $result['status']);

    } 

    public function updateCoupon(Request $request, $id) {
        $data = $request->only([
                             'coupon_title','coupon_code','type','amount', 
                            'max_discount_price','min_tran_amount','max_redeem',
                            'max_use_limit','category','from_date','to_date','short_desc','full_desc',
                            'created_by'
        ]);
        $couponRules = [
            
            'coupon_title' => 'required',
            'coupon_code' => 'required',
            'type' => 'required',
            'amount' => 'required',
            'max_discount_price' => 'required',
            'min_tran_amount' => 'required',
            'max_redeem' => 'required',
            'max_use_limit' => 'required',
            'category' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
            'short_desc' => 'required',
            'full_desc' => 'required',
            'created_by' => 'required',
        ];
        
        $couponValidation = Validator::make($data, $couponRules);


        if ($couponValidation->fails()) {
            $errors = $couponValidation->errors();
            return $errors->toJson();
          }

        $result = ['status' => 200];

        try {
            $result['data'] = $this->couponService->updatePost($data, $id);

        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function deleteCoupon ($id) {
      $result = ['status' => 200];

      try {
          $result['data'] = $this->couponService->deleteById($id);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }
      return response()->json($result, $result['status']);
    }

    public function getBusCoupon($id) {
      $coupon = $this->couponService->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$coupon;
      return response($output, 200);
    }      
	     
}
