<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusSlots;
use Illuminate\Support\Facades\Validator;
use App\Repositories\BusSlotsRepository;
use App\Services\BusSlotsService;
use Illuminate\Support\Facades\Log;
use Exception;
use InvalidArgumentException;

class BusSlotsController extends Controller
{
    protected $busSlotsService;
    protected $busSlotsRepository;

    
    public function __construct(BusSlotsService $busSlotsService,
                                BusSlotsRepository $busSlotsRepository)
    {
        $this->busSlotsService = $busSlotsService;
        $this->busSlotsRepository = $busSlotsRepository;
    }


    public function getAllBusSlots() {

        //$busSlots = $this->busSlotsService->getAll();
        $busSlots = $this->busSlotsRepository->getAll();
        $output ['status']=1;
        $output ['message']='All Data Fetched Successfully';
        $output ['result']=$busSlots;
        return response($output, 200);
    }

    public function createBusSlots(Request $request) {
        $data = $request->only([

            'bus_id', 'name','type','created_by'
            
          ]);
        
          $busSlotsRules = [
            'bus_id' => 'required',
            'name' => 'required',
            'type' => 'required',
            'created_by' => 'required',
            
        ];
        
        $busSlotsValidation = Validator::make($data, $busSlotsRules);


        if ($busSlotsValidation->fails()) {
            $errors = $busSlotsValidation->errors();
            return $errors->toJson();
          }
      $result = ['status' => 200];

      try {
         // $result['data'] = $this->busSlotsService->savePostData($data);
            $result['data'] = $this->busSlotsRepository->save($data);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }

      return response()->json($result, $result['status']);

    } 

    public function updateBusSlots(Request $request, $id) {
        $data = $request->only([
            'bus_id', 'name','type','created_by'
        ]);
        $busSlotsValidation = [
            'bus_id' => 'required',
            'name' => 'required',
            'type' => 'required',
            'created_by' => 'required',
            
        ];
        
        $busSlotsValidation = Validator::make($data, $busSlotsValidation);


        if ($busSlotsValidation->fails()) {
            $errors = $busSlotsValidation->errors();
            return $errors->toJson();
          }

        $result = ['status' => 200];
        DB::beginTransaction();

        try {
            //$result['data'] = $this->busSlotsService->updatePost($data, $id);
            $result['data'] = $this->busSlotsRepository->update($data, $id);
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

    public function deleteBusSlots ($id) {
      $result = ['status' => 200];
      DB::beginTransaction();

      try {
         // $result['data'] = $this->busSlotsService->deleteById($id);
            $result['data'] = $this->busSlotsRepository->delete($id);
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

    public function getBusBusSlots($id) {
      //$busExtraFare = $this->busSlotsService->getById($id);
        $busExtraFare = $this->busSlotsRepository->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$busExtraFare;
      return response($output, 200);
    }      
	     
}
