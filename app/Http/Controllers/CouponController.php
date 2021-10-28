<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\Validator;
use App\Services\CouponService;
use Exception;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CouponController extends Controller
{
    use ApiResponser;
    protected $couponService;
    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }
    public function getAllCoupon() {

        $coupon = $this->couponService->getAll();
        return $this->successResponse($coupon,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function createCouponBus(Request $request)
    {
        $data = $request->only([
            'bus_id','coupon_id','created_by'
        ]);
        try {
            $this->couponService->saveBusCouponData($data);
        } 
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($data,Config::get('constants.RECORD_ADDED'),Response::HTTP_CREATED);
    }
    public function createCouponRoute(Request $request)
    {
        $data = $request->only([
            'source_id','destination_id','coupon_id','created_by'
        ]);
        try {
            $this->couponService->saveRouteCouponData($data);
        } 
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($data,Config::get('constants.RECORD_ADDED'),Response::HTTP_CREATED);
    }
    public function createCouponOperator(Request $request)
    {
        $data = $request->only([
            'operator_id','coupon_id','created_by'
        ]);
        try {
            $this->couponService->saveOperatorCouponData($data);
        } 
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($data,Config::get('constants.RECORD_ADDED'),Response::HTTP_CREATED);
    }
    public function createCoupon(Request $request) {
        // Log::info($request);exit;
        $data = $request->only([

                             'coupon_title','coupon_code','type','amount', 
                            'max_discount_price','min_tran_amount','max_redeem',
                            'from_date','to_date','short_desc','full_desc',
                            'created_by','bus_operator_id','valid_by','percentage'
            
          ]);
        
          $couponRules = [
           
            'coupon_title' => 'required',
            'bus_operator_id' => 'required',
            'coupon_code' => 'required',
            'type' => 'required',
            'valid_by' => 'required',
            'max_redeem' => 'required',
            // 'category' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
            'created_by' => 'required',
        ];
        
        $couponValidation = Validator::make($data, $couponRules);
        if ($couponValidation->fails()) {
            $errors = $couponValidation->errors();
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
            $this->couponService->savePostData($data);
        } 
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($data,Config::get('constants.RECORD_ADDED'),Response::HTTP_CREATED);
    } 

    public function updateCoupon(Request $request, $id) {

        // Log::info($request);
        // exit;

        $data = $request->only([
                            'id',
                             'coupon_title','coupon_code','type','amount', 
                            'max_discount_price','min_tran_amount','max_redeem',
                           'from_date','to_date','short_desc','full_desc',
                            'created_by','bus_operator_id','valid_by','percentage'
        ]);
        $couponRules = [
            
            'coupon_title' => 'required',
            'coupon_code' => 'required',
            'type' => 'required',
            'valid_by' => 'required',
            'bus_operator_id' => 'required',
            // 'amount' => 'required',
            // 'max_discount_price' => 'required',
            // 'min_tran_amount' => 'required',
            'max_redeem' => 'required',
            // 'category' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
            'created_by' => 'required',
        ];
        $couponValidation = Validator::make($data, $couponRules);
        if ($couponValidation->fails()) {
            $errors = $couponValidation->errors();
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
            $this->couponService->updatePost($data,$id);
        } 
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($data,Config::get('constants.RECORD_UPDATED'),Response::HTTP_CREATED);


    }

    public function deleteCoupon ($id) {
     $coupon = $this->couponService->delete($id);
        return $this->successResponse($coupon,Config::get('constants.RECORD_REMOVED'),Response::HTTP_OK);

    }

    public function getBusCoupon($id) {
      $coupon = $this->couponService->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$coupon;
      return response($output, 200);
    }    

    public function getData(Request $request)
    {
        $coupon = $this->couponService->getData($request);
        return $this->successResponse($coupon,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);

    }  
	     
}
