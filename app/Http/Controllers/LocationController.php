<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Locationcode;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use App\AppValidator\LocationValidator;
use App\Repositories\LocationRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\LocationService;

class LocationController extends Controller
{
    use ApiResponser;
    /**
     * @var LocationService
     */
    protected $locationService;
    protected $locationValidator;
    protected $locationRepository;


    public function __construct(LocationRepository $locationRepository, LocationValidator $locationValidator,LocationService $locationService)
    {
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
        $data = $request->only([
            'name',
            'synonym',
            'state_id',
            'created_by',
        ]);

        $response = $this->locationRepository->add($data);

        if ($response == 'Location Already Exist') {
            return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
        } else {
            return $this->successResponse($response, "Location Added Successfully. Waiting for Approval", Response::HTTP_CREATED);
        }
    }


    public function editLocation(Request $request, $id)
    {
        $data = $request->only([
            'name',
            'synonym',
            'state_id',
            'url',
            'created_by'
        ]);

        $locationValidation = $this->locationValidator->validate($data);
        if ($locationValidation->fails()) {
            $errors = $locationValidation->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        DB::beginTransaction();
        try {

            $response = $this->locationRepository->edit($data, $id);


            if ($response === 'Location Already Exist') {
                DB::rollBack();
                return $this->errorResponse($response, Response::HTTP_CONFLICT);
            }

            DB::commit();
            return $this->successResponse($response, "Location Updated", Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('editLocation error: ' . $e->getMessage(), [
                'id' => $id,
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);
            return $this->errorResponse('Unable to update location', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function changeStatus($id)
    {

        try {
            $this->locationService->changeStatus($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, "Location Status Updated", Response::HTTP_OK);
    }

    public function filterLocation(request $request)
    {

        $prod = $this->locationRepository->filter($request);
        // $output ['status']=1;
        // $output ['message']='All Data Fetched Successfully';
        // $output ['result']=$prod;
        // return response($prod, 200);
        return $this->successResponse($prod, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }
}
