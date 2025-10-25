<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\ApiUserCommissionService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use InvalidArgumentException;
use Exception;
use App\AppValidator\ApiUserCommissionValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class ApiUserComissionController extends Controller
{   
    use ApiResponser;     
      
    protected $apiuserCommissionService;
    protected $apiuserCommissionValidator;
    
    public function __construct(ApiUserCommissionService $apiuserCommissionService,ApiUserCommissionValidator $apiuserCommissionValidator)
    {
        $this->apiuserCommissionService = $apiuserCommissionService;
        $this->apiuserCommissionValidator = $apiuserCommissionValidator;
    }

    public function getAllApiUserCommission(Request $request) 
    {
        $ApiUserCommissions = $this->apiuserCommissionService->getAll($request);
        return $this->successResponse($ApiUserCommissions,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    } 

    public function getAllApiUserCommissionData(Request $request) 
    {
        $ApiUserCommissions = $this->apiuserCommissionService->getAllApiUserCommissionData($request);
        return $this->successResponse($ApiUserCommissions,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }

    public function createApiUserCommission(Request $request) 
    {
        $data = $request->only([
                    'user_id',
                    'starting_fare',
                    'upto_fare',
                    'commision',
                    'addationalCharges',
                    'dolphinaddationalCharges',
                    'cancelCommission',
                    'created_by'                    
                ]);

        $ApiUserCommissionValidation = $this->apiuserCommissionValidator->validate($data);
        
        if($ApiUserCommissionValidation->fails())
        {
            $errors = $ApiUserCommissionValidation->errors();
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try 
        {
            $this->apiuserCommissionService->savePostData($data);          
        }
        catch (Exception $e) 
        {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }   
        return $this->successResponse($data,"API User Commission Slab Added",Response::HTTP_CREATED); 
    } 

    public function updateApiUserCommission(Request $request, $id) 
    {
        $data = $request->only([
                    'user_id',
                    'starting_fare',
                    'upto_fare',
                    'commision',
                    'addationalCharges',
                    'dolphinaddationalCharges',
                    'cancelCommission',
                    'created_by'  
        ]);
        
        $ApiUserCommissionValidation = $this->apiuserCommissionValidator->validate($data);

        if ($ApiUserCommissionValidation->fails())
        {
            $errors = $ApiUserCommissionValidation->errors();
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }        
        try 
        {
            $this->apiuserCommissionService->update($data, $id);
            return $this->successResponse(null, "API User Commission Slab Updated",Response::HTTP_CREATED);         
        }
        catch (Exception $e) 
        {
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }        
    }

    public function deleteApiUserCommission ($id) 
    {
        try 
        {
            $this->apiuserCommissionService->deleteById($id);        
        } 
        catch (Exception $e) 
        {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(Null,"API User Commission Slab Deleted",Response::HTTP_ACCEPTED);      
    }

    public function getApiUserCommission($id) 
    {
        try 
        {
            $ApiUserCommissionID = $this->apiuserCommissionService->getById($id);
        }
        catch (Exception $e) 
        {
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse($AgentCommissionID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);       
    }     
   
}