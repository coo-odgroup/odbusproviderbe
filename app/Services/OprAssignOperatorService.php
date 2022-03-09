<?php

namespace App\Services;

use App\Repositories\OprAssignOperatorRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class OprAssignOperatorService
{
    protected $OprAssignOperatorRepository;    
   
    public function __construct(OprAssignOperatorRepository $OprAssignOperatorRepository)
    {
        $this->OprAssignOperatorRepository = $OprAssignOperatorRepository;
    }
    
    public function getAllData($request)
    {
        return $this->OprAssignOperatorRepository->getAllData($request);
    }

    public function getAllAssoc()
    {
        return $this->OprAssignOperatorRepository->getAllAssoc();
    }

    public function addusercontent($request)
    {
        return $this->OprAssignOperatorRepository->addusercontent($request);
    }  

    public function getAllAssignOperator($request)
    {
        return $this->OprAssignOperatorRepository->getAllAssignOperator($request);
    } 

    public function addAssignOperator($request)
    {
        return $this->OprAssignOperatorRepository->addAssignOperator($request);
    }

    public function deleteOprAssignOperator($request)
    {
        return $this->OprAssignOperatorRepository->deleteOprAssignOperator($request);
    } 

    public function updateusercontent($request,$id)
    {
        return $this->OprAssignOperatorRepository->updateusercontent($request,$id);
    } 
    public function changePassword($request,$id)
    {
        return $this->OprAssignOperatorRepository->changePassword($request,$id);
    }
    public function deleteusercontent($id)
    {
        return $this->OprAssignOperatorRepository->deleteusercontent($id);
    } 
     public function changeStatus($id)
    {
        return $this->OprAssignOperatorRepository->changeStatus($id);
    }   

}