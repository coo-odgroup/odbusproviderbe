<?php

namespace App\Services;

use App\Models\Coupon;
use App\Repositories\CouponRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class CouponService
{
    protected $couponRepository;
    public function __construct(couponRepository $couponRepository)
    {
        $this->couponRepository = $couponRepository;
    }
    // public function delete($id)
    // { return $this->couponRepository->delete($id);
    // }
    // public function getAll()
    // {
    //     return $this->couponRepository->getAll();
    // } 

    // public function getAllCouponType()
    // {
    //     return $this->couponRepository->getAllCouponType();
    // }
    
    //  public function getData($request)
    // {
    //     return $this->couponRepository->getData($request);
    // }
    // public function getById($id)
    // {
    //     return $this->couponRepository->getById($id);
    // }
    // public function updatePost($data, $id)
    // {
    //     try {
    //         $post = $this->couponRepository->update($data, $id);

    //     } catch (Exception $e) {
    //         throw new InvalidArgumentException($e->getMessage());
    //     }
    //     return $post;
    // }
    // public function savePostData($data)
    // {
    //     $result = $this->couponRepository->save($data);
    //     return $result;
    // }

    //COUPON ADD FOR BUS, ROUTE AND OPERATOR STARTS
    // public function saveBusCouponData($data)
    // {
    //     $result = $this->couponRepository->saveCouponBus($data);
    //     return $result;
    // }
    // public function saveRouteCouponData($data)
    // {
    //     $result = $this->couponRepository->saveCouponRoute($data);
    //     return $result;
    // }
    // public function saveOperatorCouponData($data)
    // {
    //     $result = $this->couponRepository->saveCouponOperator($data);
    //     return $result;
    // }
    // public function changeStatus($data)
    // {
    //     $result = $this->couponRepository->changeStatus($data);
    //     return $result;
    // }
    //COUPON ADD FOR BUS, ROUTE AND OPERATOR ENDS HERE
    
}