<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusType;
use App\Services\BusTypeService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use InvalidArgumentException;
use Exception;
use App\AppValidator\BusTypeValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
class BusTypeController extends Controller
{
   
    use ApiResponser;
      
      
    protected $busTypeService;
    protected $busTypeValidator;
    
    public function __construct(BusTypeService $busTypeService,BusTypeValidator $busTypeValidator)
    {
        $this->busTypeService = $busTypeService;
        $this->busTypeValidator = $busTypeValidator;
    }


    public function getAllBusType(Request $request) {

      $busTypes = $this->busTypeService->getAll($request);
      return $this->successResponse($busTypes,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    } 


    public function getAllBusTypeData(Request $request) {

      $busTypes = $this->busTypeService->getAllBusTypeData($request);
      return $this->successResponse($busTypes,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }

    public function createBusType(Request $request) {
      $data = $request->only([
        'type',
        'name',
        'status'
      ]);
      $busTypeValidation = $this->busTypeValidator->validate($data);
      
      if ($busTypeValidation->fails()) {
        $errors = $busTypeValidation->errors();
        // return $errors->toJson();
        return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      }

      try {
          $this->busTypeService->savePostData($data);
          
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }   
      return $this->successResponse($data,Config::get('constants.RECORD_ADDED'),Response::HTTP_CREATED); 
    } 

    public function updateBusType(Request $request, $id) {
        $data = $request->only([
          'type',
          'name',
          'status'
        ]);
        
        $busTypeValidation = $this->busTypeValidator->validate($data);

        if ($busTypeValidation->fails()) {
          $errors = $busTypeValidation->errors();
          // return $errors->toJson();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        
        try {
          $this->busTypeService->update($data, $id);
          return $this->successResponse(null, Config::get('constants.RECORD_UPDATED'),Response::HTTP_CREATED);
         
        }
        catch (Exception $e) {
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        
    }

    public function deleteBusType ($id) {

      try {
        $this->busTypeService->deleteById($id);
        
      } 
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(Null,Config::get('constants.RECORD_REMOVED'),Response::HTTP_ACCEPTED); 
     
    }

    public function getBusType($id) {
      try {
        $bustypeID= $this->busTypeService->getById($id);
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
      return $this->successResponse($bustypeID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
      
    }   
    public function getBusTypeDT(Request $request) {      
        
      $busTypes = $this->busTypeService->getAllBusTypeDT($request);
      return $this->successResponse($busTypes,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
      
    }

    public function changeStatus ($id) {
      try{
        $this->busTypeService->changeStatus($id);
      }
      catch (Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(null, Config::get('constants.RECORD_UPDATED'), Response::HTTP_ACCEPTED);
    }


}
