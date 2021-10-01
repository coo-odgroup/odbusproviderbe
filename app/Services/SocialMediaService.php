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
    
    public function getAll()
    {
        return $this->socialmediaRepository->getAll();
    }
    public function updateData($request)
    {
        return $this->socialmediaRepository->updateData($request);
    }

}