<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CityClosing;
use Illuminate\Support\Facades\Validator;
use App\Services\CityClosingService;
use Exception;
use InvalidArgumentException;

class CityClosingController extends Controller
{
    protected $cityClosingService;

    
    public function __construct(CityClosingService $cityClosingService)
    {
        $this->cityClosingService = $cityClosingService;
    }


    public function getAllCityClosing() {

        $cityclosing = $this->cityClosingService->getAll();
        $output ['status']=1;
        $output ['message']='All Data Fetched Successfully';
        $output ['result']=$cityclosing;
        return response($output, 200);
    }

    public function createCityClosing(Request $request) {
        $data = $request->only([

            'bus_id', 
            'location_id',
            'closing_hours',
            'created_by'
            
          ]);
        
          $cityClosingRules = [
            'bus_id' => 'required',
            'location_id' => 'required',
            'closing_hours' => 'required',
            'created_by' => 'required',
            
        ];
        
        $cityClosingValidation = Validator::make($data, $cityClosingRules);


        if ($cityClosingValidation->fails()) {
            $errors = $cityClosingValidation->errors();
            return $errors->toJson();
          }
      $result = ['status' => 200];

      try {
          $result['data'] = $this->cityClosingService->savePostData($data);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }

      return response()->json($result, $result['status']);

    } 

    public function updateCityClosing(Request $request, $id) {
        $data = $request->only([
            'bus_id', 
            'location_id',
            'closing_hours',
            'created_by'
        ]);
        $cityClosingRules = [
            'bus_id' => 'required',
            'location_id' => 'required',
            'closing_hours' => 'required',
            'created_by' => 'required',
            
        ];
        
        $cityClosingValidation = Validator::make($data, $cityClosingRules);


        if ($cityClosingValidation->fails()) {
            $errors = $cityClosingValidation->errors();
            return $errors->toJson();
          }

        $result = ['status' => 200];

        try {
            $result['data'] = $this->cityClosingService->updatePost($data, $id);

        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function deleteCityClosing ($id) {
      $result = ['status' => 200];

      try {
          $result['data'] = $this->cityClosingService->deleteById($id);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }
      return response()->json($result, $result['status']);
    }

    public function getCityClosing($id) {
      $cityclosing = $this->cityClosingService->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$cityclosing;
      return response($output, 200);
    }      
	     

}
