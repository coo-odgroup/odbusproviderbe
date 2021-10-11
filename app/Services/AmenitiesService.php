<?php

namespace App\Services;

use App\Models\Amenities;
use App\Repositories\AmenitiesRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class AmenitiesService
{
    /**
     * @var $amenitiesRepository
     */
    protected $amenitiesRepository;

    /**
     * AmenitiesService constructor.
     *
     * @param AmenitiesRepository $amenitiesRepository
     */
    public function __construct(AmenitiesRepository $amenitiesRepository)
    {
        $this->amenitiesRepository = $amenitiesRepository;
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
            $amenity = $this->amenitiesRepository->delete($id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $amenity;
    }

    public function changeStatus($data,$id)
    {
        try {
            $amenity = $this->amenitiesRepository->changeStatus($data, $id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.UNABLE_CHANGE_STATUS'));
        }
        return $amenity;
    }
    /**
     * Get all Data.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->amenitiesRepository->getAll();
    }
    
    public function dataTable($request)
    {
        return $this->amenitiesRepository->getDatatable($request);
    } 

    public function AmenitiesData($request)
    {
        return $this->amenitiesRepository->getAmenitiesData($request);
    }
    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->amenitiesRepository->getById($id);
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
            $amenity = $this->amenitiesRepository->update($data, $id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $amenity;
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
            $amenity = $this->amenitiesRepository->save($data);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        }
        return $amenity;
    }

}