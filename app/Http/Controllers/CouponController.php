<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\Validator;
use App\Repositories\CouponRepository;
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
    protected $couponRepository;
    public function __construct(CouponService $couponService,
                                CouponRepository $couponRepository  )
    {
        $this->couponService = $couponService;
        $this->couponRepository = $couponRepository;
    }
    public function getAllCoupon() {

        //$coupon = $this->couponService->getAll();
        $coupon = $this->couponRepository->getAll();
        return $this->successResponse($coupon,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 

    public function getAllCouponType() {

        //$coupon = $this->couponService->getAllCouponType();
        $coupon = $this->couponRepository->getAllCouponType();
        return $this->successResponse($coupon,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function createCouponBus(Request $request)
    {
        $data = $request->only([
            'bus_id','coupon_id','created_by'
        ]);
        try {
            //$this->couponService->saveBusCouponData($data);
            $this->couponRepository->saveCouponBus($data);
        } 
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($data,"Coupon Added",Response::HTTP_CREATED);
    }
    public function createCouponRoute(Request $request)
    {
        $data = $request->only([
            'source_id','destination_id','coupon_id','created_by'
        ]);
        try {
            //$this->couponService->saveRouteCouponData($data);
            $this->couponRepository->saveCouponRoute($data);
        } 
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($data,"Coupon Added",Response::HTTP_CREATED);
    }
    public function createCouponOperator(Request $request)
    {
        $data = $request->only([
            'operator_id','coupon_id','created_by'
        ]);
        try {
           // $this->couponService->saveOperatorCouponData($data);
            $this->couponRepository->saveCouponOperator($data);
        } 
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($data,"Coupon Added",Response::HTTP_CREATED);
    }
    public function createCoupon(Request $request) {
        // Log::info($request);exit;
        $data = $request->only(['coupon_type',
                                'via',
                                'coupon_title',
                                'coupon_code',
                                'short_description',
                                'route',
                                'bus_id',
                                //'destination_id',
                                'full_description',                      
                                'coupon_discount_type',
                                'percentage',
                                'max_discount_price',
                                'amount',
                                'min_tran_amount',
                                'valid_by',
                                'from_date',
                                'to_date',
                                'bus_operator_id',
                                'max_redeem','auto_apply','apply_once','created_by','user_id']);
        
          $couponRules = [           
            'coupon_title' => 'required',
            'coupon_type' => 'required',
            'coupon_code' => 'required',
            'coupon_discount_type' => 'required',
            'valid_by' => 'required',
            'max_redeem' => 'required',
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
           //$res= $this->couponService->savePostData($data);
              $res= $this->couponRepository->save($data);

           if(isset($res['status']) && $res['status']=='exist'){

            return $this->errorResponse($res['message'],Response::HTTP_PARTIAL_CONTENT);


           }else{

            return $this->successResponse($res,"Coupon Added",Response::HTTP_CREATED);

           }
            
        } 
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
       
    } 

    public function updateCoupon(Request $request, $id) {

        // Log::info($request);
        // exit;

        // $data = $request->only(['coupon_type',
        //                         'coupon_title',
        //                         'coupon_code',
        //                         'short_description',
        //                         'route',
        //                         'bus_id',
        //                        // 'destination_id',
        //                         'full_description',                      
        //                         'coupon_discount_type',
        //                         'percentage',
        //                         'max_discount_price',
        //                         'amount',
        //                         'min_tran_amount',
        //                         'valid_by',
        //                         'from_date',
        //                         'to_date',
        //                         'bus_operator_id',
        //                         'max_redeem','created_by']);
        
        //   $couponRules = [           
        //     'coupon_title' => 'required',
        //     'coupon_type' => 'required',
        //     'coupon_code' => 'required',
        //     'coupon_discount_type' => 'required',
        //     'valid_by' => 'required',
        //     'max_redeem' => 'required',
        //     'from_date' => 'required',
        //     'to_date' => 'required',
        //     'created_by' => 'required',
        // ];
        // $couponValidation = Validator::make($data, $couponRules);
        // if ($couponValidation->fails()) {
        //     $errors = $couponValidation->errors();
        //     return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        // }
        try {
            //$data= $this->couponService->updatePost($request,$id);
            $data= $this->couponRepository->update($request,$id);
        } 
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($data,"Coupon Updated",Response::HTTP_CREATED);


    }

    public function deleteCoupon ($id) {
    // $coupon = $this->couponService->delete($id);
    $coupon = $this->couponRepository->delete($id);
        return $this->successResponse($coupon,"Coupon Deleted",Response::HTTP_OK);

    }

    public function getBusCoupon($id) {
     // $coupon = $this->couponService->getById($id);
        $coupon = $this->couponRepository->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$coupon;
      return response($output, 200);
    }    

    public function getData(Request $request)
    {
        //$coupon = $this->couponService->getData($request);
        $coupon = $this->couponRepository->getData($request);
        return $this->successResponse($coupon,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);

    }  

     public function changeStatus ($id) {
        try{
            //$response = $this->couponService->changeStatus($id);
            $response = $this->couponRepository->changeStatus($id);
        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($response,"Coupon Status Updated", Response::HTTP_ACCEPTED);
      }    
         
	     
}
