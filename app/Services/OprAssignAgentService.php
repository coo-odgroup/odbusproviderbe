<?php

namespace App\Services;

use App\Repositories\OprAssignAgentRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class OprAssignAgentService
{
    protected $oprAssignAgentRepository;    
   
    public function __construct(OprAssignAgentRepository $oprAssignAgentRepository)
    {
        $this->oprAssignAgentRepository = $oprAssignAgentRepository;
    }       

    public function getAllAssoc()
    {
        return $this->oprAssignAgentRepository->getAllAssoc();
    }     

    // public function getAllAssignAgent($request)
    // {
    //     return $this->oprAssignAgentRepository->getAllAssignAgent($request);
    // } 

    // public function addAssignAgent($request)
    // {
    //     return $this->oprAssignAgentRepository->addAssignAgent($request);
    // }

    // public function deleteOprAssignAgent($request)
    // {
    //     return $this->oprAssignAgentRepository->deleteOprAssignAgent($request);
    // } 
   
    public function changeStatus($id)
    {
        return $this->oprAssignAgentRepository->changeStatus($id);
    }   

}