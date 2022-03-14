<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusSitting;
use App\Services\RoleService;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use InvalidArgumentException;
use App\AppValidator\RoleValidator;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    use ApiResponser;
    protected $roleService;
    protected $roleValidator;
    /**
     * PostController Constructor
     *
     * @param RoleService $roleService
     *
     */
    public function __construct(RoleService $roleService,RoleValidator $roleValidator)
    {
        $this->roleService = $roleService;
        $this->roleValidator = $roleValidator;
    }

    public function getAllRole(Request $request) 
    {
        $role = $this->roleService->getAll($request);
        return $this->successResponse($role,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    } 

    public function RoleData(Request $request) 
    {
        $role = $this->roleService->RoleData($request);
        return $this->successResponse($role,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }

    public function createRole(Request $request) 
    {
        $data = $request->only([
          'name','created_by','user_id'          
        ]);
        
        $roleValidation = $this->roleValidator->validate($data);
        
        if ($roleValidation->fails()) {
          $errors = $roleValidation->errors();
          // return $errors->toJson();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
          $this->roleService->savePostData($data);
          
      }
       catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }  
      return $this->successResponse($data,"Sitting Type Added",Response::HTTP_CREATED); 
      
    } 

    public function updateRole(Request $request, $id) {
        $data = $request->only([
          'name','created_by','user_id'
        ]);
        
        $roleValidation =   $this->roleValidator->validate($data);
        
        if ($roleValidation->fails()) {
          $errors = $roleValidation->errors();
          // return $errors->toJson();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }

        try {
          $this->roleService->update($data, $id);
          
        }
         catch (Exception $e) {
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($data,"Sitting Type Updated",Response::HTTP_CREATED);     
    }

    public function deleteRole ($id) {
      try {
        $this->roleService->deleteById($id);
        
      } 
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),"404");
      }
      return $this->successResponse(Null,"Sitting Type Deleted",Response::HTTP_ACCEPTED);
    }

    public function getBusSitting($id) {
      try {
        $busSittingID= $this->roleService->getById($id);
        
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
      return $this->successResponse($busSittingID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
   
    public function getRoleDT(Request $request) 
    {              
       $role = $this->roleService->getAllRoleDT($request);
       return $this->successResponse($role,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);      
    }

    public function changeStatus ($id)
    {
      try{
        $this->busSittingService->changeStatus($id);
      }
      catch (Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(null, "Sitting Type Status Updated", Response::HTTP_ACCEPTED);
    }
}
