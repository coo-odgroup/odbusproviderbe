<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SeoSettingService;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\AppValidator\SeoSettingValidator;


class SeoSettingController extends Controller
{
    use ApiResponser;
   
    protected $seosettingService;
    protected $seosettingValidator;   
    
    
    public function __construct(SeoSettingService $seosettingService, SeoSettingValidator $seosettingValidator)
    {
        $this->seosettingService = $seosettingService;
        $this->seosettingValidator = $seosettingValidator;                
    }

    public function getAllseosetting()
    {

        $pagecontent = $this->seosettingService->getAll();
        return $this->successResponse($pagecontent,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

     public function addseosetting(Request $request)
     {
     	 $data = $request->only([
          'page_url',
          'meta_title',
          'meta_keyword',
          'meta_description',
          'extra_meta',
          'canonical_url'
        ]);

    	 $pagecontent = $this->seosettingValidator->validate($data);


      if ($pagecontent->fails()) {
        $errors = $pagecontent->errors();
        return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      }      
      try {
        $this->seosettingService->addpagecontent($request);
        return $this->successResponse(null, Config::get('constants.RECORD_UPDATED'), Response::HTTP_CREATED);
      }
      catch(Exception $e){
      	// Log::info($e);
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }  

     }
     public function updateseosetting(Request $request , $id)
     {

     	 $data = $request->only([
          'page_url',
          'meta_title',
          'meta_keyword',
          'meta_description',
          'extra_meta',
          'canonical_url'
        ]);

    	 $pagecontent = $this->seosettingValidator->validate($data);


      if ($pagecontent->fails()) {
        $errors = $pagecontent->errors();
        return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      }      
      try {
        $this->seosettingService->updatepagecontent($request, $id);
        return $this->successResponse(null, Config::get('constants.RECORD_UPDATED'), Response::HTTP_CREATED);
      }
      catch(Exception $e){
      	// Log::info($e);
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }  

     }

     public function deleteseosetting($id)
     {
     	$pagecontent = $this->seosettingService->deletepagecontent($id);
        return $this->successResponse($pagecontent,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);

     }

     
    
     

}