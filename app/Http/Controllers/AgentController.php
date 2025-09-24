<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AgentService;
use Illuminate\Support\Facades\Validator;
use App\Repositories\AgentRepository;
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
    protected $agentRepository;
    
    public function __construct(AgentService $agentService,
                                AgentValidator $agentValidator,
                                AgentRepository $agentRepository
                                )
    {
        $this->agentService = $agentService;
        $this->agentValidator = $agentValidator;
        $this->agentRepository = $agentRepository;
    }


    public function agentprofile(Request $request) {

     // $agents = $this->agentService->agentprofile($request);
     $agents = $this->agentRepository->agentprofile($request);
      return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    } 

    public function updateAgentProfile(Request $request) {

     // $agents = $this->agentService->updateAgentProfile($request);
     $agents = $this->agentRepository->updateAgentProfile($request);
      return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    } 

    public function getAllAgent(Request $request) {

      //$agents = $this->agentService->getAll($request);
      $agents = $this->agentRepository->getAll($request);

      return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    } 


    public function getAllAgentData(Request $request) {

      //$agents = $this->agentService->getAllAgentData($request);
      $agents = $this->agentRepository->getAllAgentData($request);

      return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    } 

    public function ourAgentData(Request $request) {

      //$agents = $this->agentService->ourAgentData($request);
       $agents = $this->agentRepository->ourAgentData($request);
      return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }

    public function createAgent(Request $request) {
    // Log::info($request);
    // exit;
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
      else
        {
          $data = $request->all();
          ///$response =  $this->agentService->savePostData($request);
           $response = $agent = $this->agentRepository->save($data);

           if($response=='Email Already Exist')
           {
              return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
           }
           else if($response=='Phone Already Exist')
           {
              return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
           }
           else if($response=='Pan Card Already Exist')
           {
              return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
           }
           else if($response=='Aadhaar Card Already Exist')
           {
              return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
           }
           else
           {
               return $this->successResponse($response,"Agent Added", Response::HTTP_CREATED);
           }
        }
      // try {
      //     $this->agentService->savePostData($data);
          
      // }
      // catch (Exception $e) {
      //   return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      // }   
      // return $this->successResponse($data,"Agent Created Successfully",Response::HTTP_CREATED); 
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
          'landmark',
          'pincode',
          'name_on_bank_account',
          'bank_name',
          'ifsc_code',
          'bank_account_no',
          'agentType',
          'created_by'
        ]);

        //$response =  $this->agentService->update($data, $id);

       $respons = $this->agentRepository->update($data, $id);

           if($response=='Email Already Exist')
           {
              return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
           }
           else if($response=='Phone Already Exist')
           {
              return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
           }
           else if($response=='Pan Card Already Exist')
           {
              return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
           }
           else if($response=='Aadhaar Card Already Exist')
           {
              return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
           }
           else
           {
               return $this->successResponse($response,"Agent Updated", Response::HTTP_CREATED);
           }
        
    }

    public function deleteAgent ($id) {

      try {
        //$this->agentService->deleteById($id);
        $this->agentRepository->delete($id);
        
      } 
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(Null,"Agent has been deleted Successfully",Response::HTTP_ACCEPTED); 
     
    }

    public function getAgent($id) {
      try {
       // $AgentID= $this->agentService->getById($id);
         $AgentID = $this->agentRepository->getById($id);
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
      return $this->successResponse($AgentID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
      
    }   

    public function changeStatus(Request $request) {
      // Log::info($request);exit;
        try{
          //$this->agentService->changeStatus($request);
          $this->agentRepository->changeStatus($request);
        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, "Agent Status Updated", Response::HTTP_ACCEPTED);
      }

      public function blockAgent(Request $request) {
        try{
         // $this->agentService->blockAgent($request);
             $this->agentRepository->blockAgent($request);
        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, "Agent Status Updated", Response::HTTP_ACCEPTED);
      }
    
}
