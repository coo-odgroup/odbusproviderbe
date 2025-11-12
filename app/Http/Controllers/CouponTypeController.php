<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CouponTypeService;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use InvalidArgumentException;
use App\AppValidator\CouponTypeValidator;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\CouponTypeRepository;


class CouponTypeController extends Controller
{
    use ApiResponser;
    protected $couponTypeService;
    protected $couponTypeValidator;
    protected $couponTypeRepository;
    /**
     * PostController Constructor
     *
     * @param couponTypeService $couponTypeService
     *
     */
    public function __construct(CouponTypeService $couponTypeService,CouponTypeRepository $couponTypeRepository, CouponTypeValidator $couponTypeValidator)
    {
        $this->couponTypeService = $couponTypeService;
        $this->couponTypeValidator = $couponTypeValidator;
        $this->couponTypeRepository = $couponTypeRepository;
    }

    public function getAllCouponType(Request $request)
    {
       
        $CouponType = $this->couponTypeRepository->getAll();
        return $this->successResponse($CouponType, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function CouponTypeData(Request $request)
    {
        
        $CouponType = $this->couponTypeRepository->CouponTypeData($request);
        return $this->successResponse($CouponType, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function createCouponType(Request $request)
    {
        $data = $request->only([
          'coupon_type_name','created_by'
        ]);

        $CouponTypeValidation = $this->couponTypeValidator->validate($data);

        if ($CouponTypeValidation->fails()) {
            $errors = $CouponTypeValidation->errors();
            // return $errors->toJson();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }
        try {
            $this->couponTypeRepository->save($data);
             return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        }
        return $this->successResponse($data, "Coupon Type Added", Response::HTTP_CREATED);

    }

    public function updateCouponType(Request $request, $id)
    {
        $data = $request->only([
          'coupon_type_name','created_by'
        ]);

        $CouponTypeValidation =   $this->couponTypeValidator->validate($data);

        if ($CouponTypeValidation->fails()) {
            $errors = $CouponTypeValidation->errors();
            // return $errors->toJson();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        try {
            $this->couponTypeRepository->update($data, $id);
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        }
        return $this->successResponse($data, "Coupon Type Updated", Response::HTTP_CREATED);
    }

    public function deleteCouponType($id)
    {
        try {
           $this->couponTypeRepository->delete($id);
           return $this->errorResponse($e->getMessage(), "404");
        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $this->successResponse(null, "Coupon Type Deleted", Response::HTTP_ACCEPTED);
    }

    public function getCouponType($id)
    {
        try {
          
            $CouponType = $this->couponTypeRepository->getById($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse($CouponType, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

   
    public function changeStatus($id)
{
    try {
        
        $this->couponTypeRepository->changeStatus($id);
    } catch (Exception $e) {
        return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
    }

    return $this->successResponse(null, "Coupon Type Status Updated", Response::HTTP_ACCEPTED);
}

}
