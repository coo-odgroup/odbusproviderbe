<?php

namespace App\Services;

use App\Models\AppVersion;
use App\Repositories\BoardingDropingRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
//use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class BoardingDropingService
{
    
    protected $boardingDropingRepository;

    
    public function __construct(boardingDropingRepository $boardingDropingRepository)
    {
        $this->boardingDropingRepository = $boardingDropingRepository;
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
            $boardingDropping = $this->boardingDropingRepository->delete($id);

        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $boardingDropping;

    }
    /**
     * Get all Data
     *
     * @return String
     */
    public function getAll()
    {
        return $this->boardingDropingRepository->getAll();
    }
    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->boardingDropingRepository->getById($id);
    }

    public function getByLocationId($id)
    {
        return $this->boardingDropingRepository->getByLocationId($id);
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
            $boardingDropping = $this->boardingDropingRepository->update($data, $id);

        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $boardingDropping;

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
            $boardingDropping = $this->boardingDropingRepository->save($data);
        } 
        catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        }
        return $boardingDropping;
    }
    /**
     * Get all Data in Datatable Format.
     *
     * @return String
     */
    public function getBoardingDropingDT($request)
    {
        return $this->boardingDropingRepository->getBoardingDropingDT($request);
    }

    public function boardingData($request)
    {
        return $this->boardingDropingRepository->boardingData($request);
    }

    public function createBoarding($request)
    {
        return $this->boardingDropingRepository->create($request);
    }

    public function changeStatus($id)
    {
        try {
            $boardingDropping = $this->boardingDropingRepository->changeStatus($id);

        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.UNABLE_CHANGE_STATUS'));
        }
        return $boardingDropping;

    }

}