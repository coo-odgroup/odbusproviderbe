<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ApiUserManageOperatorRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use InvalidArgumentException;
use Exception;
use App\AppValidator\AgentValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class ApiUserManageOperatorController extends Controller
{
    use ApiResponser;


    protected $ApiUserManageOperatorRepository;
    protected $agentValidator;

    public function __construct(ApiUserManageOperatorRepository $ApiUserManageOperatorRepository, AgentValidator $agentValidator)
    {
        $this->ApiUserManageOperatorRepository = $ApiUserManageOperatorRepository;
        $this->agentValidator = $agentValidator;
    }





    public function manageClientOperatorData(Request $request)
    {

        $agents = $this->ApiUserManageOperatorRepository->manageClientOperatorData($request);
        return $this->successResponse($agents, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }


    public function manageClientOperator(Request $request)
    {

        try {
            $res=$this->ApiUserManageOperatorRepository->manageClientOperatorData($request);
            // }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($res, "Data Added Successfully", Response::HTTP_OK);
    }


    public function deletemanageClientOperator($id)
    {

        try {
            $this->ApiUserManageOperatorRepository->deletemanageClientOperator($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, "Agent has been deleted Successfully", Response::HTTP_OK);
    }
}
