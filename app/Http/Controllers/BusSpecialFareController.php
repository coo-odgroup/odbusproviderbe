<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusSpecialFare;
use App\Models\Bus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Services\BusSpecialFareService;
use App\Repositories\BusSpecialFareRepository;
use App\Traits\ApiResponser;
use Exception;
use InvalidArgumentException;
use App\AppValidator\BusSpecialFareValidator;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BusSpecialFareController extends Controller
{
    use ApiResponser;
    protected $BusSpecialFareService;
    protected $BusSpecialFareValidator;
    protected $busSpecialFareRepository;
    
    public function __construct(BusSpecialFareService $busSpecialFareService,
                               BusSpecialFareValidator $busSpecialFareValidator,
                               BusSpecialFareRepository $busSpecialFareRepository)
    {
        $this->busSpecialFareService = $busSpecialFareService;
        $this->busSpecialFareValidator = $busSpecialFareValidator;
        $this->busSpecialFareRepository = $busSpecialFareRepository;
    }
    public function getAllBusSpecialFare() {
     //$busSpecialFare = $this->busSpecialFareService->getAll();
     $busSpecialFare = $this->busSpecialFareRepository->getAll();

        return $this->successResponse($busSpecialFare,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function getBusSpecialFareDT(Request $request) {
      $current_timestamp = Carbon::now()->timestamp/1000;
       //Log::info("getBusSpecialFareDT Start Time". $current_timestamp);
        $busSpecialFare = $this->busSpecialFareService->dataTable($request);
        $current_timestamp = Carbon::now()->timestamp/1000;
        //Log::info("getBusSpecialFareDT End Time". $current_timestamp);
         return $this->successResponse($busSpecialFare,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
      } 

    public function busSpecialFareData(Request $request) {
     
        // $busSpecialFare = $this->busSpecialFareService->busSpecialFareData($request);
        $busSpecialFare = $this->busSpecialFareRepository->busSpecialFareData($request);

         return $this->successResponse($busSpecialFare,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
      }



    public function createBusSpecialFare(Request $request) {
        $data = $request->only([
          'date','seater_price','sleeper_price','reason','created_by' 
        ]);
        $busSpecialFareValidation = $this->busSpecialFareValidator->validate($data);

        if ($busSpecialFareValidation->fails()) {
            $errors = $busSpecialFareValidation->errors();
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
          }
        try {
          //$response = $this->busSpecialFareService->savePostData($request);
          $response =  $this->busSpecialFareRepository->save($data);
          return $this->successResponse($response,"Bus Special Fare Added",Response::HTTP_CREATED); 
        } catch (Exception $e) {
           return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }   
    } 

    public function updateBusSpecialFare(Request $request, $id) {
        $data = $request->only(['date','bus_operator_id','source_id','destination_id','seater_price','sleeper_price','reason','created_by',
        ]);
        $busSpecialFareValidation = $this->busSpecialFareValidator->validate($data);
        if ($busSpecialFareValidation->fails()) {
            $errors = $busSpecialFareValidation->errors();
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
          $data = $request->only(['date','bus_operator_id','source_id','destination_id','seater_price','sleeper_price','reason','created_by','bus_id',
          ]);
          //$response = $this->busSpecialFareService->updatePost($data, $id)$this->busSpecialFareRepository->update($data, $id);
          $response = $this->busSpecialFareRepository->update($data, $id);
          return $this->successResponse($response, "Bus Special Fare Updated",Response::HTTP_CREATED); 
        }
        catch (Exception $e) {
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function deleteBusSpecialFare($id) {
        try {
          //$response = $this->busSpecialFareService->deleteById($id);
          $response = $this->busSpecialFareRepository->delete($id);
          }
          catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
          }
          return $this->successResponse($response, "Bus Special Fare Deleted", Response::HTTP_ACCEPTED);
    }

    public function getBusSpecialFare($id) {
        try {
            //$busSpecialFareID = $this->busSpecialFareService->getById($id);
             $busSpecialFareID =$this->busSpecialFareRepository->getById($id);
          }
          catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
          }
          return $this->successResponse($busSpecialFareID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }      
    public function changeStatus ($id) {
        try{
          //$response = $this->busSpecialFareService->changeStatus($id);
          $response = $this->busSpecialFareRepository->changeStatus($id);

        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($response, "Special Fare Status Updated", Response::HTTP_ACCEPTED);
      }
      public function getPivotData($id) {
        try {
            //$busSpecialFareID = $this->busSpecialFareService->getPivotData($id);
            $busSpecialFareID = $this->busSpecialFareRepository->getPivotData($id);


          }
          catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
          }
          return $this->successResponse($busSpecialFareID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }   	     
}
