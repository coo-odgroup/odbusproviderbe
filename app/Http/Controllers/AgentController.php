<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AgentService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use InvalidArgumentException;
use Exception;
use App\AppValidator\AgentValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
class AgentController extends Controller
{
   
    use ApiResponser;
      
      
    protected $agentService;
    protected $agentValidator;
    
    public function __construct(AgentService $agentService,AgentValidator $agentValidator)
    {
        $this->agentService = $agentService;
        $this->agentValidator = $agentValidator;
    }


    public function getAllAgent(Request $request) {

      $agents = $this->agentService->getAll($request);
      return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    } 


    public function getAllAgentData(Request $request) {

      $agents = $this->agentService->getAllAgentData($request);
      return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }

    public function createAgent(Request $request) {
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
        'street',
        'landmark',
        'city',
        'pincode',
        'name_on_bank_account',
        'bank_name',
        'ifsc_code',
        'bank_account_no',
        'branch_name',
        'upi_id','created_by'
      ]);
      $agentValidation = $this->agentValidator->validate($data);
      
      if ($agentValidation->fails()) {
        $errors = $agentValidation->errors();
        return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      }

      try {
          $this->agentService->savePostData($data);
          
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }   
      return $this->successResponse($data,"Agent Added",Response::HTTP_CREATED); 
    } 

    public function updateAgent(Request $request, $id) {
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
          'street',
          'landmark',
          'city',
          'pincode',
          'name_on_bank_account',
          'bank_name',
          'ifsc_code',
          'bank_account_no',
          'branch_name',
          'upi_id','created_by'
        ]);
        
        $agentValidation = $this->agentValidator->validate($data);

        if ($agentValidation->fails()) {
          $errors = $agentValidation->errors();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        
        try {
          $result=$this->agentService->update($data, $id);
          return $this->successResponse($result, "Agent Updated",Response::HTTP_CREATED);
         
        }
        catch (Exception $e) {
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        
    }

    public function deleteAgent ($id) {

      try {
        $this->agentService->deleteById($id);
        
      } 
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(Null,"Agent Deleted",Response::HTTP_ACCEPTED); 
     
    }

    public function getAgent($id) {
      try {
        $AgentID= $this->agentService->getById($id);
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
      return $this->successResponse($AgentID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
      
    }   
    
}
