<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiUserManageOperatorService;

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
      
      
    protected $ApiUserManageOperatorService;
    protected $ApiUserManageOperatorRepository;
    protected $agentValidator;
    
    public function __construct(ApiUserManageOperatorService $ApiUserManageOperatorService,
                                AgentValidator $agentValidator,
                                ApiUserManageOperatorRepository $ApiUserManageOperatorRepository)
    {
        $this->ApiUserManageOperatorService = $ApiUserManageOperatorService;
        $this->agentValidator = $agentValidator;
          $this->ApiUserManageOperatorRepository = $ApiUserManageOperatorRepository;
    }


   


    public function manageClientOperatorData(Request $request) {

     // $agents = $this->ApiUserManageOperatorService->manageClientOperatorData($request);
     $agents = $this->ApiUserManageOperatorRepository->manageClientOperatorData($request);
      return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    } 


    public function manageClientOperator (Request $request) {

      try {
        // $this->ApiUserManageOperatorService->manageClientOperator($request);
        $this->ApiUserManageOperatorRepository->manageClientOperator($request);
      } 
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(Null,"Data Added Successfully",Response::HTTP_ACCEPTED); 
     
    }
    

    public function deletemanageClientOperator ($id) {

      try {
       // $this->ApiUserManageOperatorService->deletemanageClientOperator($id);
       $this->ApiUserManageOperatorRepository->deletemanageClientOperator($id);
        
      } 
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(Null,"Agent has been deleted Successfully",Response::HTTP_ACCEPTED); 
     
    }



}
