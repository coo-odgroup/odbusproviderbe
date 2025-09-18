<?php

namespace App\Services;


use App\Repositories\FaqRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class FaqService
{
    protected $faqRepository;
   
    public function __construct(FaqRepository $faqRepository)
    {
        $this->faqRepository = $faqRepository;
    }
    
//     public function getAll()
//     {      
//         return $this->faqRepository->getAll();
//     }

//     public function getAllData($request)
//     {
//         return $this->faqRepository->getAllData($request);
//     }

//     public function addfaq($request)
//     {
//         return $this->faqRepository->addfaq($request);
//     } 
//     public function updatefaq($request,$id)
//     {
//         return $this->faqRepository->updatefaq($request,$id);
//     }
//     public function deletefaq($id)
//     {
//         return $this->faqRepository->deletefaq($id);
//     }   

//     public function changeStatus($id)
//     {
//         return $this->faqRepository->changeStatus($id);
//     }   

 }