<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusOwnerFare;
use App\Models\Bus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Services\FestivalFareService;
use App\Traits\ApiResponser;
use Exception;
use InvalidArgumentException;
use App\AppValidator\FestivalFareValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class FestivalFareController extends Controller
{
    use ApiResponser;
    protected $festivalFareService;
    protected $festivalFareValidator;
    
    public function __construct(FestivalFareService $festivalFareService, FestivalFareValidator $festivalFareValidator)
    {
        $this->festivalFareService = $festivalFareService;
        $this->festivalFareValidator = $festivalFareValidator;
    }

    public function getAllFestivalFare() {

        $busOwnerFare = $this->festivalFareService->getAll();
        return $this->successResponse($busOwnerFare,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function getFestivalFareDT(Request $request) {      

        $busOwnerFare = $this->festivalFareService->dataTable($request);
        return $this->successResponse($busOwnerFare,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function festivalFareData(Request $request) {      

        $busOwnerFare = $this->festivalFareService->festivalFareData($request);
        return $this->successResponse($busOwnerFare,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
      }

      public function createFestivalFare(Request $request) {

        $data = $request->only([
        
          'date','seater_price','sleeper_price','reason','created_by','operator_id','bus_id' 
        ]);

        $busOwnerFareValidation = $this->festivalFareValidator->validate($data);
        if ($busOwnerFareValidation->fails()) {
            $errors = $busOwnerFareValidation->errors();
            
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
          }
        try {
           $this->festivalFareService->savePostData($request);

        } catch (Exception $e) {
           return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
      return $this->successResponse($data,"Bus Festival Fare Added",Response::HTTP_CREATED); 
    } 


    public function updateFestivalFare(Request $request, $id) {
      $data = $request->only(['date','bus_operator_id','source_id','destination_id','seater_price','sleeper_price','reason','created_by',
      ]);
        $busOwnerFareValidation = $this->festivalFareValidator->validate($data);
        if ($busOwnerFareValidation->fails()) {
            $errors = $busOwnerFareValidation->errors();
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
          $data = $request->only(['date','bus_operator_id','source_id','destination_id','seater_price','sleeper_price','reason','created_by','bus_id',
          ]);
          $this->festivalFareService->updatePost($data, $id);
          return $this->successResponse($data, "Bus Festival Fare Updated",Response::HTTP_CREATED);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
        }
    }

    public function deleteFestivalFare($id) {
        try {
            $this->festivalFareService->deleteById($id);
          }
          catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
          }
          return $this->successResponse(null, "Bus Festival Fare Deleted", Response::HTTP_ACCEPTED);
    }

    public function getFestivalFare($id) {
        try {
            $busOwnerFareID= $this->festivalFareService->getById($id);
          }
          catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
          }
          return $this->successResponse($busOwnerFareID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }      
    public function changeStatus($id) {
        try{
          $this->festivalFareService->changeStatus($id);
        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, "Festival Fare Status Updated", Response::HTTP_ACCEPTED);
      }
	     
}
