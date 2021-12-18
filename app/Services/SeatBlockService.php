<?php

namespace App\Services;


use App\Repositories\SeatBlockRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class SeatBlockService
{
    protected $seatblockRepository;

    public function __construct(SeatBlockRepository $seatblockRepository)
    {
        $this->seatblockRepository = $seatblockRepository;
    }

   
    public function deleteById($request)
    {
        try {
            $seatblock = $this->seatblockRepository->delete($request);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $seatblock;
    }

    public function changeStatus($id)
    {
        try {
            $post = $this->seatblockRepository->changeStatus($id);

        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.UNABLE_CHANGE_STATUS'));
        }
        return $post;
    }
    
    public function getAll()
    {
        return $this->seatblockRepository->getAll();
    }

    public function addseatblock($request)
    {
        return $this->seatblockRepository->addseatblock($request);
    } 
    public function updateseatblock($request, $id)
    {
        try {
            $seatblock = $this->seatblockRepository->updateseatblock($request, $id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $seatblock;
    }
    
    public function getseatblockDT($request)
    {
        return $this->seatblockRepository->getseatblockDT($request);
    }
   
    public function seatblockData($request)
    {
        return $this->seatblockRepository->seatblockData($request);
    }
   
    
    public function getById($id)
    {
        return $this->seatblockRepository->getById($id);
    }
    
    public function updatePost($data, $id)
    {
        try {
            $amenity = $this->seatblockRepository->update($data, $id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $amenity;
    }

   
    public function savePostData($data)
    {   
        try {
            $amenity = $this->seatblockRepository->save($data);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        }
        return $amenity;
    }

}