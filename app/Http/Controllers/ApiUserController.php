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
                                'client_id',
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


    public function getAllApiUserData(Request $request) 
    {
        $agents = $this->apiUserService->getAllApiUserData($request);
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
                            'city',
                            'street',
                            'location', 
                            'address',
                            'landmark',
                            'pincode',                           
                            'created_by'
                 ]);                              

           //log::info($data);

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
           else
           {
               return $this->successResponse($response,"Api User Updated", Response::HTTP_CREATED);
           }        
    }     

    public function changeStatus(Request $request) 
    {      
        try{
          $this->apiUserService->changeStatus($request);
        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, "Api User Status Updated", Response::HTTP_ACCEPTED);
    } 

     public function apiclientprofile(Request $request) 
     {      
         $agents = $this->apiUserService->apiclientprofile($request);
         return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
     } 

     public function updateapiclient(Request $request) 
     {      
          $agents = $this->apiUserService->updateapiclient($request);
          return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
     }  
}