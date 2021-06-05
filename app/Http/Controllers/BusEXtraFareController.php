<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusExtraFare;
use Illuminate\Support\Facades\Validator;
use App\Services\BusExtraFareService;
use Exception;
use InvalidArgumentException;

class BusEXtraFareController extends Controller
{
    protected $busExtraFareService;

    
    public function __construct(BusExtraFareService $busExtraFareService)
    {
        $this->busExtraFareService = $busExtraFareService;
    }


    public function getAllBusExtraFare() {

        $busExtraFare = $this->busExtraFareService->getAll();
        $output ['status']=1;
        $output ['message']='All Data Fetched Successfully';
        $output ['result']=$busExtraFare;
        return response($output, 200);
    }

    public function createBusExtraFare(Request $request) {
        $data = $request->only([

            'bus_id', 'type','journey_date','seat_fare','sleeper_fare','created_by'
            
          ]);
        
          $busExtraFareRules = [
            'bus_id' => 'required',
            'type' => 'required',
            'journey_date' => 'required',
            'seat_fare' => 'required',
            'sleeper_fare' => 'required',
            'created_by' => 'required',
            
        ];
        
        $busExtraFareValidation = Validator::make($data, $busExtraFareRules);


        if ($busExtraFareValidation->fails()) {
            $errors = $busExtraFareValidation->errors();
            return $errors->toJson();
          }
      $result = ['status' => 200];

      try {
          $result['data'] = $this->busExtraFareService->savePostData($data);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }

      return response()->json($result, $result['status']);

    } 

    public function updateBusExtraFare(Request $request, $id) {
        $data = $request->only([
            'bus_id', 'type','journey_date','seat_fare','sleeper_fare','created_by'
        ]);
        $busExtraFareRules = [
            'bus_id' => 'required',
            'type' => 'required',
            'journey_date' => 'required',
            'seat_fare' => 'required',
            'sleeper_fare' => 'required',
            'created_by' => 'required',
            
        ];
        
        $busExtraFareValidation = Validator::make($data, $busExtraFareRules);


        if ($busExtraFareValidation->fails()) {
            $errors = $busExtraFareValidation->errors();
            return $errors->toJson();
          }

        $result = ['status' => 200];

        try {
            $result['data'] = $this->busExtraFareService->updatePost($data, $id);

        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function deleteBusExtraFare ($id) {
      $result = ['status' => 200];

      try {
          $result['data'] = $this->busExtraFareService->deleteById($id);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }
      return response()->json($result, $result['status']);
    }

    public function getBusExtraFare($id) {
      $busExtraFare = $this->busExtraFareService->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$busExtraFare;
      return response($output, 200);
    }      
	     
}
