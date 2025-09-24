<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusSeatLayout;
use App\Services\BusSeatLayoutService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Repositories\BusSeatLayoutRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use InvalidArgumentException;
use App\AppValidator\BusSeatLayoutValidator;
use Symfony\Component\HttpFoundation\Response;

class BusSeatLayoutController extends Controller
{
    use ApiResponser;
    protected $busSeatLayoutService;
    protected $busSeatLayoutValidator;
    protected $busSeatLayoutRepository;
    public function __construct(BusSeatLayoutService $busSeatLayoutService, 
                                BusSeatLayoutValidator $busSeatLayoutValidator,
                                BusSeatLayoutRepository $busSeatLayoutRepository)
    {
        $this->busSeatLayoutService = $busSeatLayoutService;
        $this->busSeatLayoutValidator = $busSeatLayoutValidator;
        $this->busSeatLayoutRepository = $busSeatLayoutRepository;
    }
    public function getSeatLayoutRecord($id) {
      try {
        //$busSeatLayoutID= $this->busSeatLayoutService->getSeatLayoutRecord($id);
        $busSeatLayoutID = $this->busSeatLayoutRepository->getSeatLayoutRecord($id);
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
      return $this->successResponse($busSeatLayoutID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }
    public function getAll() {
        //$busSeatLayout = $this->busSeatLayoutService->getAll();
        $busSeatLayout = $this->busSeatLayoutRepository->getAllBusSeatLayoutDT($request);
        return $this->successResponse($busSeatLayout,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function BusSeatLayoutOperator(Request $request) {
        //$busSeatLayout = $this->busSeatLayoutService->BusSeatLayoutOperator($request);
        $busSeatLayout = $this->busSeatLayoutRepository->BusSeatLayoutOperator($request);
        return $this->successResponse($busSeatLayout,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function BusSeatLayoutbyUser(Request $request) {
        //$busSeatLayout = $this->busSeatLayoutService->BusSeatLayoutbyUser($request);
        $busSeatLayout = $this->busSeatLayoutRepository->BusSeatLayoutbyUser($request);
        return $this->successResponse($busSeatLayout,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function save(Request $request) {
        $data = $request->only([
          'name',
          'layout_data',
          'created_by',
          'user_id',
          'bus_operator_id'
        ]);
        $busSeatLayoutValidation = $this->busSeatLayoutValidator->validate($data);
        if ($busSeatLayoutValidation->fails()) {
          $errors = $busSeatLayoutValidation->errors();
          // return $errors->toJson();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
            // $this->busSeatLayoutService->save($data);  
            $this->busSeatLayoutRepository->save($data);
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
          'user_id',
          'bus_operator_id'
        ]);
        $busSeatLayoutValidation = $this->busSeatLayoutValidator->validate($data);

        if ($busSeatLayoutValidation->fails()) {
          $errors = $busSeatLayoutValidation->errors();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
           // $this->busSeatLayoutService->update($data, $id);   
           $this->busSeatLayoutRepository->update($data, $id);
        }
        catch (Exception $e) {
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($data,"Seat Layout Updated",Response::HTTP_OK); 
    }

    public function deleteById($id) {
      try {
      //$this->busSeatLayoutService->deleteById($id);
      $this->busSeatLayoutRepository->delete($id);
      } 
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
      return $this->successResponse(Null,"Seat Layout Deleted",Response::HTTP_ACCEPTED); 
    }
    public function getById($id) {
      try {
       // $busSeatLayoutID= $this->busSeatLayoutService->getById($id);
       $busSeatLayoutID = $this->busSeatLayoutRepository->delete($id);
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
      return $this->successResponse($busSeatLayoutID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }

    public function getRowCol($id,$type) {
      try {
        //$busSeatLayoutID= $this->busSeatLayoutService->getRowCol($id,$type);
         $busSeatLayoutID = $this->busSeatLayoutRepository->getRowCol($id,$type);
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
      return $this->successResponse($busSeatLayoutID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }


    public function getBusSeatLayoutDT(Request $request) {      
      //$busSeatLayout = $this->busSeatLayoutService->getAllBusSeatLayoutDT($request);
      $busSeatLayout = $this->busSeatLayoutRepository->getAllBusSeatLayoutDT($request);
      return $this->successResponse($busSeatLayout,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }

    public function BusSeatLayoutData(Request $request) {      
      //$busSeatLayout = $this->busSeatLayoutService->BusSeatLayoutData($request);
      $busSeatLayout = $this->busSeatLayoutRepository->BusSeatLayoutData($request);
      return $this->successResponse($busSeatLayout,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }

    
    public function changeStatus($id) {
      try{
        //$this->busSeatLayoutService->changeStatus($id);
        $this->busSeatLayoutRepository->changeStatus($id);
      }
      catch (Exception $e){
           DB::rollBack();
              Log::info($e->getMessage());
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(null, "Seat Layout Status Updated", Response::HTTP_ACCEPTED);
    }

}
