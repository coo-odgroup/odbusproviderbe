<?php

namespace App\Services;

use App\Models\Safety;
use App\Repositories\OdbusChargesRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class OdbusChargesService
{
    /**
     * @var $safetyRepository
     */
    protected $odbusChargesRepository;

    /**
     * SafetyRepository constructor.
     *
     * @param SafetyRepository $safetyRepository
     */
    public function __construct(OdbusChargesRepository $odbusChargesRepository)
    {
        $this->odbusChargesRepository = $odbusChargesRepository;
    }

    
    public function getAll()
    {
        return $this->odbusChargesRepository->getAll();
    }
    
   
    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->odbusChargesRepository->getById($id);
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
            $odbusChargesRepository = $this->odbusChargesRepository->update($data, $id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $odbusChargesRepository;
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
            $odbusChargesRepository = $this->odbusChargesRepository->save($data);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        }
        return $odbusChargesRepository;
    }

}