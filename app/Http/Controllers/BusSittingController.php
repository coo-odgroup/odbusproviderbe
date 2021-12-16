<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusSitting;
use App\Services\BusSittingService;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use InvalidArgumentException;
use App\AppValidator\BusSittingValidator;
use Symfony\Component\HttpFoundation\Response;

class BusSittingController extends Controller
{
    use ApiResponser;
    protected $busSittingService;
    protected $busSittingValidator;
    /**
     * PostController Constructor
     *
     * @param BusSittingService $busSittingService
     *
     */
    public function __construct(BusSittingService $busSittingService,BusSittingValidator $busSittingValidator)
    {
        $this->busSittingService = $busSittingService;
        $this->busSittingValidator = $busSittingValidator;
    }



    public function getAllBusSitting(Request $request) {
        $busSitting = $this->busSittingService->getAll($request);
        return $this->successResponse($busSitting,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    } 

    public function BusSittingData(Request $request) {
        $busSitting = $this->busSittingService->BusSittingData($request);
        return $this->successResponse($busSitting,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }

    public function createBusSitting(Request $request) {
        $data = $request->only([
          'name','created_by','user_id'
          
        ]);
        
        $busSittingValidation = $this->busSittingValidator->validate($data);
        
        if ($busSittingValidation->fails()) {
          $errors = $busSittingValidation->errors();
          // return $errors->toJson();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
          $this->busSittingService->savePostData($data);
          
      }
       catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }  
      return $this->successResponse($data,"Sitting Type Added",Response::HTTP_CREATED); 
      
    } 

    public function updateBusSitting(Request $request, $id) {
        $data = $request->only([
          'name','created_by','user_id'
        ]);
        
        $busSittingValidation =   $this->busSittingValidator->validate($data);
        
        if ($busSittingValidation->fails()) {
          $errors = $busSittingValidation->errors();
          // return $errors->toJson();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }

        try {
          $this->busSittingService->update($data, $id);
          
        }
         catch (Exception $e) {
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($data,"Sitting Type Updated",Response::HTTP_CREATED);     
    }

    public function deleteBusSitting ($id) {
      try {
        $this->busSittingService->deleteById($id);
        
      } 
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),"404");
      }
      return $this->successResponse(Null,"Sitting Type Deleted",Response::HTTP_ACCEPTED);
    }

    public function getBusSitting($id) {
      try {
        $busSittingID= $this->busSittingService->getById($id);
        
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
      return $this->successResponse($busSittingID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
   
    public function getBusSittingDT(Request $request) {      
        
      $busSitting = $this->busSittingService->getAllBusSittingDT($request);
      return $this->successResponse($busSitting,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
      
    }

    public function changeStatus ($id) {
      try{
        $this->busSittingService->changeStatus($id);
      }
      catch (Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(null, "Sitting Type Status Updated", Response::HTTP_ACCEPTED);
    }
}
