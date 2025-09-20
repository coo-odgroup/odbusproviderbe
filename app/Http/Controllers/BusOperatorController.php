<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusOperator;
use App\Services\BusOperatorService;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Repositories\BusOperatorRepository;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use App\AppValidator\BusOperatorValidator;
use Symfony\Component\HttpFoundation\Response;

class BusOperatorController extends Controller
{
    use ApiResponser;
    /**
     * @var busOperatorService
     */
    protected $busOperatorService;
    protected $BusOperatorValidator;
    protected $busOperatorRepository;
    /**
     * PostController Constructor
     *
     * @param BusOperatorService $busTypeService
     *
     */
    public function __construct(BusOperatorService $busOperatorService, 
                                BusOperatorValidator $BusOperatorValidator,
                                BusOperatorRepository $busOperatorRepository)
    {
        $this->busOperatorService = $busOperatorService;
        $this->BusOperatorValidator= $BusOperatorValidator;
        $this->busOperatorRepository = $busOperatorRepository;
    }
    public function getAllBusOperatorsDT(Request $request)
    {
     // $BusOperators = $this->busOperatorService->dataTable($request);
      $BusOperators = $this->busOperatorRepository->getDatatable($request); 
      return $this->successResponse($BusOperators,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function BusbyOperatorData(Request $request)
    {
      //$BusOperators = $this->busOperatorService->BusbyOperatorData($request);
      $BusOperators = $this->busOperatorRepository->BusbyOperatorData($request);
      return $this->successResponse($BusOperators,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function userOperators(Request $request)
    {
      // $BusOperators = $this->busOperatorService->userOperators($request);
      $BusOperators = $this->busOperatorRepository->userOperators($request);
      return $this->successResponse($BusOperators,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }


    public function getAllBusOperators() {
       // $prod = $this->busOperatorService->getAll();
       $prod = $this->busOperatorRepository->getAll();
        return $this->successResponse($prod,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function getOperatorEmail(Request $request)
    {
        try {
            //$result=$this->busOperatorService->getOperatorEmail($request);
              $result = $this->busOperatorRepository->getOperatorEmail($request);
            return $this->successResponse($result, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        }
        catch(Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function getOperatorPhone(Request $request)
    {
        try {
           // $result=$this->busOperatorService->getOperatorPhone($request);

      $result=$this->busOperatorRepository->getOperatorPhone($request);
            return $this->successResponse($result, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        }
        catch(Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
    }
    public function createBusOperator(Request $request) {
        $data = $request->only([
          'email_id',
          'password',
          'operator_name',
          'contact_number',
          'organisation_name',
          'location_name',
          'address',
          'operator_info',
          'additional_email',
          'additional_contact',
          'bank_account_name',
          'bank_name',
          'bank_ifsc',
          'bank_account_number',
          'need_gst_bill',
          'gst_number',
          'gst_amount',
          'user_id',
          'created_by'
        ]);  
        $BusOperatorValidation = $this->BusOperatorValidator->validate($data);
        if ($BusOperatorValidation->fails()) {
         
          $errors = $BusOperatorValidation->errors();
          // return $errors->toJson();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
            //$this->busOperatorService->savePostData($data);
            $this->busOperatorRepository->save($data);
            return $this->successResponse(null,"Bus Operator Added", Response::HTTP_CREATED);
        }
        catch(Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }	
    } 

    public function updateBusOperator(Request $request, $id) {
        $data = $request->only([
            'email_id',
            'password',
            'operator_name',
            'contact_number',
            'organisation_name',
            'location_name',
            'address',
            'operator_info',
            'additional_email',
            'additional_contact',
            'bank_account_name',
            'bank_name',
            'bank_ifsc',
            'bank_account_number',
            'created_by',
            'need_gst_bill',
            'gst_number',
            'user_id',
            'gst_amount',
        ]);
        $BusOperatorValidation = $this->BusOperatorValidator->validate($data);
        if ($BusOperatorValidation->fails()) {
         
          $errors = $BusOperatorValidation->errors();
          return $this->errorResponse(null,Response::HTTP_PARTIAL_CONTENT);
        }
        try {
            //$this->busOperatorService->updatePost($data, $id);
            $this->busOperatorRepository->update($data, $id);

            return $this->successResponse(null, "Bus Operator Updated", Response::HTTP_CREATED);
        }
        catch(Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }       
    }

    public function deleteBusOperator ($id) 
    {
        try{
            //$this->busOperatorService->deleteById($id);
            $this->busOperatorRepository->delete($id);
        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, "Bus Operator Deleted", Response::HTTP_ACCEPTED);
    }

    public function getBusOperator($id) {
      try {
        //$operators= $this->busOperatorService->getById($id);
        $this->busOperatorRepository->getById($id);

      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse($operators, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);


    }
    
    public function changeStatus ($id) {
      try{
        //$this->busOperatorService->changeStatus($id);
        $this->busOperatorRepository->changeStatus($id);
      }
      catch (Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(null, "Bus Operator Status Updated", Response::HTTP_ACCEPTED);
    }
    
    public function getBusbyOperator($id) {
      try {
        //$buses= $this->busOperatorService->getBusbyOperator($id);
        $this->busOperatorRepository->getBusbyOperator($id);

      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse($buses, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }
}
