<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AgentFeeService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use InvalidArgumentException;
use Exception;
use App\AppValidator\AgentFeeValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
class AgentFeeController extends Controller
{
   
    use ApiResponser;
      
      
    protected $agentFeeService;
    protected $agentFeeValidator;
    
    public function __construct(AgentFeeService $agentFeeService,AgentFeeValidator $agentFeeValidator)
    {
        $this->agentFeeService = $agentFeeService;
        $this->agentFeeValidator = $agentFeeValidator;
    }


    public function getAllAgentFee(Request $request) {

      $agentFees = $this->agentFeeService->getAll($request);
      return $this->successResponse($agentFees,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    } 


    public function getAllAgentFeeData(Request $request) {

      $agentFees = $this->agentFeeService->getAllAgentFeeData($request);
      return $this->successResponse($agentFees,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }

    public function createAgentFee(Request $request) {
      $data = $request->only([
        'price_from',
        'price_to',
        'max_comission',
        'created_by'
      ]);
      $agentFeeValidation = $this->agentFeeValidator->validate($data);
      
      if ($agentFeeValidation->fails()) {
        $errors = $agentFeeValidation->errors();
        return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      }

      try {
          $this->agentFeeService->savePostData($data);
          
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }   
      return $this->successResponse($data,"Agent Fee Slab Added",Response::HTTP_CREATED); 
    } 

    public function updateAgentFee(Request $request, $id) {
        $data = $request->only([
          'price_from',
          'price_to',
          'max_comission',
          'created_by'
        ]);
        
        $agentFeeValidation = $this->agentFeeValidator->validate($data);

        if ($agentFeeValidation->fails()) {
          $errors = $agentFeeValidation->errors();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        
        try {
          $this->agentFeeService->update($data, $id);
          return $this->successResponse(null, "Agent Fee Slab Updated",Response::HTTP_CREATED);
         
        }
        catch (Exception $e) {
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        
    }

    public function deleteAgentFee ($id) {

      try {
        $this->agentFeeService->deleteById($id);
        
      } 
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(Null,"Agent Fee Slab Deleted",Response::HTTP_ACCEPTED); 
     
    }

    public function getAgentFee($id) {
      try {
        $AgentFeeID= $this->agentFeeService->getById($id);
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
      return $this->successResponse($AgentFeeID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
      
    }   
    
}
