<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ManageStateRepository;
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

    protected $manageStateRepository;
    protected $manageStateValidator;

    public function __construct(ManageStateRepository $manageStateRepository, ManageStateValidator $manageStateValidator)
    {
        $this->manageStateRepository = $manageStateRepository;
        $this->manageStateValidator = $manageStateValidator;
    }

    public function statelist()
    {
        $manageStates = $this->manageStateRepository->statelist();
        return $this->successResponse($manageStates, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getAllstate(Request $request)
    {
        $manageStates = $this->manageStateRepository->getAllstate($request);
        return $this->successResponse($manageStates, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function changeStatus($id)
    {
        $manageStates = $this->manageStateRepository->changeStatus($id);
        return $this->successResponse($manageStates, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function createState(Request $request)
    {
        $data = $request->only(['state_name', 'status', 'created_by']);

        $manageStateValidator = $this->manageStateValidator->validate($data);

        if ($manageStateValidator->fails()) {
            $errors = $manageStateValidator->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        } else {
            $response = $this->manageStateRepository->createState($data);

            if ($response == 'State Already Exist') {
                return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
            } else {
                return $this->successResponse($response, "State Added Successfully. Waiting for Approval", Response::HTTP_CREATED);
            }
        }
    }

    public function updateState(Request $request, $id)
    {
        $data = $request->only(['id', 'state_name', 'status', 'created_by']);

        $manageStateValidator = $this->manageStateValidator->validate($data);

        if ($manageStateValidator->fails()) {
            $errors = $manageStateValidator->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        } else {
            $response = $this->manageStateRepository->updateState($data, $id);

            if ($response == 'State Already Exist') {
                return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
            } else {
                return $this->successResponse($response, "State Updated Successfully.", Response::HTTP_CREATED);
            }
        }
    }
}
