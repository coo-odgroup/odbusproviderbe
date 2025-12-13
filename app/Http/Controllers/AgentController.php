<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use InvalidArgumentException;
use Exception;
use App\AppValidator\AgentValidator;
use App\Repositories\AgentRepository;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class AgentController extends Controller
{
    use ApiResponser;
    
    protected $agentValidator;

    public function __construct(AgentValidator $agentValidator,AgentRepository $agentRepository)
    {
      $this->agentValidator = $agentValidator;
      $this->agentRepository = $agentRepository;
    }


    public function agentprofile(Request $request) {
      try {
        $agents = $this->agentRepository->agentprofile($request);
        return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
      } catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
    }

    public function updateAgentProfile(Request $request) {
      try {
        $agents = $this->agentRepository->updateAgentProfile($request);
        return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
      } catch (Exception $e) {
        return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
      }
    }

    public function getAllAgent(Request $request) {
      try {
        $agents = $this->agentRepository->getAll($request);
        return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
      } catch (Exception $e) {
        return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
      }
    }


    public function getAllAgentData(Request $request) {
      try {
        $agents = $this->agentRepository->getAllAgentData($request);
        return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
      } catch (Exception $e) {
        return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
      }
    }

    public function ourAgentData(Request $request) {
      try {
        $agents = $this->agentRepository->ourAgentData($request);
        return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
      } catch (Exception $e) {
        return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
      }
    }

    public function createAgent(Request $request) {
      DB::beginTransaction();
      try {
        $data = $request->only([
          'name',
          'email',
          'phone',
          'password',
          'user_type',
          'otp',
          'city',
          'street',
          'location',
          'adhar_no',
          'pancard_no',
          'organization_name',
          'address',
          'landmark',
          'pincode',
          'name_on_bank_account',
          'bank_name',
          'ifsc_code',
          'bank_account_no',
          'agentType',
          'created_by'
        ]);
        $agentValidation = $this->agentValidator->validate($data);
        
        if ($agentValidation->fails()) {
          $errors = $agentValidation->errors();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }

        $response =  $this->agentRepository->savePostData($request);
        
        if (in_array($response, [
            'Email Already Exist',
            'Phone Already Exist',
            'Pan Card Already Exist',
            'Aadhaar Card Already Exist'
        ])) {
            DB::rollBack();
            return $this->errorResponse($response, \Symfony\Component\HttpFoundation\Response::HTTP_PARTIAL_CONTENT);
        }

        DB::commit();
        return $this->successResponse($response, "Agent Create", Response::HTTP_CREATED);

      } catch (Exception $e) {
        DB::rollBack();
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
    }

    public function updateAgent(Request $request, $id){
      DB::beginTransaction();

      try {
        $data = $request->only([
            'name',
            'email',
            'phone',
            'password',
            'user_type',
            'otp',
            'location',
            'adhar_no',
            'pancard_no',
            'organization_name',
            'address',
            'landmark',
            'pincode',
            'name_on_bank_account',
            'bank_name',
            'ifsc_code',
            'bank_account_no',
            'agentType',
            'created_by'
        ]);

        $response = $this->agentRepository->update($data, $id);

        if (in_array($response, [
          'Email Already Exist',
          'Phone Already Exist',
          'Pan Card Already Exist',
          'Aadhaar Card Already Exist'
        ])) {
          DB::rollBack();
          return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
        }

        DB::commit();
        return $this->successResponse($response, "Agent Updated", Response::HTTP_CREATED);

      } catch (Exception $e) {
        DB::rollBack();
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
    }

    public function deleteAgent($id) {
      DB::beginTransaction();
      try {
        $this->agentRepository->delete($id);

        DB::commit();
        return $this->successResponse('Null',"Agent has been deleted Successfully",Response::HTTP_ACCEPTED);
      }
      catch (Exception $e) {
        DB::rollBack();
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
    }

    public function getAgent($id) {
      try {
        $agentID= $this->agentRepository->getById($id);
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
      return $this->successResponse($agentID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
      
    }

    public function changeStatus(Request $request) {
      DB::beginTransaction();
      try{
        $this->agentRepository->changeStatus($request);

        DB::commit();
        return $this->successResponse(null, "Agent Status Updated", Response::HTTP_ACCEPTED);
      }
      catch (Exception $e){
        DB::rollBack();
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      
    }

    public function blockAgent(Request $request) {
      DB::beginTransaction();
      try{
        $this->agentRepository->blockAgent($request);

        DB::commit();
        return $this->successResponse(null, "Agent Status Updated", Response::HTTP_ACCEPTED);
      }
      catch (Exception $e){
        DB::rollBack();
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
    }
    
}
