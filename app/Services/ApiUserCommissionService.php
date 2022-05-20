<?php

namespace App\Services;

use App\Repositories\ApiUserCommissionRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class ApiUserCommissionService
{
    protected $apiUserCommissionRepository;
    public function __construct(ApiUserCommissionRepository $apiUserCommissionRepository)
    {
        $this->apiUserCommissionRepository = $apiUserCommissionRepository;
    }
    /**
     * Delete Data by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById($id)
    {
        try {
            $busType = $this->apiUserCommissionRepository->delete($id);

        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $busType;

    }
    /**
     * Get all Data
     *
     * @return String
     */
    public function getAll($request)
    {
        return $this->apiUserCommissionRepository->getAll($request);
    }

   
    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->apiUserCommissionRepository->getById($id);
    }
    /**
     * Update  data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update($data, $id)
    {
        try {
            $busType = $this->apiUserCommissionRepository->update($data, $id);

        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $busType;
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
            $busType = $this->apiUserCommissionRepository->save($data);
        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $busType;
    }
    /**
     * Get all Data in Datatable Format.
     *
     * @return String
     */
    public function getAllApiUserCommissionData($request)
    {
        return $this->apiUserCommissionRepository->getAllApiUserCommissionData($request);
    }
    public function changeStatus($id)
    {
        try {
            $busType = $this->apiUserCommissionRepository->changeStatus($id);
        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.UNABLE_CHANGE_STATUS'));
        }
        return $busType;

    }

}