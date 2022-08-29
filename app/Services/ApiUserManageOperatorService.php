<?php

namespace App\Services;

use App\Repositories\ApiUserManageOperatorRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class ApiUserManageOperatorService
{
    protected $ApiUserManageOperatorRepository;
 
    public function __construct(ApiUserManageOperatorRepository $ApiUserManageOperatorRepository)
    {
        $this->ApiUserManageOperatorRepository = $ApiUserManageOperatorRepository;
    }

    public function deletemanageClientOperator($id)
    {
        try {
            $busType = $this->ApiUserManageOperatorRepository->deletemanageClientOperator($id);

        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $busType;

    }
   
    public function manageClientOperatorData($request)
    {
        return $this->ApiUserManageOperatorRepository->manageClientOperatorData($request);
    }


    public function manageClientOperator($request)
    {
        return $this->ApiUserManageOperatorRepository->manageClientOperator($request);
    }
    
}