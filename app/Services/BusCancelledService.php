<?php

namespace App\Services;

use App\Models\BusCancelled;
use App\Repositories\BusCancelledRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class BusCancelledService
{
    
    protected $busCancelledRepository;
    public function __construct(BusCancelledRepository $busCancelledRepository)
    {
        $this->busCancelledRepository = $busCancelledRepository;
    }

    /**
     * Delete Data by ID.
     *
     * @return String
     */
    public function deleteById($id)
    {
        try {
            $busCancel = $this->busCancelledRepository->delete($id);

        } catch (Exception $e) {
            // Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $busCancel;

    }
    /**
     * Get all Data.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->busCancelledRepository->getAll();
    }

    public function removeOldBusCancelledCronjob()
    {
        return $this->busCancelledRepository->removeOldBusCancelledCronjob();
    }
    /**
    * Get all Data in Datatable Format.
    *
    * @return String
    */
    public function getBusCancelledDT($request)
    {
        return $this->busCancelledRepository->getBusCancelledDT($request);
    }

    public function busCancelledData($request)
    {
        return $this->busCancelledRepository->busCancelledData($request);
    }
    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    public function getByBusId($id)
    {
        return $this->busCancelledRepository->getByBusId($id);
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
            $busCancel = $this->busCancelledRepository->update($data, $id);

        } catch (Exception $e) {
            // Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $busCancel;

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
            $busCancel = $this->busCancelledRepository->save($data);

        } catch (Exception $e) {
            // Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        }
        return $busCancel;
    }

    public function busCancelledbyowner($data)
    {
        try {
            $busCancel = $this->busCancelledRepository->busCancelledbyowner($data);

        } catch (Exception $e) {
            // Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        }
        return $busCancel;
    }
    
    public function changeStatus($id)
    {
        try {
            $busCancel = $this->busCancelledRepository->changeStatus($id);

        } catch (Exception $e) {
            // Log::info($e->getMessage());
            throw new InvalidArgumentException('Unable to change status');
        }
        return $busCancel;
    }
    public function getBusWithOperator($id)
    {
        return $this->busCancelledRepository->getBusWithOperator($id);
    }


}