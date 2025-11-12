<?php

namespace App\Http\Controllers;

use App\Services\ManageStateService;
use App\Traits\ApiResponser;
use App\AppValidator\ManageStateValidator;
use App\Repositories\ManageStateRepository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class ManageStateController extends Controller
{
    use ApiResponser;

    protected ManageStateService $manageStateService;
    protected ManageStateValidator $manageStateValidator;
    protected ManageStateRepository $manageStateRepository;

    /**
     * ManageStateController Constructor
     *
     * @param ManageStateService $manageStateService
     * @param ManageStateValidator $manageStateValidator
     * @param ManageStateRepository $manageStateRepository
     */
    public function __construct(
        ManageStateService $manageStateService,
        ManageStateValidator $manageStateValidator,
        ManageStateRepository $manageStateRepository
    ) {
        $this->manageStateService = $manageStateService;
        $this->manageStateValidator = $manageStateValidator;
        $this->manageStateRepository = $manageStateRepository;
    }

    /**
     * Get list of states
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function statelist()
    {
        $manageStates = $this->manageStateRepository->statelist();

        return $this->successResponse(
            $manageStates,
            Config::get('constants.RECORD_FETCHED'),
            Response::HTTP_OK
        );
    }

    /**
     * Get all states (with filters/pagination if any)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllStates(Request $request)
    {
        $manageStates = $this->manageStateRepository->getAllstate($request);

        return $this->successResponse(
            $manageStates,
            Config::get('constants.RECORD_FETCHED'),
            Response::HTTP_OK
        );
    }

    /**
     * Change the status of a state
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus(int $id)
    {
        $manageStates = $this->manageStateRepository->changeStatus($id);

        return $this->successResponse(
            $manageStates,
            Config::get('constants.RECORD_FETCHED'),
            Response::HTTP_OK
        );
    }

    /**
     * Create a new state
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createState(Request $request)
    {
        dd(900);
        $data = $request->only(['state_name', 'status', 'created_by']);
        $validator = $this->manageStateValidator->validate($data);

        if ($validator->fails()) {
            return $this->errorResponse(
                $validator->errors()->toJson(),
                Response::HTTP_PARTIAL_CONTENT
            );
        }

        

        //$response = $this->manageStateService->createState($data);

        if ($response === 'State Already Exist') {
            return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
        }

        return $this->successResponse(
            $response,
            'State Added Successfully. Waiting for Approval',
            Response::HTTP_CREATED
        );
    }

    /**
     * Update an existing state
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateState(Request $request, int $id)
    {
        $data = $request->only(['state_name', 'status', 'created_by']);
        $validator = $this->manageStateValidator->validate($data);

        if ($validator->fails()) {
            return $this->errorResponse(
                $validator->errors()->toJson(),
                Response::HTTP_PARTIAL_CONTENT
            );
        }

        $response = $this->manageStateService->updateState($data, $id);

        if ($response === 'State Already Exist') {
            return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
        }

        return $this->successResponse(
            $response,
            'State Updated Successfully.',
            Response::HTTP_CREATED
        );
    }
}
