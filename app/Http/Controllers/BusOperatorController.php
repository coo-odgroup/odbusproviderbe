<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\BusOperatorRepository;
use App\AppValidator\BusOperatorValidator;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class BusOperatorController extends Controller
{
    use ApiResponser;

    protected $busOperatorRepository;
    protected $BusOperatorValidator;

    public function __construct(BusOperatorRepository $busOperatorRepository, BusOperatorValidator $BusOperatorValidator)
    {
        $this->busOperatorRepository = $busOperatorRepository;
        $this->BusOperatorValidator = $BusOperatorValidator;
    }

    
    public function getAllBusOperatorsDT(Request $request)
    {
        $BusOperators = $this->busOperatorRepository->getDatatable($request);
        return $this->successResponse($BusOperators, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function BusbyOperatorData(Request $request)
    {
        $BusOperators = $this->busOperatorRepository->BusbyOperatorData($request);
        return $this->successResponse($BusOperators, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function userOperators(Request $request)
    {
        $BusOperators = $this->busOperatorRepository->userOperators($request);
        return $this->successResponse($BusOperators, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

   
    public function getAllBusOperators()
    {
        $prod = $this->busOperatorRepository->getAll();
        return $this->successResponse($prod, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getBusOperator($id)
    {
        try {
            $operator = $this->busOperatorRepository->getById($id);
            return $this->successResponse($operator, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function createBusOperator(Request $request)
    {
        $data = $request->only([
            'email_id', 'password', 'operator_name', 'contact_number', 'organisation_name',
            'location_name', 'address', 'operator_info', 'additional_email', 'additional_contact',
            'bank_account_name', 'bank_name', 'bank_ifsc', 'bank_account_number', 'need_gst_bill',
            'gst_number', 'gst_amount', 'user_id', 'created_by'
        ]);

        $validation = $this->BusOperatorValidator->validate($data);
        if ($validation->fails()) {
            $errors = $validation->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        try {
            $this->busOperatorRepository->save($data);
            return $this->successResponse(null, "Bus Operator Added", Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function updateBusOperator(Request $request, $id)
    {
        $data = $request->only([
            'email_id', 'password', 'operator_name', 'contact_number', 'organisation_name',
            'location_name', 'address', 'operator_info', 'additional_email', 'additional_contact',
            'bank_account_name', 'bank_name', 'bank_ifsc', 'bank_account_number', 'created_by',
            'need_gst_bill', 'gst_number', 'user_id', 'gst_amount'
        ]);

        $validation = $this->BusOperatorValidator->validate($data);
        if ($validation->fails()) {
            $errors = $validation->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        try {
            $this->busOperatorRepository->update($data, $id);
            return $this->successResponse(null, "Bus Operator Updated", Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function deleteBusOperator($id)
    {
        try {
            $this->busOperatorRepository->delete($id);
            return $this->successResponse(null, "Bus Operator Deleted", Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    
    public function getOperatorEmail(Request $request)
    {
        try {
            $result = $this->busOperatorRepository->getOperatorEmail($request);
            return $this->successResponse($result, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function getOperatorPhone(Request $request)
    {
        try {
            $result = $this->busOperatorRepository->getOperatorPhone($request);
            return $this->successResponse($result, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function changeStatus($id)
    {
        try {
            $this->busOperatorRepository->changeStatus($id);
            return $this->successResponse(null, "Bus Operator Status Updated", Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function getBusbyOperator($id)
    {
        try {
            $buses = $this->busOperatorRepository->getBusbyOperator($id);
            return $this->successResponse($buses, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }
}
