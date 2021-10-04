<?php

namespace App\Services;


use App\Repositories\TestimonialRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class TestimonialService
{
    protected $testimonialRepository;

    
   
    public function __construct(TestimonialRepository $testimonialRepository)
    {
        $this->testimonialRepository = $testimonialRepository;
    }
    
    public function getAll($request)
    {      
        return $this->testimonialRepository->getAll($request);
    }

    public function addtestimonial($request)
    {
        return $this->testimonialRepository->addtestimonial($request);
    } 
    public function updatetestimonial($request,$id)
    {
        return $this->testimonialRepository->updatetestimonial($request,$id);
    }
    public function deletetestimonial($id)
    {
        return $this->testimonialRepository->deletetestimonial($id);
    } 
    public function changeStatus($id)
    {
        return $this->testimonialRepository->changeStatus($id);
    } 



   

}