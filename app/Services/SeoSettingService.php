<?php

namespace App\Services;


use App\Repositories\SeoSettingRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class SeoSettingService
{
    protected $seosettingRepository;

    
   
    public function __construct(SeoSettingRepository $seosettingRepository)
    {
        $this->seosettingRepository = $seosettingRepository;
    }
    
    public function getAll()
    {      
        return $this->seosettingRepository->getAll();
    }

    public function addpagecontent($request)
    {
        return $this->seosettingRepository->addpagecontent($request);
    } 
    public function updatepagecontent($request,$id)
    {
        return $this->seosettingRepository->updatepagecontent($request,$id);
    }
    public function deletepagecontent($id)
    {
        return $this->seosettingRepository->deletepagecontent($id);
    }   

}