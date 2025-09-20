<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusSeatsExtra;
use Illuminate\Support\Facades\Validator;
use App\Services\BusSeatsExtraService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\BusSeatsExtraRepository;
use Exception;
use InvalidArgumentException;

class BusSeatsExtraController extends Controller
{
    
    protected $busSeatsExtraService;
    protected $busSeatsExtraRepository;

    
    public function __construct(BusSeatsExtraService $busSeatsExtraService,
                                BusSeatsExtraRepository $busSeatsExtraRepository)
    {
        $this->busSeatsExtraService = $busSeatsExtraService;
        $this->busSeatsExtraRepository = $busSeatsExtraRepository;
    }


    public function getAllBusSeatsExtra() {

        //$busseatsExtra = $this->busSeatsExtraService->getAll();
        $busseatsExtra = $this->busSeatsExtraRepository->getAll();
        $output ['status']=1;
        $output ['message']='All Data Fetched Successfully';
        $output ['result']=$busseatsExtra;
        return response($output, 200);
    }

    public function createBusSeatsExtra(Request $request) {
        $data = $request->only([

            'bus_id','journey_dt','type','seat_type','seat_number','created_by'
          ]);
        
          $busSeatsExtraRules = [
            'bus_id' => 'required',
            'journey_dt' => 'required',
            'type' => 'required',
            'seat_type' => 'required',
            'seat_number' => 'required',
            'created_by' => 'required',
            
        ];
        
        $busSeatsExtraRulesValidation = Validator::make($data, $busSeatsExtraRules);


        if ($busSeatsExtraRulesValidation->fails()) {
            $errors = $busSeatsExtraRulesValidation->errors();
            return $errors->toJson();
          }
      $result = ['status' => 200];

      try {
          //$result['data'] = $this->busSeatsExtraService->savePostData($data);
            $result['data'] = $this->busSeatsExtraRepository->save($data);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }

      return response()->json($result, $result['status']);

    } 

    public function updateBusSeatsExtra(Request $request, $id) {
        $data = $request->only([
            'bus_id','journey_dt','type','seat_type','seat_number','created_by'
        ]);
        $busSeatsExtraRules = [
            'bus_id' => 'required',
            'journey_dt' => 'required',
            'type' => 'required',
            'seat_type' => 'required',
            'seat_number' => 'required',
            'created_by' => 'required',
            
        ];
        
        $busSeatsExtraRulesValidation = Validator::make($data, $busSeatsExtraRules);


        if ($busSeatsExtraRulesValidation->fails()) {
            $errors = $busSeatsExtraRulesValidation->errors();
            return $errors->toJson();
          }

        $result = ['status' => 200];
        DB::beginTransaction();

        try {
            //$result['data'] = $this->busSeatsExtraService->updatePost($data, $id);
            $result['data'] = $this->busSeatsExtraRepository->update($data, $id);

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

    public function deleteBusSeatsExtra ($id) {
      $result = ['status' => 200];


      DB::beginTransaction();
      try {
         // $result['data'] = $this->busSeatsExtraService->deleteById($id);
            $result['data'] = $this->busSeatsExtraRepository->delete($id);
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

    public function getBusSeatsExtra($id) {
      //$busseatsExtra = $this->busSeatsExtraService->getById($id);
        $busseatsExtra = $this->busSeatsExtraRepository->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$busseatsExtra;
      return response($output, 200);
    }      
	     


}
