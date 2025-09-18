<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Services\FaqService;
use App\Repositories\FaqRepository;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\AppValidator\FaqValidator;


class FaqController extends Controller
{
    use ApiResponser;
   
    //protected $faqService;
    protected $faqValidator; 
    protected $faqRepository;  
    
    
    public function __construct(//FaqService $faqService, 
                                FaqValidator $faqValidator,
                                FaqRepository $faqRepository)
    {
        //$this->faqService = $faqService;
        $this->faqValidator = $faqValidator;  
        $this->faqRepository = $faqRepository;              
    }

    // public function getAllfaq()
    // {

    //     $faq = $this->faqService->getAll();
    //     return $this->successResponse($faq,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }

     public function getAllfaq()
    {

        $faq = $this->$this->faqRepository->getAll();
        return $this->successResponse($faq,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    // public function getAllData(Request $request)
    // {

    //     $faq = $this->faqService->getAllData($request);
    //     return $this->successResponse($faq,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }
      public function getAllData(Request $request)
      {
  
          $faq = $this->faqRepository->getAllData($request);
          return $this->successResponse($faq,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
      }

     public function addfaq(Request $request)
     {
     	 $data = $request->only([
          'title',
          'content'
        ]);

    	 $faq = $this->faqValidator->validate($data);


      if ($faq->fails()) {
        $errors = $faq->errors();
        return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      }      
      try {
        //$this->faqService->addfaq($request);
        $this->faqRepository->addfaq($request);
        return $this->successResponse(null,"FAQ Added", Response::HTTP_CREATED);
      }
      catch(Exception $e){
      	// Log::info($e);
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }  

     }
     public function updatefaq(Request $request , $id)
     {

     	 $data = $request->only([
          'title',
          'content'
        ]);

    	 $faq = $this->faqValidator->validate($data);


      if ($faq->fails()) {
        $errors = $faq->errors();
        return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      }      
      try {
       // $this->faqService->updatefaq($request, $id);
       $this->faqRepository->updatefaq($request,$id);
        return $this->successResponse(null,"FAQ Updated", Response::HTTP_CREATED);
      }
      catch(Exception $e){
      	// Log::info($e);
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }  

     }

    //  public function deletefaq($id)
    //  {
    //  	$faq = $this->faqService->deletefaq($id);
    //     return $this->successResponse($faq,"FAQ Deleted",Response::HTTP_OK);

    //  }

    public function deletefaq($id)
     {
     //	$faq = $this->faqService->deletefaq($id);
     $faq = $this->faqRepository->deletefaq($id);
        return $this->successResponse($faq,"FAQ Deleted",Response::HTTP_OK);

     }
     //public function changeStatus($id)
    //  {
    //  	$faq = $this->faqService->changeStatus($id);
    //     return $this->successResponse($faq,"Status Updated",Response::HTTP_OK);

    //  }
    public function changeStatus($id)
     {
     $faq = $this->faqRepository->changeStatus($id);
        return $this->successResponse($faq,"Status Updated",Response::HTTP_OK);

     }
    

     
    
     

}