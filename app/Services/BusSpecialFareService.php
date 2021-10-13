<?php

namespace App\Services;

use App\Models\BusSpecialFare;
use App\Repositories\BusSpecialFareRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class BusSpecialFareService
{
    
    protected $busSpecialFareRepository;
 
    public function __construct(BusSpecialFareRepository $busSpecialFareRepository)
    {
        $this->busSpecialFareRepository = $busSpecialFareRepository;
    }
    public function getPivotData($id)
    {
        return $this->busSpecialFareRepository->getPivotData($id);
    }
    /**
     * Delete Data by ID.
     *
     * @return String
     */
    public function deleteById($id)
    {
        try {
            $specialfare = $this->busSpecialFareRepository->delete($id);

        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $specialfare;

    }
    /**
     * Get all Data.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->busSpecialFareRepository->getAll();
    }
    /**
    * Get all Data in Datatable Format.
    *
    * @return String
    */
    public function dataTable($request)
    {
        return $this->busSpecialFareRepository->getDatatable($request);
    }

    public function busSpecialFareData($request)
    {
        return $this->busSpecialFareRepository->busSpecialFareData($request);
    }
    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->busSpecialFareRepository->getById($id);
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
            $specialfare = $this->busSpecialFareRepository->update($data, $id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $specialfare;

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
            $specialfare = $this->busSpecialFareRepository->save($data);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        }
        return $specialfare;
    }
    public function changeStatus($id)
    {
        try {
            $specialfare = $this->busSpecialFareRepository->changeStatus($id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException('Unable to change status');
        }
        return $specialfare;

    }
   


}