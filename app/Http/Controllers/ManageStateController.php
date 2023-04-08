<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ManageStateService;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;


use Symfony\Component\HttpFoundation\Response;
use App\AppValidator\ManageStateValidator;

class ManageStateController extends Controller
{
    use ApiResponser;
    /**
     * @var manageStateService
     */
    protected $manageStateService;
    protected $manageStateValidator;
    
    /**
     * PostController Constructor
     *
     * @param manageStateService $busTypeService
     *
     */
    public function __construct(ManageStateService $manageStateService,ManageStateValidator $manageStateValidator)
    {
        $this->manageStateService = $manageStateService;
        $this->manageStateValidator = $manageStateValidator;
        
    }
    public function statelist() {
        $manageStates = $this->manageStateService->statelist();
        return $this->successResponse($manageStates,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 

    public function getAllstate(Request $request) {
        $manageStates = $this->manageStateService->getAllstate($request);
        return $this->successResponse($manageStates,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 

    public function changeStatus($id) {
        $manageStates = $this->manageStateService->changeStatus($id);
        return $this->successResponse($manageStates,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function createState(Request $request){
        $data = $request->only(['state_name',
                                'status',
                                'created_by']);   
      
        $manageStateValidator = $this->manageStateValidator->validate($data);
        
        if ($manageStateValidator->fails()) {
          $errors = $manageStateValidator->errors();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        else
        {
          $response = $this->manageStateService->createState($data);

           if($response=='State Already Exist')
           {
              return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
           }
           else
           {
               return $this->successResponse($response,"State Added Successfully. Waiting for Approval", Response::HTTP_CREATED);
           }
        }
    }

    public function updateState(Request $request, $id){
    	$data = $request->only(['id',
                                'state_name',
                                'status',
                                'created_by']);
      
        $manageStateValidator = $this->manageStateValidator->validate($data);
        
        if ($manageStateValidator->fails()) {
          $errors = $manageStateValidator->errors();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        else
        {
          $response = $this->manageStateService->updateState($data, $id);

           if($response=='State Already Exist')
           {
              return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
           }
           else
           {
               return $this->successResponse($response,"State Updated Successfully.", Response::HTTP_CREATED);
           }
        }

    }
   

}