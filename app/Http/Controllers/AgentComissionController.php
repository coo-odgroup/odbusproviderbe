<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AgentCommissionService;
use App\Repositories\AgentCommissionRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Exception;
use App\AppValidator\AgentCommissionValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class AgentComissionController extends Controller
{
    use ApiResponser;


    protected $agentCommissionService;
    protected $agentCommissionValidator;
<<<<<<< HEAD
    
    public function __construct(AgentCommissionService $agentCommissionService,AgentCommissionValidator $agentCommissionValidator,AgentCommissionRepository $agentCommissionRepository)
=======

    public function __construct(AgentCommissionService $agentCommissionService, AgentCommissionValidator $agentCommissionValidator)
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
    {
        $this->agentCommissionService = $agentCommissionService;
        $this->agentCommissionValidator = $agentCommissionValidator;
        $this->agentCommissionRepository = $agentCommissionRepository;
    }


    public function getAllAgentCommission(Request $request)
    {

<<<<<<< HEAD
      $agentCommissions = $this->agentCommissionRepository->getAll($request);
      return $this->successResponse($agentCommissions,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }


    public function getAllAgentCommissionData(Request $request) {

      $agentCommissions = $this->agentCommissionRepository->getAllAgentCommissionData($request);
      return $this->successResponse($agentCommissions,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
=======
        $agentCommissions = $this->agentCommissionService->getAll($request);
        return $this->successResponse($agentCommissions, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
    }


    public function getAllAgentCommissionData(Request $request)
    {

<<<<<<< HEAD
      DB::beginTransaction();

      try {
        $this->agentCommissionRepository->save($data);
        DB::commit();
        return $this->successResponse($data,"Agent Commission Slab Added",Response::HTTP_CREATED);
      }
      catch (Exception $e) {
        DB::rollBack();
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      
    }

    public function updateAgentCommission(Request $request, $id) {
      $data = $request->only([
        'range_from',
        'range_to',
        'comission_per_seat','user_name'
      ]);
      
      $agentCommissionValidation = $this->agentCommissionValidator->validate($data);

      if ($agentCommissionValidation->fails()) {
        $errors = $agentCommissionValidation->errors();
        return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      }
      
      DB::beginTransaction();
      try {
        $this->agentCommissionRepository->update($data, $id);

        DB::commit();
        return $this->successResponse(null, "Agent Commission Slab Updated",Response::HTTP_CREATED);
        
      }
      catch (Exception $e) {
        DB::rollBack();
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
        
=======
        $agentCommissions = $this->agentCommissionService->getAllAgentCommissionData($request);
        return $this->successResponse($agentCommissions, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function createAgentCommission(Request $request)
    {
        $data = $request->only([
          'range_from',
          'range_to',
          'comission_per_seat',
          'user_name'

        ]);
        $agentCommissionValidation = $this->agentCommissionValidator->validate($data);

        if ($agentCommissionValidation->fails()) {
            $errors = $agentCommissionValidation->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        try {
            $this->agentCommissionService->savePostData($data);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($data, "Agent Commission Slab Added", Response::HTTP_CREATED);
    }

    public function updateAgentCommission(Request $request, $id)
    {
        $data = $request->only([
          'range_from',
          'range_to',
          'comission_per_seat','user_name'
        ]);

        $agentCommissionValidation = $this->agentCommissionValidator->validate($data);

        if ($agentCommissionValidation->fails()) {
            $errors = $agentCommissionValidation->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        try {
            $this->agentCommissionService->update($data, $id);
            return $this->successResponse(null, "Agent Commission Slab Updated", Response::HTTP_CREATED);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }

>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
    }

    public function deleteAgentCommission($id)
    {

        try {
            $this->agentCommissionService->deleteById($id);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, "Agent Commission Slab Deleted", Response::HTTP_ACCEPTED);

    }

    public function getAgentCommission($id)
    {
        try {
            $AgentCommissionID = $this->agentCommissionService->getById($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse($AgentCommissionID, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);

<<<<<<< HEAD
      try {
        $this->agentCommissionRepository->delete($id);
        
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(Null,"Agent Commission Slab Deleted",Response::HTTP_ACCEPTED);
     
    }

    public function getAgentCommission($id) {
      try {
        $AgentCommissionID= $this->agentCommissionRepository->getById($id);
        return $this->successResponse($AgentCommissionID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
    }
    
=======
    }

>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
}
