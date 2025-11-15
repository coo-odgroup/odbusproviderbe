<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Safety;
use App\Services\SafetyService;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use App\AppValidator\SafetyValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use App\Repositories\SafetyRepository;


class SafetyController extends Controller
{
    use ApiResponser;
    /**
     * @var safetyService
     */
    protected $safetyService;
    protected $safetyValidator;
    protected $safetyRepository;

    /**
     * PostController Constructor
     *
     * @param SafetyService $busTypeService
     *
     */
    public function __construct(SafetyService $safetyService, SafetyValidator $safetyValidator,SafetyRepository $safetyRepository)
    {
        $this->safetyService = $safetyService;
        $this->safetyValidator = $safetyValidator;
        $this->safetyRepository = $safetyRepository;
    }
    public function getAll()
    {
        
        $result = $this->safetyRepository->getAll();
        
        return $this->successResponse($result, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }
    public function getByBusId($id)
    {
        try {
            
            $result = $this->safetyRepository->getByBusId($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($result, Config::get('constants.RECORD_FETCHED'), Response::HTTP_ACCEPTED);
    }
    public function getSafetyDT(Request $request)
    {
        
        $result = $this->safetyRepository->getDatatable($request);
        return $this->successResponse($result, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }
    public function safetyByUser(Request $request)
    {
        
        $result = $this->safetyRepository->safetyByUser($request);
        return $this->successResponse($result, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }


    public function getAllData(Request $request)
    {
       
        $result = $this->safetyRepository->getAllData($request);
        return $this->successResponse($result, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function save(Request $request)
    {
        $data = $request->only([
          'name','created_by','icon','android_image','user_id'
        ]);

        $safetyValidation = $this->safetyValidator->validate($data);
        if ($safetyValidation->fails()) {
            $errors = $safetyValidation->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        } else {
            
            $response =  $this->safetyRepository->save($request);

            if ($response == 'Safety Already Exist') {
                return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
            } else {
                return $this->successResponse($response, "Safety Added", Response::HTTP_CREATED);
            }
        }
    }

    public function update(Request $request)
    {

        $data = $request->only([
           'name','created_by','icon','id','android_image','user_id'
         ]);

        $safetyValidation = $this->safetyValidator->validate($data);
        if ($safetyValidation->fails()) {
            $errors = $safetyValidation->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        } else {
            
            $response =  $this->safetyRepository->update($data);

            if ($response == 'Safety Already Exist') {
                return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
            } else {
                return $this->successResponse($response, "Safety Updated", Response::HTTP_CREATED);
            }

        }
    }

    public function delete($id)
    {

        try {
            
            $response = $this->safetyRepository->delete($id);
            return $this->successResponse($response, "Safety Deleted", Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function getById($id)
    {
        try {
    
            $result = $this->safetyRepository->getById($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($result, Config::get('constants.RECORD_FETCHED'), Response::HTTP_ACCEPTED);
    }

    public function changeStatus(Request $request, $id)
    {

        try {
           
            $response = $this->safetyRepository->changeStatus($request, $id);
            return $this->successResponse($response, "Safety Status Updated", Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }

    }
}
