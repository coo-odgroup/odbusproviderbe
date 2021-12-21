<?php

namespace App\Services;

use App\Models\BusType;
use App\Repositories\BusTypeRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class BusTypeService
{
    protected $busTypeRepository;
    public function __construct(BusTypeRepository $busTypeRepository)
    {
        $this->busTypeRepository = $busTypeRepository;
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
            $busType = $this->busTypeRepository->delete($id);

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
        return $this->busTypeRepository->getAll($request);
    }

    public function getAllBusTypeData($request)
    {
        return $this->busTypeRepository->getAllBusTypeData($request);
    }
    public function BusTypebyUser($request)
    {
        return $this->busTypeRepository->BusTypebyUser($request);
    }
    public function getBusTypeOperator($request)
    {
        return $this->busTypeRepository->getBusTypeOperator($request);
    }


    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->busTypeRepository->getById($id);
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
            $busType = $this->busTypeRepository->update($data, $id);

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
            $busType = $this->busTypeRepository->save($data);
        } catch (Exception $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
        return $busType;
    }
    /**
     * Get all Data in Datatable Format.
     *
     * @return String
     */
    public function getAllBusTypeDT($request)
    {
        return $this->busTypeRepository->getAllBusTypeDT($request);
    }
    public function changeStatus($id)
    {
        try {
            $busType = $this->busTypeRepository->changeStatus($id);
        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.UNABLE_CHANGE_STATUS'));
        }
        return $busType;

    }

}