<?php

namespace App\Services;

use App\Repositories\PermissionToRoleRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class PermissionToRoleService
{
    protected $permissionToRoleRepository;    
   
    public function __construct(PermissionToRoleRepository $permissionToRoleRepository)
    {
        $this->permissionToRoleRepository = $permissionToRoleRepository;
    }          

    public function getAllPermissionToRole($request)
    {
        //return $this->permissionToRoleRepository->getAllPermissionToRole($request);
    } 

    // public function addPermissionToRole($request)
    // {
    //     return $this->permissionToRoleRepository->addPermissionToRole($request);
    // }

    // public function deletePermissionToRole($id)
    // {
    //     return $this->permissionToRoleRepository->deletePermissionToRole($id);
    // } 
   
    // public function changeStatus($id)
    // {
    //     return $this->permissionToRoleRepository->changeStatus($id);
    // }   

}