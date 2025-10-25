<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OdbusChargesService;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use App\AppValidator\OdbusChargesValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class OdbusChargesController extends Controller
{
    use ApiResponser;
    /**
     * @var OdbusChargesService
     */
    protected $odbusChargesService;
    protected $odbusChargesValidator;

    /**
     * PostController Constructor
     *
     * @param odbusChargesService $busTypeService
     *
     */
    public function __construct(OdbusChargesService $odbusChargesService,OdbusChargesValidator $odbusChargesValidator)
    {
        $this->odbusChargesService = $odbusChargesService;
        $this->odbusChargesValidator = $odbusChargesValidator;
    }
    public function getData(Request $request)
    {
        $result = $this->odbusChargesService->getData($request);
        return $this->successResponse($result,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function getAll() {
      $result = $this->odbusChargesService->getAll();;
      return $this->successResponse($result,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
   
    public function save(Request $request) {
      
      // log::info($request);exit;
      
      $data = $request->all();
        $odbusChargesValidation = $this->odbusChargesValidator->validate($data);
        if ($odbusChargesValidation->fails()) {
          $errors = $odbusChargesValidation->errors();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
         else
        {
           $response = $this->odbusChargesService->savePostData($data);

           if($response=='User already taken')
           {
              return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
           }
           else
           {
               return $this->successResponse($response,"Master Settings Added", Response::HTTP_CREATED);
           }
        }
    } 

    public function update(Request $request) {
      $data = $request->all();

      $odbusChargesValidation = $this->odbusChargesValidator->validate($data);
      if ($odbusChargesValidation->fails()) {
        $errors = $odbusChargesValidation->errors();
        return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      }
      else
      {
        $response = $this->odbusChargesService->updatePost($request);

        if($response=='User already taken')
        {
          return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
        }
        else
        {
         return $this->successResponse($response,"Master Settings Updated", Response::HTTP_CREATED);
        }
      }
    }

    public function getById($id) { 
      try{
        $result= $this->odbusChargesService->getById($id);
      }
      catch (Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse($result, Config::get('constants.RECORD_FETCHED'), Response::HTTP_ACCEPTED);
    }
    public function delete($id) {
      try{
        $response = $this->odbusChargesService->deleteById($id);
        return $this->successResponse($response, "Master Settings Deleted", Response::HTTP_ACCEPTED);
      }
      catch (Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      } 
    } 
    public function changeStatus($id) {
      try{
        $response = $this->odbusChargesService->changeStatus($id);
        return $this->successResponse($response, "Master Settings Status Updated", Response::HTTP_ACCEPTED);
      }
      catch (Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
     
    }
    
    public function removePopup($id) {
      try{
        $response = $this->odbusChargesService->removePopup($id);
        return $this->successResponse($response, "PopUp Image Deleted", Response::HTTP_ACCEPTED);
      }
      catch (Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
     
    }  
   
}
