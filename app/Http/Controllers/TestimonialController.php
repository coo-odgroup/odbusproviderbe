<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TestimonialService;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\AppValidator\TestimonialValidator;


class TestimonialController extends Controller
{
    use ApiResponser;
   
    protected $testimonialService;
    protected $testimonialValidator;   
    
    
    public function __construct(TestimonialService $testimonialService, TestimonialValidator $testimonialValidator)
    {
        $this->testimonialService = $testimonialService;
        $this->testimonialValidator = $testimonialValidator;                
    }

    public function getAlltestimonial(Request $request)
    {
      // Log::info($request);
      
        $testimonial = $this->testimonialService->getAll($request);
        return $this->successResponse($testimonial,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

     public function addtestimonial(Request $request)
     {
     	 $data = $request->only([
            'posted_by',
            'testinmonial_content',
            'travel_date',
            'operator',
            'destination',
            'source',
            'designation'
        ]); 

    	 $testimonial = $this->testimonialValidator->validate($data);


      if ($testimonial->fails()) {
        $errors = $testimonial->errors();
        return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      }      
      try {
        $this->testimonialService->addtestimonial($request);
        return $this->successResponse(null, Config::get('constants.RECORD_ADDED'), Response::HTTP_CREATED);
      }
      catch(Exception $e){
      	// Log::info($e);
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }  

     }
     public function updatetestimonial(Request $request , $id)
     {

     	 $data = $request->only([
             'posted_by',
            'testinmonial_content',
            'travel_date',
            'operator',
            'destination',
            'source',
            'designation'
        ]);

    	 $testimonial = $this->testimonialValidator->validate($data);


      if ($testimonial->fails()) {
        $errors = $testimonial->errors();
        return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      }      
      try {
        $this->testimonialService->updatetestimonial($request, $id);
        return $this->successResponse(null, Config::get('constants.RECORD_UPDATED'), Response::HTTP_CREATED);
      }
      catch(Exception $e){
      	// Log::info($e);
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }  

     }

     public function deletetestimonial($id)
     {

     	$testimonial = $this->testimonialService->deletetestimonial($id);
        return $this->successResponse($testimonial,Config::get('constants.RECORD_REMOVED'),Response::HTTP_OK);

     } 
     public function changeStatus($id)
     {
      $testimonial = $this->testimonialService->changeStatus($id);
        return $this->successResponse($testimonial,Config::get('constants.RECORD_UPDATED'),Response::HTTP_OK);

     }

     
    
     

}