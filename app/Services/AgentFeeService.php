<?php

namespace App\Services;

use App\Repositories\AgentFeeRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class AgentFeeService
{
    protected $agentFeeRepository;
    public function __construct(AgentFeeRepository $agentFeeRepository)
    {
        $this->agentFeeRepository = $agentFeeRepository;
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
            $busType = $this->agentFeeRepository->delete($id);

        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $busType;

    }
    /**
     * Get all Data
     *
     * @return String
     */
    public function getAll($request)
    {
        return $this->agentFeeRepository->getAll($request);
    }

   
    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->agentFeeRepository->getById($id);
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
            $busType = $this->agentFeeRepository->update($data, $id);

        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $busType;
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
            $busType = $this->agentFeeRepository->save($data);
        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $busType;
    }
    /**
     * Get all Data in Datatable Format.
     *
     * @return String
     */
    public function getAllAgentFeeData($request)
    {
        return $this->agentFeeRepository->getAllAgentFeeData($request);
    }
    public function changeStatus($id)
    {
        try {
            $busType = $this->agentFeeRepository->changeStatus($id);
        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.UNABLE_CHANGE_STATUS'));
        }
        return $busType;

    }

}