<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CouponRepository;
use Exception;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    use ApiResponser;

    protected $couponRepository;

    public function __construct(CouponRepository $couponRepository)
    {
        $this->couponRepository = $couponRepository;
    }

    public function getAllCoupon()
    {
        $coupon = $this->couponRepository->getAll();
        return $this->successResponse($coupon, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getAllCouponType()
    {
        $coupon = $this->couponRepository->getAllCouponType();
        return $this->successResponse($coupon, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function createCouponBus(Request $request)
    {
        $data = $request->only(['bus_id','coupon_id','created_by']);
        try {
            $this->couponRepository->saveCouponBus($data);
            return $this->successResponse($data, "Coupon Added", Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function createCouponRoute(Request $request)
    {
        $data = $request->only(['source_id','destination_id','coupon_id','created_by']);
        try {
            $this->couponRepository->saveCouponRoute($data);
            return $this->successResponse($data, "Coupon Added", Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function createCouponOperator(Request $request)
    {
        $data = $request->only(['operator_id','coupon_id','created_by']);
        try {
            $this->couponRepository->saveCouponOperator($data);
            return $this->successResponse($data, "Coupon Added", Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function createCoupon(Request $request)
    {
        $data = $request->only([
            'coupon_type','via','all_route_check','user_type','coupon_title','coupon_code','short_description','route','bus_id',
            'full_description','coupon_discount_type','percentage','max_discount_price','amount',
            'min_tran_amount','valid_by','from_date','to_date','bus_operator_id','max_redeem',
            'auto_apply','apply_once','created_by','user_id'
        ]);

        $rules = [
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

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        try {
            $res = $this->couponRepository->save($data);
            if (isset($res['status']) && $res['status'] === 'exist') {
                return $this->errorResponse($res['message'], Response::HTTP_PARTIAL_CONTENT);
            }
            return $this->successResponse($res, "Coupon Added", Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function updateCoupon(Request $request, $id)
    {
        try {
            $data = $this->couponRepository->update($request, $id);
            return $this->successResponse($data, "Coupon Updated", Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function deleteCoupon($id)
    {
        $coupon = $this->couponRepository->delete($id);
        return $this->successResponse($coupon, "Coupon Deleted", Response::HTTP_OK);
    }

    public function getBusCoupon($id)
    {
        $coupon = $this->couponRepository->getById($id);
        return response([
            'status' => 1,
            'message' => 'Single Data Fetched Successfully',
            'result' => $coupon
        ], 200);
    }

    public function getData(Request $request)
    {
        $coupon = $this->couponRepository->getData($request);
        return $this->successResponse($coupon, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function changeStatus($id)
    {
        try {
            $response = $this->couponRepository->changeStatus($id);
            return $this->successResponse($response, "Coupon Status Updated", Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }
}
