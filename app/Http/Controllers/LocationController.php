<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LocationService;
use App\Repositories\LocationRepository;
use App\AppValidator\LocationValidator;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class LocationController extends Controller
{
    use ApiResponser;

    protected $locationService;
    protected $locationValidator;
    protected $locationRepository;

    public function __construct(
        LocationService $locationService,
        LocationValidator $locationValidator,
        LocationRepository $locationRepository
    ) {
        $this->locationService = $locationService;
        $this->locationValidator = $locationValidator;
        $this->locationRepository = $locationRepository;
    }

    public function getAllLocations()
    {
        $locations = $this->locationRepository->getAll();
        return $this->successResponse($locations, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getlocationbyID($id)
    {
        try {
            $locations = $this->locationRepository->getById($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($locations, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function deletelocation($id)
    {
        try {
            $this->locationRepository->delete($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, Config::get('constants.RECORD_REMOVED'), Response::HTTP_ACCEPTED);
    }

    public function getLocationDT(Request $request)
    {
        $locations = $this->locationRepository->getAllLocationDT($request);
        return $this->successResponse($locations, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function locationsData(Request $request)
    {
        $locations = $this->locationRepository->locationsData($request);
        return $this->successResponse($locations, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function addLocation(Request $request)
    {
        $data = $request->only(['name', 'synonym', 'state_id', 'created_by']);
        $response = $this->locationRepository->add($data);

        if ($response === 'Location Already Exist') {
            return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
        }

        return $this->successResponse($response, "Location Added Successfully. Waiting for Approval", Response::HTTP_CREATED);
    }

    public function editLocation(Request $request, $id)
    {
        $data = $request->only(['name', 'synonym', 'state_id', 'url', 'created_by']);
        $locationValidation = $this->locationValidator->validate($data);

        if ($locationValidation->fails()) {
            return $this->errorResponse($locationValidation->errors()->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        DB::beginTransaction();

        try {
            $response = $this->locationRepository->edit($data, $id);

            if ($response === 'Location Already Exist') {
                DB::rollBack();
                return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
            }

            DB::commit();
            return $this->successResponse($response, "Location Updated", Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating location: '.$e->getMessage(), ['id' => $id, 'payload' => $data]);
            return $this->errorResponse(Config::get('constants.RECORD_NOT_FOUND'), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function changeStatus($id)
    {
        try {
            $this->locationRepository->changeStatus($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, "Location Status Updated", Response::HTTP_ACCEPTED);
    }

    public function filterLocation(Request $request)
    {
        $prod = $this->locationRepository->filter($request);
        return $this->successResponse($prod, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }
}
