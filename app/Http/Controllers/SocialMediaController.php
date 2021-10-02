<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SocialMediaService;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\AppValidator\SocialMediaValidator;



class SocialMediaController extends Controller
{
    use ApiResponser;
   
    protected $socialmediaService;    
    protected $socialmediaValidator;    
    
    public function __construct(SocialMediaService $socialmediaService,SocialMediaValidator $socialmediaValidator)
    {
        $this->socialmediaService = $socialmediaService;        
        $this->socialmediaValidator = $socialmediaValidator;        
    }

    public function getAll()
    {
        $extraseatopen = $this->socialmediaService->getAll();
        return $this->successResponse($extraseatopen,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function updateData(Request $request)
    {
    	// Log::info($request);
        $data = $request->only([
          'facebook_link',
          'twitter_link',
          'instagram_link',
          'googleplus_link',
          'linkedin_link',
        ]);

    	 $socialmediaValidation = $this->socialmediaValidator->validate($data);


      if ($socialmediaValidation->fails()) {
        $errors = $socialmediaValidation->errors();
        return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      }      
      try {
        $this->socialmediaService->updateData($request);
        return $this->successResponse(null, Config::get('constants.RECORD_UPDATED'), Response::HTTP_CREATED);
      }
      catch(Exception $e){
      	// Log::info($e);
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }  
    }

}