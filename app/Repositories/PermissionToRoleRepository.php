<?php

namespace App\Repositories;
use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionToRole;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;

/*Priyadarshi to Review*/
class PermissionToRoleRepository
{    
   protected $role;
   protected $permission;
   protected $permissionToRole;

   public function __construct(Role $role,Permission $permission,PermissionToRole $permissionToRole  )
   {
      $this->role = $role ;
      $this->permission = $permission ;
      $this->permissionToRole = $permissionToRole ;
   }     

    public function getAllPermissionToRole($request)
    {        
        $paginate = $request['rows_number'];
        //$agent_id = $request['agent_id'];

        $data = $this->role->with('PermissionToRole.Permission')                                       
                                        ->orderBy('id','DESC');
       
        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }

        // if($agent_id)
        // {
        //    $data = $data->where('user_id',$agent_id);
        // }   
        
        $data = $data->paginate($paginate);    
        
        // if($data)
        // {
        //     foreach($data as $k=>$v)
        //     { 
        //         Log::info(count($v->PermissionToRole));
        //         if(count($v->PermissionToRole)!=0)
        //         {
        //             $data['permissionData']=$v;
                    
        //         }
               
        //     }
        // }
        $response = array(
               "count" => $data->count(), 
               "total" => $data->total(),
               "data" => $data
             );   
        return $response;
    } 

    public function addPermissionToRole($request)
    { 
        //log::info($request); exit;

        foreach ($request['submenu'] as $k=>$submenu) 
        {        
            $PTR = new $this->permissionToRole;            
            $PTR->role_id = $request['role_id'];
            $PTR->menu = $request['menu'];
            $PTR->submenu = $submenu;
            $PTR->add_status = ($request['add_status'] == true)?'1':'0'; 
            $PTR->update_status = ($request['update_status'] == true)?'1':'0'; 
            $PTR->view_status = ($request['view_status'] == true)?'1':'0'; 
            $PTR->delete_status = ($request['delete_status'] == true)?'1':'0'; 
            $PTR->created_by = $request['created_by'];
            $PTR->save();
        }
        return 'done' ;
    } 

   public function deletePermissionToRole($id)
   {
        $permissionToRole = $this->permissionToRole->find($id);
        $permissionToRole->status = 2;
        $permissionToRole->update();
        return $permissionToRole;
   }
}