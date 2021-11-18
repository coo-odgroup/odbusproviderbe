<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Amenities;
use App\Services\AmenitiesService;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use App\AppValidator\AmenitiesValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class AmenitiesController extends Controller
{
    use ApiResponser;
    /**
     * @var amenitiesService
     */
    protected $amenitiesService;
    protected $AmenitiesValidator;
    /**
     * PostController Constructor
     *
     * @param AmenitiesService $busTypeService
     *
     */
    public function __construct(AmenitiesService $amenitiesService, AmenitiesValidator $AmenitiesValidator)
    {
        $this->amenitiesService = $amenitiesService;
        $this->AmenitiesValidator=$AmenitiesValidator;
    }
    public function getAll() {
      $amenty = $this->amenitiesService->getAll();;
      return $this->successResponse($amenty,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
  }

    public function getAllAmenitiesDT(Request $request)
    {
      $amenities = $this->amenitiesService->dataTable($request);
      return $this->successResponse($amenities,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 
    public function AmenitiesData(Request $request)
    {
      $amenities = $this->amenitiesService->AmenitiesData($request);
      return $this->successResponse($amenities,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    
    public function createAmenities(Request $request) {
        $data = $request->only([
          'name',
          'icon',
          'created_by',
        ]);
        $AmenitiesValidation = $this->AmenitiesValidator->validate($data);
        if ($AmenitiesValidation->fails()) {
          $errors = $AmenitiesValidation->errors();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
          $response = $this->amenitiesService->savePostData($data);
          return $this->successResponse($response, "Amenities Added", Response::HTTP_CREATED);
      }
      catch(Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }	
    } 

    public function updateAmenities(Request $request) {
      
        $data = $request->only([
          'id',
          'name',
          'icon',
          'created_by'
        ]);
        $amenitiesValidation = $this->AmenitiesValidator->validate($data);
        try {
          $response = $this->amenitiesService->updatePost($data);
          return $this->successResponse($response, "Amenities Updated", Response::HTTP_CREATED);

      } catch (Exception $e) {
          return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
    }

    public function deleteAmenities ($id) {
      
      try{
        $response = $this->amenitiesService->deleteById($id);
        return $this->successResponse($response,  "Amenities Deleted", Response::HTTP_ACCEPTED);
      }
      catch (Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      } 
    }

    public function getAmenities($id) { 
      try{
        $amenity= $this->amenitiesService->getById($id);
      }
      catch (Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse($amenity, Config::get('constants.RECORD_FETCHED'), Response::HTTP_ACCEPTED);
    }
    
    public function changeStatus (Request $request,$id) {
    
      $data = $request->only([
        'reason'
      ]);
      try{
        $response = $this->amenitiesService->changeStatus($data,$id);
        return $this->successResponse($response,  "Amenities Status Updated", Response::HTTP_ACCEPTED);
      }
      catch (Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
     
    }
}
