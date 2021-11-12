<?php

namespace App\Services;

use App\Models\BusSeats;
use App\Repositories\BusSeatsRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
//use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class BusSeatsService
{
    protected $busSeatsRepository;
    public function __construct(busSeatsRepository $busSeatsRepository)
    {
        $this->busSeatsRepository = $busSeatsRepository;
    }
    public function deleteById($id)
    {
        try {
            $post = $this->busSeatsRepository->delete($id);
        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $post;

    }
    public function getAll()
    {
        return $this->busSeatsRepository->getAll();
    }
    public function getAllFare($busId)
    {
        return $this->busSeatsRepository->getAllFare($busId);
    }
    
    public function getById($id)
    {
        return $this->busSeatsRepository->getById($id);
    }
    public function getByBusId($id)
    {
        return $this->busSeatsRepository->getByBusId($id);
    }
    
    public function updatePost($data, $id)
    {
        try {
            $post = $this->busSeatsRepository->update($data, $id);

        } catch (Exception $e) {
            Log::info($e);
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $post;

    }
    public function updateNewFare($data)
    {
        try {
            $post = $this->busSeatsRepository->updateNewFare($data);

        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $data;
    }
    public function busSeatsExtra($data, $id)
    {
        try {
            $post = $this->busSeatsRepository->updateBusSeatsExtra($data, $id);

        } catch (Exception $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
        return $data;

    }
    
    public function savePostData($data)
    {
        $result = $this->busSeatsRepository->save($data);
        return $result;
    }

}