<?php

namespace App\Services;


use App\Repositories\AssociationAssignOperatorRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class AssociationAssignOperatorService
{
    protected $AssociationAssignOperatorRepository;

    
   
    public function __construct(AssociationAssignOperatorRepository $AssociationAssignOperatorRepository)
    {
        $this->AssociationAssignOperatorRepository = $AssociationAssignOperatorRepository;
    }
    
    public function getAllData($request)
    {
        return $this->AssociationAssignOperatorRepository->getAllData($request);
    }

    public function getAllAssoc()
    {
        return $this->AssociationAssignOperatorRepository->getAllAssoc();
    }

    public function addusercontent($request)
    {
        return $this->AssociationAssignOperatorRepository->addusercontent($request);
    }  




    public function getAllAssignOperator($request)
    {
        return $this->AssociationAssignOperatorRepository->getAllAssignOperator($request);
    } 

    public function addAssignOperator($request)
    {
        return $this->AssociationAssignOperatorRepository->addAssignOperator($request);
    }

      public function deleteassocAssignOperator($request)
    {
        return $this->AssociationAssignOperatorRepository->deleteassocAssignOperator($request);
    } 





    public function updateusercontent($request,$id)
    {
        return $this->AssociationAssignOperatorRepository->updateusercontent($request,$id);
    } 
    public function changePassword($request,$id)
    {
        return $this->AssociationAssignOperatorRepository->changePassword($request,$id);
    }
    public function deleteusercontent($id)
    {
        return $this->AssociationAssignOperatorRepository->deleteusercontent($id);
    } 
     public function changeStatus($id)
    {
        return $this->AssociationAssignOperatorRepository->changeStatus($id);
    } 
  

}