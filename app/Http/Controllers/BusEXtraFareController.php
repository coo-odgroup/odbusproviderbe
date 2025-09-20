<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusExtraFare;
use Illuminate\Support\Facades\Validator;
use App\Services\BusExtraFareService;
use App\Repositories\BusExtraFareRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use InvalidArgumentException;

class BusEXtraFareController extends Controller
{
    protected $busExtraFareService;
    protected $busExtraFareRepository;

    
    public function __construct(BusExtraFareService $busExtraFareService,
                                BusExtraFareRepository $busExtraFareRepository)
    {
        $this->busExtraFareService = $busExtraFareService;
        $this->busExtraFareRepository = $busExtraFareRepository;
    }


    // public function getAllBusExtraFare() {

    //     $busExtraFare = $this->busExtraFareService->getAll();
    //     $output ['status']=1;
    //     $output ['message']='All Data Fetched Successfully';
    //     $output ['result']=$busExtraFare;
    //     return response($output, 200);
    // }

     public function getAllBusExtraFare() {

        $busExtraFare = $this->busExtraFareRepository->getAll();
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
          //$result['data'] = $this->busExtraFareService->savePostData($data);
            $result['data'] = $this->busExtraFareRepository->save($data);
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

        DB::beginTransaction();

        try {
            //$result['data'] = $this->busExtraFareService->updatePost($data, $id);
            $result['data'] = $this->busExtraFareRepository->update($data, $id);
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            // Log::info($e->getMessage());
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function deleteBusExtraFare ($id) {
      $result = ['status' => 200];

      DB::beginTransaction();

      try {
         // $result['data'] = $this->busExtraFareService->deleteById($id);
            $result['data'] = $this->busExtraFareRepository->delete($id);
            DB::commit();
      } catch (Exception $e) {
        DB::rollBack();
          // Log::info($e->getMessage());
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }
      return response()->json($result, $result['status']);
    }

    // public function getBusExtraFare($id) {
    //   $busExtraFare = $this->busExtraFareService->getById($id);
    //   $output ['status']=1;
    //   $output ['message']='Single Data Fetched Successfully';
    //   $output ['result']=$busExtraFare;
    //   return response($output, 200);
    // }   
    
     public function getBusExtraFare($id) {
      $busExtraFare = $this->busExtraFareRepository->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$busExtraFare;
      return response($output, 200);
    }      
	     
}
