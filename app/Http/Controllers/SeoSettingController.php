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

        $seosetting = $this->seosettingService->getAll();
        return $this->successResponse($seosetting,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function seosettingData(Request $request)
    {

        $seosetting = $this->seosettingService->seosettingData($request);
        return $this->successResponse($seosetting,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

     public function addseosetting(Request $request)
     {
     	 $data = $request->only([
          'page_url',
          'bus_operator_id',
          'url_description',
          'meta_title',
          'meta_keyword',
          'meta_description',
          'extra_meta',
          'canonical_url'
        ]);

    	 $seosetting = $this->seosettingValidator->validate($data);


      if ($seosetting->fails()) {
        $errors = $seosetting->errors();
        return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      }      
      try {
        $this->seosettingService->addseosetting($request);
        return $this->successResponse(null, Config::get('constants.RECORD_ADDED'), Response::HTTP_CREATED);
      }
      catch(Exception $e){
      	// Log::info($e);
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }  

     }
     public function updateseosetting(Request $request , $id)
     {

     	 $data = $request->only([
          'page_url', 'bus_operator_id',
          'url_description',
          'meta_title',
          'meta_keyword',
          'meta_description',
          'extra_meta',
          'canonical_url'
        ]);

    	 $seosetting = $this->seosettingValidator->validate($data);


      if ($seosetting->fails()) {
        $errors = $seosetting->errors();
        return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      }      
      try {
        $this->seosettingService->updateseosetting($request, $id);
        return $this->successResponse(null, Config::get('constants.RECORD_UPDATED'), Response::HTTP_CREATED);
      }
      catch(Exception $e){
      	// Log::info($e);
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }  

     }

     public function deleteseosetting($id)
     {
     	$seosetting = $this->seosettingService->deleteseosetting($id);
        return $this->successResponse($seosetting,Config::get('constants.RECORD_UPDATED'),Response::HTTP_OK);

     }
     public function changeStatusseosetting($id)
     {
      $seosetting = $this->seosettingService->changeStatusseosetting($id);
        return $this->successResponse($seosetting,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);

     }

     
    
     

}