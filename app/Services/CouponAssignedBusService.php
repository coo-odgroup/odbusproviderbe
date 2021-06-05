<?php

namespace App\Services;

use App\Models\CouponAssignedBus;
use App\Repositories\CouponAssignedBusRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class CouponAssignedBusService
{
    
    protected $couponAssignedBusRepository;

    
    public function __construct(couponAssignedBusRepository $couponAssignedBusRepository)
    {
        $this->couponAssignedBusRepository = $couponAssignedBusRepository;
    }

    
    public function deleteById($id)
    {
        DB::beginTransaction();

        try {
            $post = $this->couponAssignedBusRepository->delete($id);

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
        return $this->couponAssignedBusRepository->getAll();
    }

    
    public function getById($id)
    {
        return $this->couponAssignedBusRepository->getById($id);
    }

   
    public function updatePost($data, $id)
    {
        

        DB::beginTransaction();

        try {
            $post = $this->couponAssignedBusRepository->update($data, $id);

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
        

        $result = $this->couponAssignedBusRepository->save($data);

        return $result;
    }

}