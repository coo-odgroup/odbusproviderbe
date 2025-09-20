<?php

namespace App\Services;

use App\Models\BusStoppageTiming;
use App\Repositories\BusStoppageTimingRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class BusStoppageTimingService
{
    protected $busStoppageTimingRepository;
    public function __construct(busStoppageTimingRepository $busStoppageTimingRepository)
    {
        $this->busStoppageTimingRepository = $busStoppageTimingRepository;
    }
    public function deleteByStoppageId($id)
    {
        try {
            $post = $this->busStoppageTimingRepository->deleteByStoppageId($id);

        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $post;
    }
    public function deleteById($id)
    {
        try {
            $post = $this->busStoppageTimingRepository->delete($id);

        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $post;
    }

    
    public function getAll()
    {
        return $this->busStoppageTimingRepository->getAll();
    }
    public function getById($id)
    {
        return $this->busStoppageTimingRepository->getById($id);
    }
    public function busStoppageTimingbyBusId($busid)
    {
        return $this->busStoppageTimingRepository->busStoppageTimingbyBusId($busid);
    }
    public function busStoppageTimingbyBusIdClone($busid)
    {
        return $this->busStoppageTimingRepository->busStoppageTimingbyBusIdClone($busid);
    }
    // public function updatePost($data, $id)
    // {
    //     try {
    //         $post = $this->busStoppageTimingRepository->update($data, $id);

    //     } catch (Exception $e) {
    //         Log::info($e->getMessage());
    //         throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
    //     }
    //     return $post;

    // }


    // public function updateStatus($id)
    // {
    //      $result = $this->busStoppageTimingRepository->updateStatus($id);
    //     return $result;
    // }

   
//     public function savePostData($data)
//     {
//         $result = $this->busStoppageTimingRepository->save($data);
//         return $result;
//     }
 }