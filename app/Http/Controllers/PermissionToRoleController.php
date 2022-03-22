<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PermissionToRoleService;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\AppValidator\PermissionToRoleValidator;

class PermissionToRoleController extends Controller
{
    use ApiResponser;
   
    protected $permissionToRoleService;
    protected $permissionToRoleValidator;      
    
    public function __construct(PermissionToRoleService $permissionToRoleService, PermissionToRoleValidator $permissionToRoleValidator)
    {
        $this->permissionToRoleService = $permissionToRoleService;
        $this->permissionToRoleValidator = $permissionToRoleValidator;                
    }

    public function getAllPermissionToRole(Request $request)
    {
        return $this->permissionToRoleService->getAllPermissionToRole($request);            
	}   

	public function deletePermissionToRole($id)
    {      
         $response = $this->permissionToRoleService->deletePermissionToRole($id);
         return $this->successResponse($response,"Delete Successful", Response::HTTP_CREATED);  
	}   

	public function addPermissionToRole(Request $request)
    {
         //log::info($request);
         $response = $this->permissionToRoleService->addPermissionToRole($request);
         return $this->successResponse($response,"Permission assigned to Role", Response::HTTP_CREATED);   
	}     

}