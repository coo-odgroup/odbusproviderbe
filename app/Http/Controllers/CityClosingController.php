<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CityClosing;
use Illuminate\Support\Facades\Validator;
use App\Services\CityClosingService;
use App\Repositories\CityClosingRepository;
use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CityClosingController extends Controller
{
    protected $cityClosingService;
    protected $cityClosingRepository;

    
    public function __construct(CityClosingService $cityClosingService,
                                CityClosingRepository $cityClosingRepository)
    {
        $this->cityClosingService = $cityClosingService;
        $this->cityClosingRepository = $cityClosingRepository;
    }


    // public function getAllCityClosing() {

    //     $cityclosing = $this->cityClosingService->getAll();
    //     $output ['status']=1;
    //     $output ['message']='All Data Fetched Successfully';
    //     $output ['result']=$cityclosing;
    //     return response($output, 200);
    // }


     public function getAllCityClosing() {

        $cityclosing = $this->cityClosingRepository->getAll();
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
          //$result['data'] = $this->cityClosingService->savePostData($data);
            $result['data'] = $this->cityClosingRepository->save($data);
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
        DB::beginTransaction();

        try {
            //$result['data'] = $this->cityClosingService->updatePost($data, $id);
            $result['data'] = $this->cityClosingRepository->update($data, $id);
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
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

    // public function getCityClosing($id) {
    //   $cityclosing = $this->cityClosingService->getById($id);
    //   $output ['status']=1;
    //   $output ['message']='Single Data Fetched Successfully';
    //   $output ['result']=$cityclosing;
    //   return response($output, 200);
    // }      
     public function getCityClosing($id) {
      $cityclosing = $this->cityClosingRepository->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$cityclosing;
      return response($output, 200);
    }      
	     

}
