<?php

namespace App\Services;

use App\Models\BusOperator;
use App\Repositories\BusOperatorRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;

class BusOperatorService
{
    /**
     * @var $busOperatorRepository
     */
    protected $busOperatorRepository;

    /**
     * AmenitiesService constructor.
     *
     * @param BusOperatorRepository $busOperatorRepository
     */
    public function __construct(BusOperatorRepository $busOperatorRepository)
    {
        $this->busOperatorRepository = $busOperatorRepository;
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
            $busOperator = $this->busOperatorRepository->delete($id);

        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $busOperator;
    }

    public function getOperatorEmail($request)
    {
        try {
            $busOperator = $this->busOperatorRepository->getOperatorEmail($request);

        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $busOperator;
    }

    public function getOperatorPhone($request)
    {
        try {
            $busOperator = $this->busOperatorRepository->getOperatorPhone($request);

        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $busOperator;
    }
    /**
     * Get all Data.
    
     * @return String
     */
    public function getAll()
    {
        return $this->busOperatorRepository->getAll();
    }
    public function dataTable($request)
    {
        return $this->busOperatorRepository->getDatatable($request);
    }
    public function datafilter($request)
    {
        return $this->busOperatorRepository->filter($request);
    } 

    public function BusbyOperatorData($request)
    {
        return $this->busOperatorRepository->BusbyOperatorData($request);
    }
    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->busOperatorRepository->getById($id);
    }
    /**
     * Update  data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function updatePost($data, $id)
    {
        try {
            $busOperator = $this->busOperatorRepository->update($data, $id);
        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $busOperator;

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
            $busOperator = $this->busOperatorRepository->save($data);

        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $busOperator;
    }
    public function changeStatus($id)
    {
        try {
            $busOperator = $this->busOperatorRepository->changeStatus($id);
        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.UNABLE_CHANGE_STATUS'));
        }
        return $busOperator;
    }
    public function getBusbyOperator($id)
    {
        return $this->busOperatorRepository->getBusbyOperator($id);
    }
}