<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Amenities;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use App\AppValidator\AmenitiesValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use App\Repositories\AmenitiesRepository;

class AmenitiesController extends Controller
{
    use ApiResponser;
    /**
     * @var amenitiesService
     */

    protected $AmenitiesValidator;
    protected $amenitiesRepository;
    /**
     * PostController Constructor
     *
     * @param AmenitiesService 
     *
     */
    public function __construct(AmenitiesRepository $amenitiesRepository, AmenitiesValidator $AmenitiesValidator)
    {
        
        $this->AmenitiesValidator = $AmenitiesValidator;
        $this->amenitiesRepository = $amenitiesRepository;
    }
    public function getAll()
    {
        
        $amenty = $this->amenitiesRepository->getAll();
        
        return $this->successResponse($amenty, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getAllAmenitiesDT(Request $request)
    {
        
        $amenities = $this->amenitiesRepository->getDatatable($request);
        return $this->successResponse($amenities, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }
    public function AmenitiesData(Request $request)
    {
       
        $amenities = $this->amenitiesRepository->getAmenitiesData($request);
        return $this->successResponse($amenities, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }
    public function AmenitiesbyUser(Request $request)
    {
   
        $amenities = $this->amenitiesRepository->AmenitiesbyUser($request);
        return $this->successResponse($amenities, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function createAmenities(Request $request)
    {

        $data = $request->only([
          'name',
          'icon',
          'created_by','android_image','user_id'
        ]);
        $AmenitiesValidation = $this->AmenitiesValidator->validate($data);

        if ($AmenitiesValidation->fails()) {
            $errors = $AmenitiesValidation->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        } else {
            
            $response = $this->amenitiesRepository->save($data);

            if ($response == 'Amenities Already Exist') {
                return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
            } else {
                return $this->successResponse($response, "Amenities Added", Response::HTTP_CREATED);
            }
        }
        
    }

    public function updateAmenities(Request $request)
    {

        $data = $request->only([
            'name',
            'icon',
            'created_by','id','android_image','user_id'
          ]);
        $AmenitiesValidation = $this->AmenitiesValidator->validate($data);

        if ($AmenitiesValidation->fails()) {
            $errors = $AmenitiesValidation->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        } else {
         
            $response = $this->amenitiesRepository->update($data);

            if ($response == 'Amenities Already Exist') {
                return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
            } else {
                return $this->successResponse($response, "Amenities Updated", Response::HTTP_CREATED);
            }

        }



    }

    public function deleteAmenities($id)
    {

        try {
            
            $response = $this->amenitiesRepository->delete($id);
            return $this->successResponse($response, "Amenities Deleted", Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function getAmenities($id)
    {
        try {
           
            $amenity = $this->amenitiesRepository->getById($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($amenity, Config::get('constants.RECORD_FETCHED'), Response::HTTP_ACCEPTED);
    }

    public function changeStatus(Request $request, $id)
    {

        $data = $request->only([
          'reason'
        ]);
        try {
            
            $response = $this->amenitiesRepository->changeStatus($data, $id);
            return $this->successResponse($response, "Amenities Status Updated", Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }

    }
}
