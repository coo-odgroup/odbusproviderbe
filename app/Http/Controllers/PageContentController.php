<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Services\PageContentService;
use App\Repositories\PageContentRepository;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\AppValidator\PageContentValidator;


class PageContentController extends Controller
{
    use ApiResponser;
   
    //protected $pagecontentService;
    protected $pagecontentValidator;   
    protected $pagecontentRepository;
    
    
    public function __construct(//PageContentService $pagecontentService, 
                             PageContentValidator $pagecontentValidator,
                             PageContentRepository $pagecontentRepository)
    {
       // $this->pagecontentService = $pagecontentService;
        $this->pagecontentValidator = $pagecontentValidator; 
        $this->pagecontentRepository = $pagecontentRepository;               
    }

    // public function getAllpagecontent()
    // {

    //     $pagecontent = $this->pagecontentService->getAll();
    //     return $this->successResponse($pagecontent,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }
     public function getAllpagecontent()
    {

        $pagecontent = $this->pagecontentRepository->getAll();
        return $this->successResponse($pagecontent,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    // public function getAllData(Request $request)
    // {

    //     $pagecontent = $this->pagecontentService->getAllData($request);
    //     return $this->successResponse($pagecontent,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }

      public function getAllData(Request $request)
      {
  
          $pagecontent = $this->pagecontentRepository->getAllData($request);
          return $this->successResponse($pagecontent,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
      }

     public function addpagecontent(Request $request)
     {
     	 $data = $request->only([
          'page_name',
          'user_id',
          'page_url',
          'page_description',
          'meta_title',
          'meta_keyword',
          'meta_description',
          'extra_meta',
          'canonical_url'
        ]);

    	 $pagecontent = $this->pagecontentValidator->validate($data);


      if ($pagecontent->fails()) {
        $errors = $pagecontent->errors();
        return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      }      
      try {
        $this->pagecontentService->addpagecontent($request);
        return $this->successResponse(null,"Page Content Added", Response::HTTP_CREATED);
      }
      catch(Exception $e){
      	// Log::info($e);
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }  

     }
     public function updatepagecontent(Request $request , $id)
     {

     	 $data = $request->only([
          'page_name',
          'user_id',
          'page_url',
          'page_description',
          'meta_title',
          'meta_keyword',
          'meta_description',
          'extra_meta',
          'canonical_url'
        ]);

    	 $pagecontent = $this->pagecontentValidator->validate($data);


      if ($pagecontent->fails()) {
        $errors = $pagecontent->errors();
        return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      }      
      try {
        $this->pagecontentRepository->updatepagecontent($request,$id);
        return $this->successResponse(null,"Page Content Updated", Response::HTTP_CREATED);
      }
      catch(Exception $e){
      	// Log::info($e);
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }  

     }

    //  public function deletepagecontent($id)
    //  {
    //  	$pagecontent = $this->pagecontentService->deletepagecontent($id);
    //     return $this->successResponse($pagecontent,"Page Content Deleted",Response::HTTP_OK);

    //  }
      public function deletepagecontent($id)
      {
      	$pagecontent = $this->pagecontentRepository->deletepagecontent($id);
          return $this->successResponse($pagecontent,"Page Content Deleted",Response::HTTP_OK);
  
      }

     
    
     

}