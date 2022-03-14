<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusSitting;
use App\Services\PermissionService;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use InvalidArgumentException;
use App\AppValidator\PermissionValidator;
use Symfony\Component\HttpFoundation\Response;

class PermissionController extends Controller
{
    use ApiResponser;
    protected $permissionService;
    protected $permissionValidator;
    /**
     * PostController Constructor
     *
     * @param PermissionService $permissionService
     *
     */
    public function __construct(PermissionService $permissionService,PermissionValidator $permissionValidator)
    {
        $this->permissionService = $permissionService;
        $this->permissionValidator = $permissionValidator;
    }

    public function getAllPermission(Request $request) 
    {
        $role = $this->permissionService->getAll($request);
        return $this->successResponse($role,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    } 

    public function PermissionData(Request $request) 
    {
        $role = $this->permissionService->PermissionData($request);
        return $this->successResponse($role,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }

    public function createPermission(Request $request) 
    {
        $data = $request->only([
          'name','created_by'          
        ]);
        
        $permissionValidation = $this->permissionValidator->validate($data);
        
        if ($permissionValidation->fails()) {
          $errors = $permissionValidation->errors();
          // return $errors->toJson();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
          $this->permissionService->savePostData($data);
          
      }
       catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }  
      return $this->successResponse($data,"Sitting Type Added",Response::HTTP_CREATED); 
      
    } 

    public function updatePermission(Request $request, $id) {
        $data = $request->only([
          'name','created_by'
        ]);
        
        $permissionValidation =   $this->permissionValidator->validate($data);
        
        if ($permissionValidation->fails()) {
          $errors = $permissionValidation->errors();
          // return $errors->toJson();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }

        try {
          $this->permissionService->update($data, $id);
          
        }
         catch (Exception $e) {
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($data,"Sitting Type Updated",Response::HTTP_CREATED);     
    }

    public function deletePermission ($id) {
      try {
        $this->permissionService->deleteById($id);
        
      } 
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),"404");
      }
      return $this->successResponse(Null,"Sitting Type Deleted",Response::HTTP_ACCEPTED);
    }

    public function getPermission($id) {
      try {
        $permission= $this->permissionService->getById($id);        
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
      return $this->successResponse($permission,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
   
    public function getPermissionDT(Request $request) 
    {              
       $permission = $this->permissionService->getAllPermissionDT($request);
       return $this->successResponse($permission,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);      
    }

    public function changeStatus ($id)
    {
      try{
        $this->permissionService->changeStatus($id);
      }
      catch (Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(null, "Sitting Type Status Updated", Response::HTTP_ACCEPTED);
    }
}
