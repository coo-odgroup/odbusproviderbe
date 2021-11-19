<?php

namespace App\Services;

use App\Models\Safety;
use App\Repositories\SafetyRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class SafetyService
{
    /**
     * @var $safetyRepository
     */
    protected $safetyRepository;

    /**
     * SafetyRepository constructor.
     *
     * @param SafetyRepository $safetyRepository
     */
    public function __construct(SafetyRepository $safetyRepository)
    {
        $this->safetyRepository = $safetyRepository;
    }

    /**
     * Delete  by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById($id)
    {
        try {
            $safety = $this->safetyRepository->delete($id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $safety;
    }

    public function changeStatus($data,$id)
    {
        try {
            $safety = $this->safetyRepository->changeStatus($data, $id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.UNABLE_CHANGE_STATUS'));
        }
        return $safety;
    }
    /**
     * Get all Data.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->safetyRepository->getAll();
    }
    
    public function dataTable($request)
    {
        return $this->safetyRepository->getDatatable($request);
    }

    public function getAllData($request)
    {
        return $this->safetyRepository->getAllData($request);
    }
    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->safetyRepository->getById($id);
    }
    public function getByBusId($id)
    {
        return $this->safetyRepository->getByBusId($id);
    }
    /**
     * Update  data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function updatePost($data)
    {
        try {
            $safety = $this->safetyRepository->update($data);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $safety;
    }

    /**
     * Validate  data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function savePostData($data)
    {   
        try {
            $safety = $this->safetyRepository->save($data);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        }
        return $safety;
    }

}