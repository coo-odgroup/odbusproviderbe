<?php

namespace App\Services;

use App\Models\BusClosingHours;
use App\Repositories\BusClosingHourRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class BusClosingHourService
{
    
    protected $busClosingHourRepository;

    
    public function __construct(BusClosingHourRepository $busClosingHourRepository)
    {
        $this->busClosingHourRepository = $busClosingHourRepository; 
    }
    public function deleteById($id)
    {
        try {
            $post = $this->busClosingHourRepository->delete($id);

        } catch (Exception $e) {
            Log::info($e->getMessage());

            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $post;

    }
    /**
     * Get all Data With Data Table.
     *
     * @return String
     */
    public function dataTable($request)
    {
        return $this->busClosingHourRepository->getDatatable($request);
    }
    /**
     * Get all Data.
     *
     * @return String
     */
    public function getAll($request)
    {
        return $this->busClosingHourRepository->getAll($request);
    }
    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->busClosingHourRepository->getById($id);
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
            $post = $this->busClosingHourRepository->update($data, $id);

        } catch (Exception $e) {
            Log::info($e->getMessage());

            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
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
            $post = $this->busClosingHourRepository->save($data);

        } catch (Exception $e) {
            Log::info($e->getMessage());

            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        }
        return $post;
    }
   

}