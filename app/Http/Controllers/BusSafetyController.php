<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusSafety;
use App\Services\BusSafetyService;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use App\AppValidator\BusSafetyValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class BusSafetyController extends Controller
{
    use ApiResponser;
    /**
     * @var busSafetyService
     */
    protected $busSafetyService;
    protected $busSafetyValidator;
    /**
     * PostController Constructor
     *
     * @param BusSafetyService $busTypeService
     *
     */
    public function __construct(BusSafetyService $busSafetyService, BusSafetyValidator $busSafetyValidator)
    {
        $this->busSafetyService = $busSafetyService;
        $this->busSafetyValidator=$busSafetyValidator;
    }
    public function getAll() {
      $result = $this->busSafetyService->getAll();;
      return $this->successResponse($result,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function getSafetyDT(Request $request)
    {
      $result = $this->busSafetyService->dataTable($request);
      return $this->successResponse($result,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    
    public function save(Request $request) {
        $data = $request->only([
          'name','created_by'
        ]);
        $busSafetyValidator = $this->safetyValidator->validate($data);
        if ($busSafetyValidator->fails()) {
          $errors = $busSafetyValidator->errors();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
          $response = $this->busSafetyService->savePostData($data);
          return $this->successResponse($response, Config::get('constants.RECORD_ADDED'), Response::HTTP_CREATED);
      }
      catch(Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }	
    } 

    public function update(Request $request, $id) {
      
        $data = $request->only([
          'name','created_by'
        ]);
        $busSafetyValidator = $this->safetyValidator->validate($data);
        try {
          $response = $this->busSafetyService->updatePost($data, $id);
          return $this->successResponse($response, Config::get('constants.RECORD_UPDATED'), Response::HTTP_CREATED);

      } catch (Exception $e) {
          return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
    }

    public function delete ($id) {
      
      try{
        $response = $this->busSafetyService->deleteById($id);
        return $this->successResponse($response, Config::get('constants.RECORD_REMOVED'), Response::HTTP_ACCEPTED);
      }
      catch (Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      } 
    }

    public function getById($id) { 
      try{
        $result= $this->busSafetyService->getById($id);
      }
      catch (Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse($result, Config::get('constants.RECORD_FETCHED'), Response::HTTP_ACCEPTED);
    }
    
    public function changeStatus (Request $request,$id) {
    
      $data = $request->only([
        'reason'
      ]);
      try{
        $response = $this->busSafetyService->changeStatus($data,$id);
        return $this->successResponse($response, Config::get('constants.RECORD_UPDATED'), Response::HTTP_ACCEPTED);
      }
      catch (Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
     
    }
}
