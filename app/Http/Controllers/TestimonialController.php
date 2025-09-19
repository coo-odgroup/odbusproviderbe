<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Services\TestimonialService;
use App\Repositories\TestimonialRepository;
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
   
    //protected $testimonialService;
    protected $testimonialValidator; 
    protected $testimonialRepository;  
    
    
    public function __construct(//TestimonialService $testimonialService, 
                                 TestimonialValidator $testimonialValidator,
                                TestimonialRepository $testimonialRepository
    )
    {
        $this->testimonialService = $testimonialService;
        $this->testimonialValidator = $testimonialValidator;   
        $this->testimonialRepository = $testimonialRepository;             
    }

    // public function getAlltestimonial(Request $request)
    // {
    //   // Log::info($request);
      
    //     $testimonial = $this->testimonialService->getAll($request);
    //     return $this->successResponse($testimonial,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }

    
     public function getAlltestimonial(Request $request)
    {
      // Log::info($request);
      
        $testimonial = $this->testimonialRepository->getAll($request);
        return $this->successResponse($testimonial,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
     public function addtestimonial(Request $request)
     {
      // log::info($request);exit;
     	 $data = $request->only([
            'posted_by',
            'testinmonial_content',
            'travel_date',
            'user_id',
            'destination',
            'source',
            'designation',
            'created_by'
        ]); 

    	 $testimonial = $this->testimonialValidator->validate($data);


      if ($testimonial->fails()) {
        $errors = $testimonial->errors();
        return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      }      
      try {
        //$this->testimonialService->addtestimonial($request);
        $this->testimonialRepository->addtestimonial($request);
        return $this->successResponse(null, "Testimonial Added", Response::HTTP_CREATED);
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
            'user_id',
            'destination',
            'source',
            'designation',
            'created_by'
        ]);

    	 $testimonial = $this->testimonialValidator->validate($data);


      if ($testimonial->fails()) {
        $errors = $testimonial->errors();
        return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      }      
      try {
        //$this->testimonialService->updatetestimonial($request, $id);
        $this->testimonialRepository->updatetestimonial($request, $id);
        return $this->successResponse(null,"Testimonial Updated", Response::HTTP_CREATED);
      }
      catch(Exception $e){
      	// Log::info($e);
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }  

     }

    //  public function deletetestimonial($id)
    //  {

    //  	$testimonial = $this->testimonialService->deletetestimonial($id);
    //     return $this->successResponse($testimonial,"Testimonial Deleted",Response::HTTP_OK);

    //  } 

    public function deletetestimonial($id)
     {

     	$testimonial = $this->testimonialRepository->deletetestimonial($id);
        return $this->successResponse($testimonial,"Testimonial Deleted",Response::HTTP_OK);

     } 
    //  public function changeStatus($id)
    //  {
    //   $testimonial = $this->testimonialService->changeStatus($id);
    //     return $this->successResponse($testimonial,"Testimonial Status Updated",Response::HTTP_OK);

    //  }
     public function changeStatus($id)
     {
      $testimonial = $this->testimonialRepository->changeStatus($id);
        return $this->successResponse($testimonial,"Testimonial Status Updated",Response::HTTP_OK);

     }

     
    
     

}