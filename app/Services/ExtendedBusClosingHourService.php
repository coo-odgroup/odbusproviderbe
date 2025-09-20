<?php

namespace App\Services;

use App\Models\ExtendedBusClosingHours;
use App\Repositories\ExtendedBusClosingHourRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class ExtendedBusClosingHourService
{
    
    protected $extendedbusClosingHourRepository;

    
    public function __construct(ExtendedBusClosingHourRepository $extendedbusClosingHourRepository)
    {
        $this->extendedbusClosingHourRepository = $extendedbusClosingHourRepository; 
    }
    // public function deleteById($id)
    // {
    //     try {
    //         $post = $this->extendedbusClosingHourRepository->delete($id);

    //     } catch (Exception $e) {
    //         Log::info($e->getMessage());

    //         throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
    //     }
    //     return $post;

    // }
    /**
     * Get all Data With Data Table.
     *
     * @return String
     */
    // public function dataTable($request)
    // {
    //     return $this->extendedbusClosingHourRepository->getDatatable($request);
    // }
    /**
     * Get all Data.
     *
     * @return String
     */
    // public function getAll($request)
    // {
    //     return $this->extendedbusClosingHourRepository->getAll($request);
    // }
    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    // public function getById($id)
    // {
    //     return $this->extendedbusClosingHourRepository->getById($id);
    // }
    /**
     * Update  data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    // public function updatePost($data, $id)
    // {
    //     try {
    //         $post = $this->extendedbusClosingHourRepository->update($data, $id);

    //     } catch (Exception $e) {
    //         Log::info($e->getMessage());

    //         throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
    //     }
    //     return $post;
    // }

    /**
     * Validate  data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
        // public function savePostData($data)
        // {
        //     try {
        //         $post = $this->extendedbusClosingHourRepository->save($data);

        //     } catch (Exception $e) {
        //         Log::info($e->getMessage());

        //         throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        //     }
        //     return $post;
        // }
   

}