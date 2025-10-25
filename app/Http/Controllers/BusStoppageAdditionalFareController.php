<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusStoppageAdditionalFare;
use Illuminate\Support\Facades\Validator;
use App\Services\BusStoppageAdditionalFareService;
use Exception;
use InvalidArgumentException;

class BusStoppageAdditionalFareController extends Controller
{
    protected $busStoppageAdditionalFareService;

    
    public function __construct(BusStoppageAdditionalFareService $busStoppageAdditionalFareService)
    {
        $this->busStoppageAdditionalFareService = $busStoppageAdditionalFareService;
    }


    public function getAllBusStoppageAdditionalFare() {

        $busfare = $this->busStoppageAdditionalFareService->getAll();
        $output ['status']=1;
        $output ['message']='All Data Fetched Successfully';
        $output ['result']=$busfare;
        return response($output, 200);
    }

    public function createBusStoppageAdditionalFare(Request $request) {
        $data = $request->only([

            'ticket_price_id', 
            'bus_seats_id',
            'additional_fare',
            'created_by'
            
          ]);
        
          $busStoppageAdditionalFareRules = [
            'ticket_price_id' => 'required',
            'bus_seats_id' => 'required',
            'additional_fare' => 'required',
            'created_by' => 'required',
            
        ];
        
        $busStoppageAdditionalFareValidation = Validator::make($data, $busStoppageAdditionalFareRules);


        if ($busStoppageAdditionalFareValidation->fails()) {
            $errors = $busStoppageAdditionalFareValidation->errors();
            return $errors->toJson();
          }
      $result = ['status' => 200];

      try {
          $result['data'] = $this->busStoppageAdditionalFareService->savePostData($data);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }

      return response()->json($result, $result['status']);

    } 

    public function updateBusStoppageAdditionalFare(Request $request, $id) {
        $data = $request->only([
            'ticket_price_id', 
            'bus_seats_id',
            'additional_fare',
            'created_by'
        ]);
        $busStoppageAdditionalFareRules = [
            'ticket_price_id' => 'required',
            'bus_seats_id' => 'required',
            'additional_fare' => 'required',
            'created_by' => 'required',
            
        ];
        
        $busStoppageAdditionalFareValidation = Validator::make($data, $busStoppageAdditionalFareRules);


        if ($busStoppageAdditionalFareValidation->fails()) {
            $errors = $busStoppageAdditionalFareValidation->errors();
            return $errors->toJson();
          }

        $result = ['status' => 200];

        try {
            $result['data'] = $this->busStoppageAdditionalFareService->updatePost($data, $id);

        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function deleteBusStoppageAdditionalFare ($id) {
      $result = ['status' => 200];

      try {
          $result['data'] = $this->busStoppageAdditionalFareService->deleteById($id);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }
      return response()->json($result, $result['status']);
    }

    public function getBusStoppageAdditionalFare($id) {
      $busfare = $this->busStoppageAdditionalFareService->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$busfare;
      return response($output, 200);
    }      
	     

}
