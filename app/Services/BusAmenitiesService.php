<?php

namespace App\Services;

use App\Models\BusAmenities;
use App\Repositories\BusAmenitiesRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
//use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class BusAmenitiesService
{
    
    protected $busAmenitiesRepository;

    
    public function __construct(busAmenitiesRepository $busAmenitiesRepository)
    {
        $this->busAmenitiesRepository = $busAmenitiesRepository;
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
            $post = $this->busAmenitiesRepository->delete($id);

        } catch (Exception $e) {
            // Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $post;
    }
/**
     * Get all Data
     *
     * @return String
     */
    
    public function getAll()
    {
        return $this->busAmenitiesRepository->getAll();
    }
/**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    
    public function getById($id)
    {
        return $this->busAmenitiesRepository->getById($id);
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
            $post = $this->busAmenitiesRepository->update($data, $id);

        } catch (Exception $e) {
            // Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $post;

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
            $post =$this->busAmenitiesRepository->save($data);
        }
        catch (Exception $e) {
            // Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        }
        return $post;
    }
    /**
     * Get all Data in Datatable Format.
     *
     * @return String
     */
    
}