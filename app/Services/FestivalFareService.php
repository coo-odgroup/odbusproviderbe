<?php

namespace App\Services;

use App\Models\BusOwnerFare;
use App\Repositories\FestivalFareRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class FestivalFareService
{
    
    protected $festivalFareRepository;



    
    public function __construct(FestivalFareRepository $festivalFareRepository)
    {
        $this->festivalFareRepository = $festivalFareRepository;
    }

    /**
     * Delete Data by ID.
     *
     * @return String
     */
    public function deleteById($id)
    {
        try {
            $post = $this->festivalFareRepository->delete($id);

        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $post;

    }
    /**
     * Get all Data.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->festivalFareRepository->getAll();
    }
    /**
    * Get all Data in Datatable Format.
    *
    * @return String
    */
    public function dataTable($request)
    {
        
        return $this->festivalFareRepository->getDatatable($request);
    }
    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->festivalFareRepository->getById($id);
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
            $post = $this->festivalFareRepository->update($data, $id);
        } catch (Exception $e) {
            Log::info($e->getMessage());

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
            $post = $this->festivalFareRepository->save($data);

        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        }
        return $post;
    }
    public function changeStatus($id)
    {
        try {
            $post = $this->festivalFareRepository->changeStatus($id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to change status');
        }
        return $post;

    }


}