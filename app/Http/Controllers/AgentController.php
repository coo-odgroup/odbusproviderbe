<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\AgentService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use InvalidArgumentException;
use Exception;
use App\AppValidator\AgentValidator;
use App\Repositories\AgentRepository;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class AgentController extends Controller
{
    use ApiResponser;
<<<<<<< HEAD
    
    protected $agentValidator;
    protected $agentRepository;
    
    public function __construct(AgentValidator $agentValidator,AgentRepository $agentRepository)
=======


    protected $agentService;
    protected $agentValidator;

    public function __construct(AgentService $agentService, AgentValidator $agentValidator)
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
    {
      $this->agentValidator = $agentValidator;
      $this->agentRepository = $agentRepository;
    }


<<<<<<< HEAD
    public function agentprofile(Request $request) {
      try {
        $agents = $this->agentRepository->agentprofile($request);
        return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
      } catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
    }

    public function updateAgentProfile(Request $request) {

      $agents = $this->agentRepository->updateAgentProfile($request);
      return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function getAllAgent(Request $request) {

      $agents = $this->agentRepository->getAll($request);
      return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }


    public function getAllAgentData(Request $request) {

      $agents = $this->agentRepository->getAllAgentData($request);
      return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function ourAgentData(Request $request) {

      $agents = $this->agentRepository->ourAgentData($request);
      return $this->successResponse($agents,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function createAgent(Request $request) {
      DB::beginTransaction();
      try {
=======
    public function agentprofile(Request $request)
    {

        $agents = $this->agentService->agentprofile($request);
        return $this->successResponse($agents, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function updateAgentProfile(Request $request)
    {

        $agents = $this->agentService->updateAgentProfile($request);
        return $this->successResponse($agents, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getAllAgent(Request $request)
    {

        $agents = $this->agentService->getAll($request);
        return $this->successResponse($agents, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }


    public function getAllAgentData(Request $request)
    {

        $agents = $this->agentService->getAllAgentData($request);
        return $this->successResponse($agents, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function ourAgentData(Request $request)
    {

        $agents = $this->agentService->ourAgentData($request);
        return $this->successResponse($agents, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function createAgent(Request $request)
    {
        // Log::info($request);
        // exit;
        $data = $request->only([
          'name',
          'email',
          'phone',
          'password',
          'user_type',
          'otp',
          'city',
          'street',
          'location',
          'adhar_no',
          'pancard_no',
          'organization_name',
          'address',
          'landmark',
          'pincode',
          'name_on_bank_account',
          'bank_name',
          'ifsc_code',
          'bank_account_no',
          'agentType',
          'created_by'
        ]);
        $agentValidation = $this->agentValidator->validate($data);

        if ($agentValidation->fails()) {
            $errors = $agentValidation->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        } else {
            $response =  $this->agentService->savePostData($request);

            if ($response == 'Email Already Exist') {
                return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
            } elseif ($response == 'Phone Already Exist') {
                return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
            } elseif ($response == 'Pan Card Already Exist') {
                return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
            } elseif ($response == 'Aadhaar Card Already Exist') {
                return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
            } else {
                return $this->successResponse($response, "Agent Added", Response::HTTP_CREATED);
            }
        }
        // try {
        //     $this->agentService->savePostData($data);

        // }
        // catch (Exception $e) {
        //   return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        // }
        // return $this->successResponse($data,"Agent Created Successfully",Response::HTTP_CREATED);
    }

    public function updateAgent(Request $request, $id)
    {
>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
        $data = $request->only([
          'name',
          'email',
          'phone',
          'password',
          'user_type',
          'otp',
          'city',
          'street',
          'location',
          'adhar_no',
          'pancard_no',
          'organization_name',
          'address',
          'landmark',
          'pincode',
          'name_on_bank_account',
          'bank_name',
          'ifsc_code',
          'bank_account_no',
          'agentType',
          'created_by'
        ]);
<<<<<<< HEAD
        $agentValidation = $this->agentValidator->validate($data);
        
        if ($agentValidation->fails()) {
          $errors = $agentValidation->errors();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }

        $response =  $this->agentRepository->savePostData($request);

        if (in_array($response, [
            'Email Already Exist',
            'Phone Already Exist',
            'Pan Card Already Exist',
            'Aadhaar Card Already Exist'
        ])) {
            DB::rollBack();
            return $this->errorResponse($response, \Symfony\Component\HttpFoundation\Response::HTTP_PARTIAL_CONTENT);
        }

        DB::commit();

      } catch (\Throwable $th) {
        DB::rollBack();
        return $this->errorResponse(
            'Something went wrong while creating agent: ' . $th->getMessage(),
            \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR
        );
      }
    }

    public function updateAgent(Request $request, $id)
    {
      DB::beginTransaction();

      try {
          $data = $request->only([
              'name',
              'email',
              'phone',
              'password',
              'user_type',
              'otp',
              'location',
              'adhar_no',
              'pancard_no',
              'organization_name',
              'address',
              'landmark',
              'pincode',
              'name_on_bank_account',
              'bank_name',
              'ifsc_code',
              'bank_account_no',
              'agentType',
              'created_by'
          ]);

          $response = $this->agentRepository->update($data, $id);

          if (in_array($response, [
              'Email Already Exist',
              'Phone Already Exist',
              'Pan Card Already Exist',
              'Aadhaar Card Already Exist'
          ])) {
              DB::rollBack();
              return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
          }

          DB::commit();
          return $this->successResponse($response, "Agent Updated", Response::HTTP_CREATED);

      } catch (\Throwable $th) {
          DB::rollBack();
          \Log::error('Agent update failed: '.$th->getMessage());
          return $this->errorResponse(
              'Something went wrong: ' . $th->getMessage(),
              Response::HTTP_INTERNAL_SERVER_ERROR
          );
      }
    }

    public function deleteAgent ($id) {
      try {
        $this->agentRepository->deleteById($id);
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse('Null',"Agent has been deleted Successfully",Response::HTTP_ACCEPTED);
     
    }

    public function getAgent($id) {
      try {
        $agentID= $this->agentRepository->getById($id);
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
      return $this->successResponse($agentID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
      
    }

    public function changeStatus(Request $request) {
      try{
        $this->agentRepository->changeStatus($request);
      }
      catch (Exception $e){
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(null, "Agent Status Updated", Response::HTTP_ACCEPTED);
    }

    public function blockAgent(Request $request) {
      try{
        $this->agentRepository->blockAgent($request);
      }
      catch (Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(null, "Agent Status Updated", Response::HTTP_ACCEPTED);
    }
    
=======

        $response =  $this->agentService->update($data, $id);

        if ($response == 'Email Already Exist') {
            return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
        } elseif ($response == 'Phone Already Exist') {
            return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
        } elseif ($response == 'Pan Card Already Exist') {
            return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
        } elseif ($response == 'Aadhaar Card Already Exist') {
            return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
        } else {
            return $this->successResponse($response, "Agent Updated", Response::HTTP_CREATED);
        }

    }

    public function deleteAgent($id)
    {

        try {
            $this->agentService->deleteById($id);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, "Agent has been deleted Successfully", Response::HTTP_ACCEPTED);

    }

    public function getAgent($id)
    {
        try {
            $AgentID = $this->agentService->getById($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse($AgentID, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);

    }

    public function changeStatus(Request $request)
    {
        // Log::info($request);exit;
        try {
            $this->agentService->changeStatus($request);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, "Agent Status Updated", Response::HTTP_ACCEPTED);
    }

    public function blockAgent(Request $request)
    {
        try {
            $this->agentService->blockAgent($request);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, "Agent Status Updated", Response::HTTP_ACCEPTED);
    }

>>>>>>> 114ea55211b248e60ed9698f8c4023bf06b9735c
}
