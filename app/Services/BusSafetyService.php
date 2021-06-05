<?php

namespace App\Services;

use App\Models\BusSafety;
use App\Repositories\BusSafetyRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class BusSafetyService
{
    /**
     * @var $busSafetyRepository
     */
    protected $busSafety;

    /**
     * BusSafetyRepository constructor.
     *
     * @param BusSafetyRepository $busSafetyRepository
     */
    public function __construct(BusSafetyRepository $busSafetyRepository)
    {
        $this->busSafetyRepository = $busSafetyRepository;
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
            $busSafety = $this->busSafetyRepository->delete($id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $busSafety;
    }

    public function changeStatus($data,$id)
    {
        try {
            $busSafety = $this->busSafetyRepository->changeStatus($data, $id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.UNABLE_CHANGE_STATUS'));
        }
        return $busSafety;
    }
    /**
     * Get all Data.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->busSafetyRepository->getAll();
    }
    
    public function dataTable($request)
    {
        return $this->busSafetyRepository->getDatatable($request);
    }
    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->busSafetyRepository->getById($id);
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
            $busSafety = $this->busSafetyRepository->update($data, $id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $busSafety;
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
            $busSafety = $this->busSafetyRepository->save($data);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        }
        return $busSafety;
    }

}