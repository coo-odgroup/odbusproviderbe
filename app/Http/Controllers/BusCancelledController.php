<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusCancelled;
use App\Models\Bus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Services\BusCancelledService;
use App\Traits\ApiResponser;
use Exception;
use InvalidArgumentException;
use App\AppValidator\CancelBusValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
class BusCancelledController extends Controller
{
    use ApiResponser;
    protected $busCancelledService;
    protected $cancelBusValidator;
    
    public function __construct(busCancelledService $busCancelledService, CancelBusValidator $cancelBusValidator)
    {
        $this->busCancelledService = $busCancelledService;
        $this->cancelBusValidator = $cancelBusValidator;
    }
    public function getAllBusCancelled() {

        $buscancelled = $this->busCancelledService->getAll();
        return $this->successResponse($buscancelled,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function getBusCancelledDT(Request $request) {
        $buscancelled = $this->busCancelledService->getBusCancelledDT($request);
        return $this->successResponse($buscancelled,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
      }

    public function createBusCancelled(Request $request) {
        $data = $request->only([
        
            'bus_id','bus_operator_id','cancelled_date','reason','cancelled_by','buses','month','year'
        ]);
          
          $busCancelledValidation = $this->cancelBusValidator->validate($data);
        if ($busCancelledValidation->fails()) {
           $errors = $busCancelledValidation->errors();
           return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
         }
        try {
          $data = $this->busCancelledService->savePostData($data);
           return $this->successResponse($data, Config::get('constants.RECORD_ADDED'), Response::HTTP_CREATED);
        } catch (Exception $e) {
           return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }  
    } 
    public function updateBusCancelled(Request $request, $id) {
        $data = $request->only(['bus_id','bus_operator_id','cancelled_date','reason','cancelled_by','dateLists','month','year' 
        ]);

        $busCancelledValidation = $this->cancelBusValidator->validate($data);
        if ($busCancelledValidation->fails()) {
            $errors = $busCancelledValidation->errors();
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
            $response = $this->busCancelledService->updatePost($data, $id);
            return $this->successResponse( $response, Config::get('constants.RECORD_UPDATED'), Response::HTTP_CREATED);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
        }
    }

    public function deleteBusCancelled($id) {
        try {
          $response = $this->busCancelledService->deleteById($id);
          return $this->successResponse($response, Config::get('constants.RECORD_REMOVED'), Response::HTTP_ACCEPTED);
          }
          catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
          }     
    }
    public function getBusCancelled($id) {
      try {
        $buscancelledID= $this->busCancelledService->getById($id);
        return $this->successResponse($buscancelledID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }  
    }      
    public function changeStatus ($id) {
        try{
          $response =  $this->busCancelledService->changeStatus($id);
          return $this->successResponse($response, Config::get('constants.RECORD_UPDATED'), Response::HTTP_ACCEPTED);
        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }  
      }
	     
}

