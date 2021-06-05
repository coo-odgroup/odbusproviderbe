<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CityClosingExtended;
use Illuminate\Support\Facades\Validator;
use App\Services\CityClosingExtendedService;
use Exception;
use InvalidArgumentException;

class CityClosingExtendedController extends Controller
{
    protected $cityClosingExtendedService;

    
    public function __construct(CityClosingExtendedService $cityClosingExtendedService)
    {
        $this->cityClosingExtendedService = $cityClosingExtendedService;
    }


    public function getAllCityClosingExtended() {

        $cityclosingextended = $this->cityClosingExtendedService->getAll();
        $output ['status']=1;
        $output ['message']='All Data Fetched Successfully';
        $output ['result']=$cityclosingextended;
        return response($output, 200);
    }

    public function createCityClosingExtended(Request $request) {
        $data = $request->only([

            'bus_id', 
            'location_id',
            'journey_date',
            'closing_hours',
            'created_by'
            
          ]);
        
          $cityClosingExtendedRules = [
            'bus_id' => 'required',
            'location_id' => 'required',
            'journey_date' => 'required',
            'closing_hours' => 'required',
            'created_by' => 'required'
            
        ];
        
        $cityClosingExtendedValidation = Validator::make($data, $cityClosingExtendedRules);


        if ($cityClosingExtendedValidation->fails()) {
            $errors = $cityClosingExtendedValidation->errors();
            return $errors->toJson();
          }
      $result = ['status' => 200];

      try {
          $result['data'] = $this->cityClosingExtendedService->savePostData($data);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }

      return response()->json($result, $result['status']);

    } 

    public function updateCityClosingExtended(Request $request, $id) {
        $data = $request->only([
            'bus_id', 
            'location_id',
            'journey_date',
            'closing_hours',
            'created_by'
            
        ]);
        $cityClosingExtendedRules = [
            'bus_id' => 'required',
            'location_id' => 'required',
            'journey_date' => 'required',
            'closing_hours' => 'required',
            'created_by' => 'required'
            
        ];
        
        $cityClosingExtendedValidation = Validator::make($data, $cityClosingExtendedRules);


        if ($cityClosingExtendedValidation->fails()) {
            $errors = $cityClosingExtendedValidation->errors();
            return $errors->toJson();
          }

        $result = ['status' => 200];

        try {
            $result['data'] = $this->cityClosingExtendedService->updatePost($data, $id);

        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function deleteCityClosingExtended ($id) {
      $result = ['status' => 200];

      try {
          $result['data'] = $this->cityClosingExtendedService->deleteById($id);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }
      return response()->json($result, $result['status']);
    }

    public function getCityClosingExtended($id) {
      $cityclosingextended = $this->cityClosingExtendedService->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$cityclosingextended;
      return response($output, 200);
    }      
	     

}
