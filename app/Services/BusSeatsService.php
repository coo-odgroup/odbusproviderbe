<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoardingDroping;
use App\Models\Location;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use App\Repositories\BoardingDropingRepository;
use Exception;
use InvalidArgumentException;
use App\AppValidator\BoardingDropingValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class BoardingDropingController extends Controller
{
    use ApiResponser;
    protected $boardingDropingRepository;
    protected $boardingDropingValidator;

    public function __construct(BoardingDropingRepository $boardingDropingRepository, BoardingDropingValidator $boardingDropingValidator)
    {
        $this->boardingDropingRepository = $boardingDropingRepository;
        $this->boardingDropingValidator = $boardingDropingValidator;
    }

    public function getAllBoardingDroping()
    {
        $boardingdroping = $this->boardingDropingRepository->getAll();
        return $this->successResponse($boardingdroping, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function createBoardingDroping(Request $request)
    {
        $data = $request->only([
            'location_id',
            'boarding_point',
            'created_by',
        ]);

        $boardingdropingValidation = $this->boardingDropingValidator->validate($data);
        if ($boardingdropingValidation->fails()) {
            $errors = $boardingdropingValidation->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        try {
            $response = $this->boardingDropingRepository->save($data);
            return $this->successResponse($response, "Bus Stoppage Added", Response::HTTP_CREATED);
        } catch (Exception $e) {
            Log::error("Error creating boarding dropping: " . $e->getMessage());
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function updateBoardingDroping(Request $request, $id)
    {
        $data = $request->only([
            'location_id',
            'boarding_point',
            'created_by'
        ]);

        $boardingdropingValidation = $this->boardingDropingValidator->validate($data);
        if ($boardingdropingValidation->fails()) {
            $errors = $boardingdropingValidation->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        try {
            $response = $this->boardingDropingRepository->update($data, $id);
            return $this->successResponse($response, "Bus Stoppage Updated", Response::HTTP_CREATED);
        } catch (Exception $e) {
            Log::error("Error updating boarding dropping: " . $e->getMessage());
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function deleteBoardingDroping($id)
    {
        try {
            $response = $this->boardingDropingRepository->delete($id);
            return $this->successResponse($response, "Bus Stoppage Deleted", Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            Log::error("Error deleting boarding dropping: " . $e->getMessage());
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function getBoardingDroping($id)
    {
        try {
            $boardingDropingID = $this->boardingDropingRepository->getById($id);
            return $this->successResponse($boardingDropingID, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error("Error fetching boarding dropping: " . $e->getMessage());
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function getBoardingDropingbyLoacationId($id)
    {
        try {
            $boardingDropingID = $this->boardingDropingRepository->getByLocationId($id);
            return $this->successResponse($boardingDropingID, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error("Error fetching by location id: " . $e->getMessage());
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    ////data table//////
    public function getBoardingDropingDT(Request $request)
    {
        $boardingDroping = $this->boardingDropingRepository->getBoardingDropingDT($request);
        return $this->successResponse($boardingDroping, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function boardingData(Request $request)
    {
        $boardingDroping = $this->boardingDropingRepository->boardingData($request);
        return $this->successResponse($boardingDroping, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function createBoarding(Request $request)
    {
        $data = $request->only([
            'location_id',
            'name',
            'type',
            'created_by',
        ]);

        $boardingdropingValidation = $this->boardingDropingValidator->validate($data);
        if ($boardingdropingValidation->fails()) {
            $errors = $boardingdropingValidation->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        try {
            $this->boardingDropingRepository->createBordingDroping($data);
            return $this->successResponse($data, Config::get('constants.RECORD_ADDED'), Response::HTTP_CREATED);
        } catch (Exception $e) {
            Log::error("Error creating boarding: " . $e->getMessage());
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function changeStatus($locationId)
    {
        try {
            $response = $this->boardingDropingRepository->changeStatus($locationId);
            return $this->successResponse($response, "Bus Stoppage Status Updated", Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            Log::error("Error changing status: " . $e->getMessage());
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }
}
