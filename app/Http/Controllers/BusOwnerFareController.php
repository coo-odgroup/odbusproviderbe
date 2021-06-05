<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusOwnerFare;
use App\Models\Bus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Services\BusOwnerFareService;
use App\Traits\ApiResponser;
use Exception;
use InvalidArgumentException;
use App\AppValidator\BusOwnerFareValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class BusOwnerFareController extends Controller
{
    use ApiResponser;
    protected $BusOwnerFareService;
    protected $BusOwnerFareValidator;
    
    public function __construct(BusOwnerFareService $busOwnerFareService, BusOwnerFareValidator $busOwnerFareValidator)
    {
        $this->busOwnerFareService = $busOwnerFareService;
        $this->busOwnerFareValidator = $busOwnerFareValidator;
    }

    public function getAllBusOwnerFare() {

        $busOwnerFare = $this->busOwnerFareService->getAll();
        return $this->successResponse($busOwnerFare,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function getBusOwnerFareDT(Request $request) {      

        $busOwnerFare = $this->busOwnerFareService->dataTable($request);
        return $this->successResponse($busOwnerFare,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
      }

      public function createBusOwnerFare(Request $request) {
        $data = $request->only([
        
          'date','seater_price','sleeper_price','reason','created_by','operator_id','bus_id' 
        ]);
        $busOwnerFareValidation = $this->busOwnerFareValidator->validate($data);
        if ($busOwnerFareValidation->fails()) {
            $errors = $busOwnerFareValidation->errors();
            
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
          }
        try {
           $this->busOwnerFareService->savePostData($request);

        } catch (Exception $e) {
           return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
      return $this->successResponse($data,Config::get('constants.RECORD_ADDED'),Response::HTTP_CREATED); 
    } 


    public function updateBusOwnerFare(Request $request, $id) {
      $data = $request->only(['date','bus_operator_id','source_id','destination_id','seater_price','sleeper_price','reason','created_by',
      ]);
        $busOwnerFareValidation = $this->busOwnerFareValidator->validate($data);
        if ($busOwnerFareValidation->fails()) {
            $errors = $busOwnerFareValidation->errors();
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
          $data = $request->only(['date','bus_operator_id','source_id','destination_id','seater_price','sleeper_price','reason','created_by','bus_id',
          ]);
          $this->busOwnerFareService->updatePost($data, $id);
          return $this->successResponse($data, Config::get('constants.RECORD_UPDATED'),Response::HTTP_CREATED);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
        }
    }

    public function deleteBusOwnerFare($id) {
        try {
            $this->busOwnerFareService->deleteById($id);
          }
          catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
          }
          return $this->successResponse(null, Config::get('constants.RECORD_REMOVED'), Response::HTTP_ACCEPTED);
    }

    public function getBusOwnerFare($id) {
        try {
            $busOwnerFareID= $this->busOwnerFareService->getById($id);
          }
          catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
          }
          return $this->successResponse($busOwnerFareID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }      
    public function changeStatus ($id) {
        try{
          $this->busOwnerFareService->changeStatus($id);
        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, Config::get('constants.RECORD_UPDATED'), Response::HTTP_ACCEPTED);
      }
	     
}
