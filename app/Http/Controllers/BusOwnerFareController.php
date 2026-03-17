<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\ApiResponser;
use App\AppValidator\BusOwnerFareValidator;
use App\Repositories\BusOwnerFareRepository;
use Exception;


class BusOwnerFareController extends Controller
{
    use ApiResponser;

    protected $busOwnerFareValidator;
    protected $busOwnerFareRepository;

    public function __construct(
        BusOwnerFareRepository $busOwnerFareRepository,
        BusOwnerFareValidator $busOwnerFareValidator
    ) {
        $this->busOwnerFareValidator = $busOwnerFareValidator;
        $this->busOwnerFareRepository = $busOwnerFareRepository;
    }

    public function getAllBusOwnerFare()
    {
        $busOwnerFare = $this->busOwnerFareRepository->getAll();
        return $this->successResponse($busOwnerFare, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getBusOwnerFareDT(Request $request)
    {
        $busOwnerFare = $this->busOwnerFareRepository->getDatatable($request);
        return $this->successResponse($busOwnerFare, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function busOwnerFareData(Request $request)
    {
        $busOwnerFare = $this->busOwnerFareRepository->busOwnerFareData($request);
        return $this->successResponse($busOwnerFare, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function createBusOwnerFare(Request $request)
    {
        $data = $request->only([
            'date',
            'seater_price',
            'sleeper_price',
            'reason',
            'created_by',
            'bus_operator_id',
            'bus_id',
            'source_id',
            'destination_id',
        ]);

        // Log::info($data);

        $busOwnerFareValidation = $this->busOwnerFareValidator->validate($data);
        if ($busOwnerFareValidation->fails()) {
            $errors = $busOwnerFareValidation->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        try {
            $this->busOwnerFareRepository->save($data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($data, "Bus Owner Fare Added", Response::HTTP_CREATED);
    }


    public function updateBusOwnerFare(Request $request, $id)
    {
        $data = $request->only([
            'date',
            'operator_id',
            'seater_price',
            'sleeper_price',
            'reason',
            'created_by',
            'bus_id'
        ]);

        $validation = $this->busOwnerFareValidator->validate($data);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors()->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        try {
            $response = $this->busOwnerFareRepository->update($request, $id);
            return $this->successResponse($response, "Bus Owner Fare Updated", Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function deleteBusOwnerFare($id)
    {
        try {
            $response = $this->busOwnerFareRepository->delete($id);
            return $this->successResponse($response, "Bus Owner Fare Deleted", Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function getBusOwnerFare($id)
    {
        try {
            $busOwnerFareID = $this->busOwnerFareRepository->getById($id);
            return $this->successResponse($busOwnerFareID, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function changeStatus($id)
    {
        try {
            $response = $this->busOwnerFareRepository->changeStatus($id);
            return $this->successResponse($response, "Bus Owner Fare Status Updated", Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }
}
