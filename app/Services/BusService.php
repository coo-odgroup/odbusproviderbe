<?php

namespace App\Services;

use App\Models\Bus;
use App\Repositories\BusRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
class BusService
{
    protected $busRepository;    
    public function __construct(BusRepository $busRepository)
    {
        $this->busRepository = $busRepository; 
    }
    public function deleteById($id)
    {
        try {
            $post = $this->busRepository->delete($id);
        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $post;
    }
    public function getAll()
    {
        return $this->busRepository->getAll();
    }
    public function getByOperaor($id)
    {
        return $this->busRepository->getByOperaor($id);
    }

    public function getLocationBus($source_id,$destination_id)
    {
        return $this->busRepository->getLocationBus($source_id,$destination_id);
    }

    public function getById($id)
    {
        return $this->busRepository->getById($id);
    }
    public function updateSequncePost($data, $id)
    {
        try {
            $post = $this->busRepository->updatesequence($data, $id);
        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $post;
    }
    public function updatePost($data, $id)
    {
        try {
            $post = $this->busRepository->update($data, $id);

        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $post;
    }
    public function savePostData($data)
    {
        $result = $this->busRepository->save($data);
        return $result;
    }
    public function saveRoute($data)
    {
        $result = $this->busRepository->saveRoute($data);
        return result;
    }
    public function getAllBusDT( $request)
    {
        return $this->busRepository->getAllBusDT($request);
    }
    public function changeStatus($id)
    {
        try {
            $post = $this->busRepository->changeStatus($id);

        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.UNABLE_CHANGE_STATUS'));
        }
        return $post;
    }
    public function getBusbyBuschedule($id)
    {
        return $this->busRepository->getBusbyBuschedule($id);
    }
    public function getBusScheduleEntryDates($busId)
    {
        return $this->busRepository->getBusScheduleEntryDates($busId);
    }
    public function getBusScheduleEntryDatesFilter($data)
    {
        return $this->busRepository->getBusScheduleEntryDatesFilter($data);
    }
}