<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusStoppageTiming;
use App\Repositories\BusStoppageTimingRepository;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use App\AppValidator\BusStoppageTimingValidator;
use Symfony\Component\HttpFoundation\Response;

class BusStoppageTimingController extends Controller
{
    use ApiResponser;
    protected $busStoppageTimingRepository;
    protected $BusStoppageTimingValidator;

    public function __construct(busStoppageTimingRepository $busStoppageTimingRepository, BusStoppageTimingValidator $BusStoppageTimingValidator)
    {
        $this->busStoppageTimingRepository = $busStoppageTimingRepository;
        $this->BusStoppageTimingValidator = $BusStoppageTimingValidator;
    }


    public function getAllBusStoppageTiming()
    {

        $busstoppageTiming = $this->busStoppageTimingRepository->getAll();
        return $this->successResponse($busstoppageTiming, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function createBusStoppageTiming(Request $request)
    {
        $data = $request->only([
            'ticket_price_id',
            'boarding_droping_name',
            'journey_time',
            'created_by'
        ]);
        $busStoppageTimingValidation = $this->BusStoppageTimingValidator->validate($data);
        if ($busStoppageTimingValidation->fails()) {
            $errors = $busStoppageTimingValidation->errors();
            return $this->errorResponse($errors, Response::HTTP_PARTIAL_CONTENT);
        }
        try {
            $this->busStoppageTimingRepository->save($data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, Config::get('constants.RECORD_ADDED'), Response::HTTP_CREATED);
    }

    public function updateBusStoppageTiming(Request $request, $id)
    {
        $data = $request->only([
            'ticket_price_id',
            'boarding_droping_name',
            'journey_time',
            'created_by'
        ]);

        $busStoppageTimingValidation = $this->BusStoppageTimingValidator->validate($data);
        if ($busStoppageTimingValidation->fails()) {
            $errors = $busStoppageTimingValidation->errors();
            return $this->errorResponse($errors, Response::HTTP_PARTIAL_CONTENT);
        }

        try {
            $this->busStoppageTimingRepository->update($data, $id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }

        return $this->successResponse(null, Config::get('constants.RECORD_UPDATED'), Response::HTTP_CREATED);
    }

    public function deleteBusStoppageTiming($id)
    {
        try {
            $this->busStoppageTimingRepository->delete($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, Config::get('constants.RECORD_REMOVED'), Response::HTTP_ACCEPTED);
    }

    public function getBusStoppageTiming($id)
    {
        $busstoppageTiming = $this->busStoppageTimingRepository->getById($id);
        return $this->successResponse($busstoppageTiming, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function busStoppageTimingbyBusId($id)
    {
        $busstoppageTiming = $this->busStoppageTimingRepository->busStoppageTimingbyBusId($id);
        return $this->successResponse($busstoppageTiming, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function busStoppageTimingbyBusIdClone($id)
    {
        $busstoppageTiming = $this->busStoppageTimingRepository->busStoppageTimingbyBusIdClone($id);
        return $this->successResponse($busstoppageTiming, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }
}
