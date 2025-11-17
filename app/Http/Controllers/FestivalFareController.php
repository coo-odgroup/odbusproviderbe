<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\AppValidator\FestivalFareValidator;
use App\Repositories\FestivalFareRepository;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;
use InvalidArgumentException;

class FestivalFareController extends Controller
{
    use ApiResponser;

    protected $festivalFareValidator;
    protected $festivalFareRepository;

    public function __construct(FestivalFareRepository $festivalFareRepository, FestivalFareValidator $festivalFareValidator)
    {
        $this->festivalFareRepository = $festivalFareRepository;
        $this->festivalFareValidator = $festivalFareValidator;
    }

    public function getAllFestivalFare()
    {
        $busOwnerFare = $this->festivalFareRepository->getAll();
        return $this->successResponse($busOwnerFare, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getFestivalFareDT(Request $request)
    {
        $busOwnerFare = $this->festivalFareRepository->getDatatable($request);
        return $this->successResponse($busOwnerFare, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function festivalFareData(Request $request)
    {
        $busOwnerFare = $this->festivalFareRepository->festivalFareData($request);
        return $this->successResponse($busOwnerFare, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function createFestivalFare(Request $request)
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
            'destination_id'
        ]);

        $validation = $this->festivalFareValidator->validate($data);
        if ($validation->fails()) {
            return $this->errorResponse($validation->errors()->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        try {
            $this->festivalFareRepository->save($data);
            return $this->successResponse($data, "Bus Festival Fare Added", Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function updateFestivalFare(Request $request, $id)
    {
        $data = $request->only([
            'date',
            'bus_operator_id',
            'source_id',
            'destination_id',
            'seater_price',
            'sleeper_price',
            'reason',
            'created_by',
            'bus_id'
        ]);

        $validation = $this->festivalFareValidator->validate($data);
        if ($validation->fails()) {
            return $this->errorResponse($validation->errors()->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        try {
            $this->festivalFareRepository->update($data, $id);
            return $this->successResponse($data, "Bus Festival Fare Updated", Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function deleteFestivalFare($id)
    {
        try {
            $this->festivalFareRepository->delete($id);
            return $this->successResponse(null, "Bus Festival Fare Deleted", Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function getFestivalFare($id)
    {
        try {
            $fare = $this->festivalFareRepository->getById($id);
            return $this->successResponse($fare, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function changeStatus($id)
    {
        DB::beginTransaction();

        try {
            $fare = $this->festivalFareRepository->changeStatus($id);
            DB::commit();
            return $this->successResponse($fare, "Festival Fare Status Updated", Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return $this->errorResponse('Unable to change status', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
