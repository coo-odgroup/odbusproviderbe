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

    
    public function deleteById($id)
    {
        DB::beginTransaction();

        try {
            $post = $this->couponRepository->delete($id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete post data');
        }

        DB::commit();

        return $post;

    }

    
    public function getAll()
    {
        return $this->couponRepository->getAll();
    }

    
    public function getById($id)
    {
        return $this->couponRepository->getById($id);
    }

   
    public function updatePost($data, $id)
    {
        

        DB::beginTransaction();

        try {
            $post = $this->couponRepository->update($data, $id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update post data');
        }

        DB::commit();

        return $post;

    }

    
    public function savePostData($data)
    {
        

        $result = $this->couponRepository->save($data);

        return $result;
    }

}