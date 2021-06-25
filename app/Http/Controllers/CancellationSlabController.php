<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CancellationSlab;
use App\Services\CancellationSlabService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use Exception;
use InvalidArgumentException;
use App\AppValidator\CancellationSlabValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
class CancellationSlabController extends Controller
{
    use ApiResponser;
    protected $cancellationSlabService;
    protected $cancellationSlabValidator;
    
    public function __construct(CancellationSlabService $cancellationSlabService, CancellationSlabValidator $cancellationSlabValidator)
    {
      $this->cancellationSlabService = $cancellationSlabService;
      $this->CancellationSlabValidator= $cancellationSlabValidator;
    }

    public function getAllCancellationSlab(Request $request) {
      $cSlab = $this->cancellationSlabService->getAll($request);
      return $this->successResponse($cSlab,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function getCancellationSlabDT(Request $request) {
      $cSlab = $this->cancellationSlabService->getCancellationSlabDT($request);
      return $this->successResponse($cSlab,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function deleteCancellationSlab ($id) {
      try {
        $response = $this->cancellationSlabService->deleteById($id);
        return $this->successResponse($response, Config::get('constants.RECORD_REMOVED'), Response::HTTP_ACCEPTED);
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      } 
    }

    public function getCancellationSlab($id) {
      try {
        $cSlabID= $this->cancellationSlabService->getById($id);
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse($cSlabID, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }
    
    public function createCancellationSlab(Request $request) {
      $data = $request->only([
          'api_id', 'rule_name','slabs'
      ]);
      
      $cSlabValidate = $this->CancellationSlabValidator->validate($data);
      
      if ($cSlabValidate->fails()) {
          $errors = $cSlabValidate->errors();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      }
      try {
       
        $response = $this->cancellationSlabService->savePostData($data);
          return $this->successResponse($response, Config::get('constants.RECORD_ADDED'), Response::HTTP_CREATED);
      }
      catch(Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
  }
  public function updateCancellationSlab(Request $request, $id) {
    $data = $request->only([
      'api_id', 'rule_name', 'slabs'
    ]);
    $cSlabValidate = $this->CancellationSlabValidator->validate($data);
    if ($cSlabValidate->fails()) {
        $errors = $cSlabValidate->errors();
        return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
    }
    try {
      $response = $this->cancellationSlabService->updatePost($data, $id);
      return $this->successResponse($response, Config::get('constants.RECORD_UPDATED'),Response::HTTP_CREATED);
    }
    catch(Exception $e){
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
    }
  }

  public function changeStatus ($id) {
    try{
      $response = $this->cancellationSlabService->changeStatus($id);
      return $this->successResponse($response, Config::get('constants.RECORD_UPDATED'), Response::HTTP_ACCEPTED);
    }
    catch (Exception $e){
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
    } 
  }
}
