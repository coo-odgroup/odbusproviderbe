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
        //$CouponType = $this->couponTypeService->getAll($request);
        $CouponType = $this->couponTypeRepository->getAll();
        return $this->successResponse($CouponType, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function CouponTypeData(Request $request)
    {
        //$CouponType = $this->couponTypeService->CouponTypeData($request);
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
            // $this->couponTypeService->savePostData($data);
            $this->couponTypeRepository->save($data);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($data, "Coupon Type Added", Response::HTTP_CREATED);

    }

    public function updateCouponType(Request $request, $id)
{
    $data = $request->only([
        'coupon_type_name', 'created_by'
    ]);

    $CouponTypeValidation = $this->couponTypeValidator->validate($data);

    if ($CouponTypeValidation->fails()) {
        $errors = $CouponTypeValidation->errors();
        return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
    }

    try {
        $this->couponTypeRepository->update($data, $id);
        return $this->successResponse($data, "Coupon Type Updated", Response::HTTP_CREATED);
    } catch (Exception $e) {
        return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}


    public function deleteCouponType($id)
    {
        try {
            //$this->couponTypeService->deleteById($id);
            $this->couponTypeRepository->delete($id);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), "404");
        }
        return $this->successResponse(null, "Coupon Type Deleted", Response::HTTP_ACCEPTED);
    }

    public function getCouponType($id)
    {
        try {
            //$CouponType = $this->couponTypeService->getById($id);
            $CouponType = $this->couponTypeRepository->getById($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse($CouponType, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getCouponTypeDT(Request $request)
    {
        $CouponType = $this->couponTypeService->getAllCouponTypeDT($request);
        return $this->successResponse($CouponType, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    } //Present in controller but not in service or Repository.

    public function changeStatus($id)
    {
        try {
            //$this->couponTypeService->changeStatus($id);
            $this->couponTypeRepository->changeStatus($id);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, "Coupon Type Status Updated", Response::HTTP_ACCEPTED);
    }
}
