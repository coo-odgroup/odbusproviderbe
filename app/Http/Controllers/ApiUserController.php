<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiUserService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use InvalidArgumentException;
use Exception;
use App\AppValidator\ApiUserValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class ApiUserController extends Controller
{    
    use ApiResponser;   
      
    protected $apiUserService;
    protected $apiUserValidator;
    
    public function __construct(ApiUserService $apiUserService,ApiUserValidator $apiUserValidator)
    {
        $this->apiUserService = $apiUserService;
        $this->apiUserValidator = $apiUserValidator;
    }

    public function createApiUser(Request $request) 
    {
        // Log::info($request);
        // exit;
        $data = $request->only([
                                'name',
                                'email',
                                'phone',
                                'password',
                                'user_type',                    
                                'city',
                                'street',
                                'location',            
                                'pancard_no',
                                'organization_name',
                                'address',
                                'landmark',
                                'pincode',            
                                'created_by'
                              ]);
        
        $apiUserValidation = $this->apiUserValidator->validate($data);
      
        if ($apiUserValidation->fails()) {
            $errors = $apiUserValidation->errors();
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        else
        {
                $response =  $this->apiUserService->savePostData($request);

                if($response == 'Email Already Exist')
                {
                    return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
                }
                else if($response == 'Phone Already Exist')
                {
                    return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
                }
                else if($response == 'Pan Card Already Exist')
                {
                    return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
                }           
                else
                {
                    return $this->successResponse($response,"API User Added", Response::HTTP_CREATED);
                }
        }
      // try {
      //     $this->apiUserService->savePostData($data);
          
      // }
      // catch (Exception $e) {
      //   return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      // }   
      // return $this->successResponse($data,"Agent Created Successfully",Response::HTTP_CREATED); 
    } 

    public function agentprofile(Request $request) 
    {
      $agents = $this->apiUserService->agentprofile($request);
      return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    } 

    public function updateAgentProfile(Request $request) {

      $agents = $this->apiUserService->updateAgentProfile($request);
      return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    } 

    public function getAllAgent(Request $request) {

      $agents = $this->apiUserService->getAll($request);
      return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    } 


    public function getAllApiUserData(Request $request) 
    {
        $agents = $this->apiUserService->getAllApiUserData($request);
        return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    } 

    public function ourAgentData(Request $request) 
    {
        $agents = $this->apiUserService->ourAgentData($request);
        return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }    

    public function updateApiUser(Request $request, $id) 
    {
        $data = $request->only([
                            'name',
                            'email',
                            'phone',
                            'password',
                            'user_type',
                            'location',
                            'pancard_no',
                            'organization_name',
                            'address',
                            'landmark',
                            'pincode',                           
                            'created_by'
        ]);

        $response =  $this->apiUserService->update($data, $id);

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
        $this->apiUserService->deleteById($id);
        
      } 
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(Null,"Agent has been deleted Successfully",Response::HTTP_ACCEPTED); 
     
    }

    public function getAgent($id) {
      try {
        $AgentID= $this->apiUserService->getById($id);
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
      return $this->successResponse($AgentID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
      
    }   

    public function changeStatus(Request $request) {
      // Log::info($request);exit;
        try{
          $this->apiUserService->changeStatus($request);
        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, "Agent Status Updated", Response::HTTP_ACCEPTED);
      }

      public function blockAgent(Request $request) {
        try{
          $this->apiUserService->blockAgent($request);
        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, "Agent Status Updated", Response::HTTP_ACCEPTED);
      }
    
}
