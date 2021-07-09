<?php

namespace App\Services;

use App\Models\BusSitting;
use App\Repositories\BusSittingRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator; 
use InvalidArgumentException;

class BusSittingService
{
    
    protected $busSittingRepository;

    /**
     * BusSittingService constructor.
     *
     * @param BusSittingRepository $busSittingRepository
     */
    public function __construct(BusSittingRepository $busSittingRepository)
    {
        $this->busSittingRepository = $busSittingRepository;
    }

    /**
     * Delete Data by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById($id)
    {
        Log::info($id);
        try {
            $busSitting = $this->busSittingRepository->delete($id);
        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $busSitting;
    }

    /**
     * Get all Data
     *
     * @return String
     */
    public function getAll()
    {
        return $this->busSittingRepository->getAll();
    }

    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->busSittingRepository->getById($id);
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
            $busSitting = $this->busSittingRepository->update($data, $id);
        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        }
        return $busSitting;

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
            $post = $this->busSittingRepository->save($data);

        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        }
        return $post;
    }
    /**
     * Get all Data in Datatable Format.
     *
     * @return String
     */

    public function getAllBusSittingDT($request)
    {
        return $this->busSittingRepository->getAllBusSittingDT($request);
    }
    public function changeStatus($id)
    {
        try {
            $post = $this->busSittingRepository->changeStatus($id);

        } catch (Exception $e) {
            throw new InvalidArgumentException('Unable to change status');
        }
        return $post;
    }
}