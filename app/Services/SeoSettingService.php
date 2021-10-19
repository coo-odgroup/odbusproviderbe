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

    public function addseosetting($request)
    {
        return $this->seosettingRepository->addseosetting($request);
    } 

    public function seosettingData($request)
    {
        return $this->seosettingRepository->seosettingData($request);
    } 
    public function updateseosetting($request,$id)
    {
        return $this->seosettingRepository->updateseosetting($request,$id);
    }
    public function deleteseosetting($id)
    {
        return $this->seosettingRepository->deleteseosetting($id);
    }   
    public function changeStatusseosetting($id)
    {
        return $this->seosettingRepository->changeStatusseosetting($id);
    }   

}