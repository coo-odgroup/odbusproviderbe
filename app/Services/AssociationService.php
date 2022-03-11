<?php

namespace App\Services;


use App\Repositories\AssociationRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class AssociationService
{
    protected $AssociationRepository;

    
   
    public function __construct(AssociationRepository $AssociationRepository)
    {
        $this->AssociationRepository = $AssociationRepository;
    }
    
    public function getAllData($request)
    {
        return $this->AssociationRepository->getAllData($request);
    }

    public function getAllAssoc()
    {
        return $this->AssociationRepository->getAllAssoc();
    }

    public function getAllAgent()
    {
        return $this->AssociationRepository->getAllAgent();
    }

    public function getAllUserOperator()
    {
        return $this->AssociationRepository->getAllUserOperator();
    }

    public function addusercontent($request)
    {
        return $this->AssociationRepository->addusercontent($request);
    } 
    public function updateusercontent($request,$id)
    {
        return $this->AssociationRepository->updateusercontent($request,$id);
    } 
    public function changePassword($request,$id)
    {
        return $this->AssociationRepository->changePassword($request,$id);
    }
    public function deleteusercontent($id)
    {
        return $this->AssociationRepository->deleteusercontent($id);
    } 
     public function changeStatus($id)
    {
        return $this->AssociationRepository->changeStatus($id);
    } 
  

}