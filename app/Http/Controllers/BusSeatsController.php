<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusSeats;
use Illuminate\Support\Facades\Validator;
use App\Services\BusSeatsService;
use Exception;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
class BusSeatsController extends Controller
{
    use ApiResponser;
    protected $busSeatsService;

    
    public function __construct(BusSeatsService $busSeatsService)
    {
        $this->busSeatsService = $busSeatsService;
    }


    public function getAllBusSeats() {

        $busSeats = $this->busSeatsService->getAll();
        $output ['status']=1;
        $output ['message']='All Data Fetched Successfully';
        $output ['result']=$busSeats;
        return response($output, 200);
    }
    public function getAllBusSeatsFare($busId) {

        $busSeats = $this->busSeatsService->getAllFare($busId);
        $output ['status']=1;
        $output ['message']='All Data Fetched Successfully';
        $output ['result']=$busSeats;
        return response($output, 200);
    }


    public function getByBusId($id) {

        $busSeats = $this->busSeatsService->getByBusId($id);
        return $this->successResponse($busSeats,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function createBusSeats(Request $request) {
        $data = $request->only([

            'bus_id', 'category','seat_type','seat_number','duration','created_by'
            
          ]);
        
          $busSeatsRules = [
            'bus_id' => 'required',
            'category' => 'required',
            'seat_type' => 'required',
            'seat_number' => 'required',
            'duration' => 'required',
            'created_by' => 'required',
            
        ];
        
        $busSeatsValidation = Validator::make($data, $busSeatsRules);


        if ($busSeatsValidation->fails()) {
            $errors = $busSeatsValidation->errors();
            return $errors->toJson();
          }
      $result = ['status' => 200];

      try {
          $result['data'] = $this->busSeatsService->savePostData($data);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }

      return response()->json($result, $result['status']);

    } 

    public function updateBusSeats(Request $request, $id) {
        try {
            $result=$this->busSeatsService->updatePost($request,$id);
        } 
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null,Config::get('constants.RECORD_UPDATED'),Response::HTTP_OK);
    }
    public function updateNewFare(Request $request)
    {
        try {
            $result=$this->busSeatsService->updateNewFare($request);
        } 
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null,"Bus Seat Fare Updated",Response::HTTP_OK);
    }
    public function updateBusSeatsExtras(Request $request, $id) {
      // Log::info($request);exit;
        try {
            $result=$this->busSeatsService->busSeatsExtra($request,$id);
        } 
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null,Config::get('constants.RECORD_UPDATED'),Response::HTTP_OK);
    }

    

    public function deleteBusSeats ($id) {
      $result = ['status' => 200];

      try {
          $result['data'] = $this->busSeatsService->deleteById($id);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }
      return response()->json($result, $result['status']);
    }

    public function getBusSeats($id) {
      $busSeats = $this->busSeatsService->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$busSeats;
      return response($output, 200);
    }      
	     
}


