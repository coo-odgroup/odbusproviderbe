<?php
namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use App\AppValidator\ManageStateValidator;
use App\Repositories\ManageStateRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ManageStateController extends Controller
{
    use ApiResponser;

    protected ManageStateValidator $manageStateValidator;
    protected ManageStateRepository $manageStateRepository;

    public function __construct(
        ManageStateValidator $manageStateValidator,
        ManageStateRepository $manageStateRepository
    ) {
        $this->manageStateValidator = $manageStateValidator;
        $this->manageStateRepository = $manageStateRepository;
    }

    public function statelist()
    {
        try {
            $manageStates = $this->manageStateRepository->statelist();

            return $this->successResponse(
                $manageStates,
                Config::get('constants.RECORD_FETCHED'),
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            Log::error('statelist error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to fetch states.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllState(Request $request)
    {
        try {
            // pass a plain array so repository can use indices like an array
            $input = $request->all();
            $manageStates = $this->manageStateRepository->getAllstate($input);

            return $this->successResponse(
                $manageStates,
                Config::get('constants.RECORD_FETCHED'),
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            Log::error('getAllState error: '.$e->getMessage(), ['trace' => $e->getTraceAsString(), 'input' => $request->all()]);
            return $this->errorResponse('Failed to fetch states.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function changeStatus(int $id)
    {
        try {
            if (!$id) {
                return $this->errorResponse('Invalid state id.', Response::HTTP_BAD_REQUEST);
            }

            $state = $this->manageStateRepository->changeStatus($id);

            if (!$state) {
                return $this->errorResponse('State not found.', Response::HTTP_NOT_FOUND);
            }

            return $this->successResponse(
                $state,
                'State status changed successfully',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            Log::error('changeStatus error: '.$e->getMessage(), ['trace' => $e->getTraceAsString(), 'id' => $id]);
            return $this->errorResponse('Failed to change state status.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createState(Request $request)
    {
        try {
            $data = $request->only(['state_name', 'status', 'created_by']);

            $validator = $this->manageStateValidator->validate($data);

            if ($validator->fails()) {
                return $this->errorResponse(
                    $validator->errors()->toJson(),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            $response = $this->manageStateRepository->createState($data);

            if ($response === 'State Already Exist') {
                return $this->errorResponse($response, Response::HTTP_CONFLICT);
            }

            return $this->successResponse(
                $response,
                'State Added Successfully. Waiting for Approval',
                Response::HTTP_CREATED
            );
        } catch (\Throwable $e) {
            Log::error('createState error: '.$e->getMessage(), ['trace' => $e->getTraceAsString(), 'input' => $request->all()]);
            return $this->errorResponse('Failed to create state.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateState(Request $request, int $id)
    {
        try {
            $data = $request->only(['state_name', 'status', 'created_by']);

            $validator = $this->manageStateValidator->validate($data);

            if ($validator->fails()) {
                return $this->errorResponse(
                    $validator->errors()->toJson(),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            $response = $this->manageStateRepository->updateState($data, $id);

            if ($response === 'State Already Exist') {
                return $this->errorResponse($response, Response::HTTP_CONFLICT);
            }

            return $this->successResponse(
                $response,
                'State Updated Successfully.',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            Log::error('updateState error: '.$e->getMessage(), ['trace' => $e->getTraceAsString(), 'id' => $id, 'input' => $request->all()]);
            return $this->errorResponse('Failed to update state.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
