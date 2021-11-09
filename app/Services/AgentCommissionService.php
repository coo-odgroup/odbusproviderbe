<?php

namespace App\Services;

use App\Repositories\AgentCommissionRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class AgentCommissionService
{
    protected $agentCommissionRepository;
    public function __construct(AgentCommissionRepository $agentCommissionRepository)
    {
        $this->agentCommissionRepository = $agentCommissionRepository;
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
            $busType = $this->agentCommissionRepository->delete($id);

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
        return $this->agentCommissionRepository->getAll($request);
    }

   
    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->agentCommissionRepository->getById($id);
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
            $busType = $this->agentCommissionRepository->update($data, $id);

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
            $busType = $this->agentCommissionRepository->save($data);
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
    public function getAllAgentCommissionData($request)
    {
        return $this->agentCommissionRepository->getAllAgentCommissionData($request);
    }
    public function changeStatus($id)
    {
        try {
            $busType = $this->agentCommissionRepository->changeStatus($id);
        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.UNABLE_CHANGE_STATUS'));
        }
        return $busType;

    }

}