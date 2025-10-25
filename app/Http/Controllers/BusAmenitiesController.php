<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusAmenities;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use App\Services\BusAmenitiesService;
use Exception;
use InvalidArgumentException;
use App\AppValidator\BusAmenitiesValidator;
use Symfony\Component\HttpFoundation\Response;

class BusAmenitiesController extends Controller
{
    use ApiResponser;
    protected $busAmenitiesService;
    protected $busAmenitiesValidator;
    
    public function __construct(BusAmenitiesService $busAmenitiesService,BusAmenitiesValidator $busAmenitiesValidator)
    {
        $this->busAmenitiesService = $busAmenitiesService;
        $this->busAmenitiesValidator = $busAmenitiesValidator;
    }


    public function getAllBusAmenities(Request $request) {

        $busAmenities = $this->busAmenitiesService->getAll();
        return $this->successResponse($busAmenities,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function createBusAmenities(Request $request) {
        $data = $request->only([

            'bus_id', 
            'amenities_id',
            'created_by'
            
          ]);
          $busAmenitiesValidation = $this->busAmenitiesValidator->validate($data);

        if ($busAmenitiesValidation->fails()) {
            $errors = $busAmenitiesValidation->errors();
            // return $errors->toJson();
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
          }
      try {
          $this->busAmenitiesService->savePostData($data);
      } catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }

      return $this->successResponse($data,Config::get('constants.RECORD_ADDED'),Response::HTTP_CREATED); 
    } 

    public function updateBusAmenities(Request $request, $id) {
        $data = $request->only([
            'bus_id', 
            'amenities_id',
            'created_by'
        ]);
        
        $busAmenitiesValidation = $this->busAmenitiesValidator->validate($data);

        if ($busAmenitiesValidation->fails()) {
            $errors = $busAmenitiesValidation->errors();
            // return $errors->toJson();
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
          }

        try {
            $this->busAmenitiesService->updatePost($data, $id);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
        }

        return $this->successResponse($data,Config::get('constants.RECORD_UPDATED'),Response::HTTP_OK); 
    }

    public function deleteBusAmenities ($id) {
    
      try {
          $this->busAmenitiesService->deleteById($id);
      } catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
      return $this->successResponse(Null,Config::get('constants.RECORD_REMOVED'),Response::HTTP_ACCEPTED); 
    }

    public function getBusAmenities($id) {
        try{
            $busAmenitiesID=$this->busAmenitiesService->getById($id);
        }
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
          }
          return $this->successResponse($busAmenitiesID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
        }   
        
        
         ////data table//////
    public function getBusAmenitiesDT(Request $request) {      
        
        $busAmenities = $this->busAmenitiesService->getAllBusAmenitiesDT($request);
        return $this->successResponse($busAmenities,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
        
      }
	     
}
