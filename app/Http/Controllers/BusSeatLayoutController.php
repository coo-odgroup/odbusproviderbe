<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusSeatLayout;
use App\Services\BusSeatLayoutService;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use InvalidArgumentException;
use App\AppValidator\BusSeatLayoutValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class BusSeatLayoutController extends Controller
{
    use ApiResponser;
    protected $busSeatLayoutService;
    protected $busSeatLayoutValidator;
    public function __construct(BusSeatLayoutService $busSeatLayoutService,BusSeatLayoutValidator $busSeatLayoutValidator)
    {
        $this->busSeatLayoutService = $busSeatLayoutService;
        $this->busSeatLayoutValidator = $busSeatLayoutValidator;
    }
    public function getSeatLayoutRecord($id) {
      try {
        $busSeatLayoutID= $this->busSeatLayoutService->getSeatLayoutRecord($id);
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
      return $this->successResponse($busSeatLayoutID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }
    public function getAll() {
        $busSeatLayout = $this->busSeatLayoutService->getAll();
        return $this->successResponse($busSeatLayout,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function BusSeatLayoutOperator(Request $request) {
        $busSeatLayout = $this->busSeatLayoutService->BusSeatLayoutOperator($request);
        return $this->successResponse($busSeatLayout,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function save(Request $request) {
        $data = $request->only([
          'name',
          'layout_data',
          'created_by',
          'bus_operator_id'
        ]);
        $busSeatLayoutValidation = $this->busSeatLayoutValidator->validate($data);
        if ($busSeatLayoutValidation->fails()) {
          $errors = $busSeatLayoutValidation->errors();
          // return $errors->toJson();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
            $this->busSeatLayoutService->save($data);  
        }
        catch (Exception $e) {
          return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
        }  
        return $this->successResponse($data,"Seat Layout Created",Response::HTTP_CREATED);
    } 
    public function update(Request $request, $id) {
      // Log::info($id);

        $data = $request->only([
          'name',
          'layout_data',
          'created_by',
          'bus_operator_id'
        ]);
        $busSeatLayoutValidation = $this->busSeatLayoutValidator->validate($data);

        if ($busSeatLayoutValidation->fails()) {
          $errors = $busSeatLayoutValidation->errors();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
            $this->busSeatLayoutService->update($data, $id);   
        }
        catch (Exception $e) {
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($data,"Seat Layout Updated",Response::HTTP_OK); 
    }

    public function deleteById($id) {
      try {
      $this->busSeatLayoutService->deleteById($id);
      } 
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
      return $this->successResponse(Null,"Seat Layout Deleted",Response::HTTP_ACCEPTED); 
    }
    public function getById($id) {
      try {
        $busSeatLayoutID= $this->busSeatLayoutService->getById($id);
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
      return $this->successResponse($busSeatLayoutID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }

    public function getRowCol($id,$type) {
      try {
        $busSeatLayoutID= $this->busSeatLayoutService->getRowCol($id,$type);
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
      return $this->successResponse($busSeatLayoutID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }


    public function getBusSeatLayoutDT(Request $request) {      
      $busSeatLayout = $this->busSeatLayoutService->getAllBusSeatLayoutDT($request);
      return $this->successResponse($busSeatLayout,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }

    public function BusSeatLayoutData(Request $request) {      
      $busSeatLayout = $this->busSeatLayoutService->BusSeatLayoutData($request);
      return $this->successResponse($busSeatLayout,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }

    
    public function changeStatus($id) {
      try{
        $this->busSeatLayoutService->changeStatus($id);
      }
      catch (Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(null, "Seat Layout Status Updated", Response::HTTP_ACCEPTED);
    }

}
