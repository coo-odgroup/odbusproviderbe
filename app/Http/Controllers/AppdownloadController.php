<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppDownload;
use App\Services\AppDownloadService;
use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\AppDownloadRepository;
use Illuminate\Support\Facades\Config;
use App\AppValidator\AppDownloadValidator;
use App\Traits\ApiResponser;


class AppDownloadController extends Controller
{
    use ApiResponser;
     /**
     * @var appDownloadService
     */
    protected $appDownloadService;
    protected $AppDownloadValidator;
    protected $appDownloadRepository;



    /**
     * PostController Constructor
     *
     * @param AppDownloadService $appDownloadService
     *
     */
    public function __construct(AppDownloadService $appDownloadService,
                                   AppDownloadValidator $AppDownloadValidator,
                                   AppDownloadRepository $appDownloadRepository)
    {
        $this->appDownloadService = $appDownloadService;
        $this->AppDownloadValidator = $AppDownloadValidator;
        $this->appDownloadRepository = $appDownloadRepository;
    }



    public function getAllAppDownload() {
        //$prod = $this->appDownloadService->getAll();
        $prod = $this->appDownloadRepository->getAll();
        return $this->successResponse($prod,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function createAppDownload(Request $request) {
        $data = $request->only([
          'mobileno',
          
        ]);
       
        $appDownloadValidation = $this->AppDownloadValidator->validate($data);
        
        if ($appDownloadValidation->fails()) {
          $errors = $appDownloadValidation->errors();
          return $errors->toJson();
        }    
        try {
          //$this->appDownloadService->savePostData($data);
            $this->appDownloadRepository->save($data);
          return $this->successResponse(null, Config::get('constants.RECORD_ADDED'), Response::HTTP_CREATED);
        }
          catch(Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }	
	
    } 

    public function updateAppDownload(Request $request, $id) {
        $data = $request->only([
          'mobileno',
          
        ]);
       
        $appDownloadValidation = $this->AppDownloadValidator->validate($data);
        
        if ($appDownloadValidation->fails()) {
          $errors = $appDownloadValidation->errors();
          return $errors->toJson();
        }
    
        
        try {
          //$this->appDownloadService->updatePost($data, $id);
          $this->appDownloadRepository->update($data, $id);
          return $this->successResponse(null, Config::get('constants.RECORD_UPDATED'), Response::HTTP_CREATED);
        }
          catch(Exception $e){
             DB::rollBack();
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }      
        
    }

    public function deleteAppDownload ($id) {
      try{
        //$this->appDownloadService->deleteById($id);
        $this->appDownloadRepository->update($data, $id);
         
      }
      catch (Exception $e){
        DB::rollBack();
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(null, Config::get('constants.RECORD_REMOVED'), Response::HTTP_ACCEPTED);
    }

    public function getAppDownload($id) {
      try {
        //$ame= $this->appDownloadService->getById($id);
        $ame = $this->appDownloadRepository->getById($id);
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse($ame, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
  	}
}
