<?php

namespace App\Services;


use App\Repositories\SocialMediaRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class SocialMediaService
{
    protected $socialmediaRepository;

    
   
    public function __construct(SocialMediaRepository $socialmediaRepository)
    {
        $this->socialmediaRepository = $socialmediaRepository;
    }      
    
    public function getAll($request)
    {      
        return $this->socialmediaRepository->getAll($request);
    }

    public function addsocialmedia($request)
    {
        return $this->socialmediaRepository->addsocialMedia($request);
    } 
    public function updatesocialmedia($request,$id)
    {
        return $this->socialmediaRepository->updatesocialMedia($request,$id);
    }
    public function deletesocialmedia($id)
    {
        return $this->socialmediaRepository->deletesocialMedia($id);
    } 
    public function changeStatus($id)
    {
        return $this->socialmediaRepository->changeStatus($id);
    } 


}