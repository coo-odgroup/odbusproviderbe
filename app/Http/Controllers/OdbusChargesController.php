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
    public function getAll() {
      $result = $this->odbusChargesService->getAll();;
      return $this->successResponse($result,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
   
    
    
    public function save(Request $request) {
        $data = $request->only([
          'payment_gateway_charges','email_sms_charges','odbus_gst_charges'
        ]);
        $odbusChargesValidation = $this->odbusChargesValidator->validate($data);
        if ($odbusChargesValidation->fails()) {
          $errors = $odbusChargesValidation->errors();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
          $response = $this->odbusChargesService->savePostData($data);
          return $this->successResponse($response, Config::get('constants.RECORD_ADDED'), Response::HTTP_CREATED);
      }
      catch(Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }	
    } 

    public function update(Request $request, $id) {
        $data = $request->only([
          'payment_gateway_charges','email_sms_charges','odbus_gst_charges','advance_days_show','support_email','booking_email','request_email','other_email','mobile_no_1','mobile_no_2','mobile_no_3','mobile_no_4','created_by'
        ]);
        $odbusChargesValidation = $this->odbusChargesValidator->validate($data);
        if ($odbusChargesValidation->fails()) {
          $errors = $odbusChargesValidation->errors();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
          $response = $this->odbusChargesService->updatePost($data, $id);
          return $this->successResponse($response, Config::get('constants.RECORD_UPDATED'), Response::HTTP_CREATED);

      } catch (Exception $e) {
          return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
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
    
   
}
