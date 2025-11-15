<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AgentFeeService;
use App\Repositories\AgentFeeRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use InvalidArgumentException;
use Exception;
use App\AppValidator\AgentFeeValidator;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class AgentFeeController extends Controller
{
    use ApiResponser;


    protected $agentFeeService;
    protected $agentFeeValidator;

    public function __construct(AgentFeeService $agentFeeService, AgentFeeValidator $agentFeeValidator,AgentFeeRepository $agentFeeRepository)
    {
        $this->agentFeeService = $agentFeeService;
        $this->agentFeeValidator = $agentFeeValidator;
        $this->agentFeeRepository = $agentFeeRepository;
    }


    public function getAllAgentFee(Request $request){
        try {
            $agentFees = $this->agentFeeRepository->getAll($request);
            return $this->successResponse($agentFees, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        
    }


    public function getAllAgentFeeData(Request $request){
        try {
            $agentFees = $this->agentFeeRepository->getAllAgentFeeData($request);
            return $this->successResponse($agentFees, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        
    }

    public function createAgentFee(Request $request)
    {
        $data = $request->only([
          'price_from',
          'price_to',
          'max_comission',
          'created_by'
        ]);
        $agentFeeValidation = $this->agentFeeValidator->validate($data);

        if ($agentFeeValidation->fails()) {
            $errors = $agentFeeValidation->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        DB::beginTransaction();

        try {
            $this->agentFeeRepository->save($data);
            DB::commit();
            return $this->successResponse($data, "Agent Fee Slab Added", Response::HTTP_CREATED);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function updateAgentFee(Request $request, $id)
    {
        $data = $request->only([
          'price_from',
          'price_to',
          'max_comission',
          'created_by'
        ]);

        $agentFeeValidation = $this->agentFeeValidator->validate($data);

        if ($agentFeeValidation->fails()) {
            $errors = $agentFeeValidation->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        DB::beginTransaction();

        try {
            $this->agentFeeRepository->update($data, $id);
            DB::commit();
            return $this->successResponse(null, "Agent Fee Slab Updated", Response::HTTP_CREATED);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }

    }

    public function deleteAgentFee($id)
    {
        DB::beginTransaction();
        try {
            $this->agentFeeRepository->delete($id);

            DB::commit();
            return $this->successResponse(null, "Agent Fee Slab Deleted", Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function getAgentFee($id)
    {
        try {
            $agentFeeID = $this->agentFeeRepository->getById($id);
            return $this->successResponse($agentFeeID, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

}
