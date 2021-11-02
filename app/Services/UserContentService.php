<?php

namespace App\Services;


use App\Repositories\UserContentRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class UserContentService
{
    protected $userContentRepository;

    
   
    public function __construct(UserContentRepository $userContentRepository)
    {
        $this->userContentRepository = $userContentRepository;
    }
    
    public function getAllData($request)
    {
        return $this->userContentRepository->getAllData($request);
    }

    public function addusercontent($request)
    {
        return $this->userContentRepository->addusercontent($request);
    } 
    public function updateusercontent($request,$id)
    {
        return $this->userContentRepository->updateusercontent($request,$id);
    } 
    public function changePassword($request,$id)
    {
        return $this->userContentRepository->changePassword($request,$id);
    }
    public function deleteusercontent($id)
    {
        return $this->userContentRepository->deleteusercontent($id);
    } 
     public function changeStatus($id)
    {
        return $this->userContentRepository->changeStatus($id);
    } 
  

}