<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CityClosingExtended;
use Illuminate\Support\Facades\Validator;
use App\Services\CityClosingExtendedService;
use App\Repositories\CityClosingExtendedRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use InvalidArgumentException;

class CityClosingExtendedController extends Controller
{
    protected $cityClosingExtendedService;
    protected $cityClosingExtendedRepository;


    
    public function __construct(CityClosingExtendedService $cityClosingExtendedService,
                                 CityClosingExtendedRepository $cityClosingExtendedRepository
    )
    {
        $this->cityClosingExtendedService = $cityClosingExtendedService;
        $this->cityClosingExtendedRepository = $cityClosingExtendedRepository;
    }


    // public function getAllCityClosingExtended() {

    //     $cityclosingextended = $this->cityClosingExtendedService->getAll();
    //     $output ['status']=1;
    //     $output ['message']='All Data Fetched Successfully';
    //     $output ['result']=$cityclosingextended;
    //     return response($output, 200);
    // }
    public function getAllCityClosingExtended() {

        $cityclosingextended = $this->cityClosingExtendedRepository->getAll();
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
          //$result['data'] = $this->cityClosingExtendedService->savePostData($data);
            $result['data'] = $this->cityClosingExtendedRepository->save($data);
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
         DB::beginTransaction();

        try {
           // $result['data'] = $this->cityClosingExtendedService->updatePost($data, $id);
            $result['data'] = $this->cityClosingExtendedRepository->update($data, $id);
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

    public function deleteCityClosingExtended ($id) {
      $result = ['status' => 200];

      DB::beginTransaction();

      try {
         // $result['data'] = $this->cityClosingExtendedService->deleteById($id);
            $result['data'] = $this->cityClosingExtendedRepository->delete($id);
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

    // public function getCityClosingExtended($id) {
    //   $cityclosingextended = $this->cityClosingExtendedService->getById($id);
    //   $output ['status']=1;
    //   $output ['message']='Single Data Fetched Successfully';
    //   $output ['result']=$cityclosingextended;
    //   return response($output, 200);
    // }      

     public function getCityClosingExtended($id) {
      $cityclosingextended = $this->cityClosingExtendedRepository->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$cityclosingextended;
      return response($output, 200);
    }      
	     

}
