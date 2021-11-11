<?php

namespace App\Services;

use App\Repositories\AgentRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class AgentService
{
    protected $agentRepository;
    public function __construct(AgentRepository $agentRepository)
    {
        $this->agentRepository = $agentRepository;
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
            $busType = $this->agentRepository->delete($id);

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
        return $this->agentRepository->getAll($request);
    }

   
    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->agentRepository->getById($id);
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
            $agent = $this->agentRepository->update($data, $id);

        } catch (Exception $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
        return $agent;
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
            $agent = $this->agentRepository->save($data);
        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $agent;
    }
    /**
     * Get all Data in Datatable Format.
     *
     * @return String
     */
    public function getAllAgentData($request)
    {
        return $this->agentRepository->getAllAgentData($request);
    }
    public function changeStatus($id)
    {
        try {
            $agent = $this->agentRepository->changeStatus($id);
        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.UNABLE_CHANGE_STATUS'));
        }
        return $agent;

    }

}