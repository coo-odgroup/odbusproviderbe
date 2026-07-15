<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusSchedule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use App\Repositories\BusScheduleRepository;
use App\AppValidator\BusScheduleValidator;
use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class BusScheduleController extends Controller
{
    use ApiResponser;

    protected $busScheduleValidator;
    protected $busScheduleRepository;

    public function __construct(busScheduleRepository $busScheduleRepository, BusScheduleValidator $busScheduleValidator)
    {

        $this->busScheduleValidator = $busScheduleValidator;
        $this->busScheduleRepository = $busScheduleRepository;
    }
    public function getAllBusSchedule(Request $request)
    {

        $busSchedule = $this->busScheduleRepository->getAll();
        return $this->successResponse($busSchedule, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function scheduleCronJob()
    {

        $busSchedule = $this->busScheduleRepository->scheduleCronJob();
        return $this->successResponse($busSchedule, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function syncBusSeatCount()
    {

        $syncBusSeatCount = $this->busScheduleRepository->syncBusSeatCount();
        return $this->successResponse($syncBusSeatCount, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function removeOldBusScheduleCronjob()
    {

        $busSchedule = $this->busScheduleRepository->removeOldBusScheduleCronjob();
        return $this->successResponse($busSchedule, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getAllBusScheduleDT(Request $request)
    {

        $busSchedule = $this->busScheduleRepository->getDatatable($request);
        return $this->successResponse($busSchedule, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function busScheduleById($id)
    {
        return $this->busScheduleRepository->busScheduleById($id);;
    }


    public function busSchedulerData(Request $request)
    {
        $busSchedule = $this->busScheduleRepository->busSchedulerData($request);
        return $this->successResponse($busSchedule, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function createBusSchedule(Request $request)
    {
        $data = $request->only([
            'bus_id',
            'created_by',
            'running_cycle',
            'entry_date'
        ]);
        $busScheduleValidation = $this->busScheduleValidator->validate($data);
        if ($busScheduleValidation->fails()) {
            $errors = $busScheduleValidation->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        } else {

            $response = $this->busScheduleRepository->save($data);

            if ($response == 'Bus Schedule Already Exist') {
                return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
            } elseif ($response == 'Can Not Add Old Date') {
                return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
            } else {
                return $this->successResponse($response, "Bus Schedule Added", Response::HTTP_CREATED);
            }
        }
    }

    public function updateBusSchedule(Request $request, $id)
    {
        $data = $request->only([
            'bus_id',
            'entry_date',
            'created_by',
            'running_cycle'
        ]);


        $response = $this->busScheduleRepository->update($data, $id);

        if ($response == 'Can Not Add Old Date') {
            return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
        } else {
            return $this->successResponse($response, "Bus Schedule Updated", Response::HTTP_CREATED);
        }
    }

    public function deleteBusSchedule($id)
    {
        try {

            $response = $this->busScheduleRepository->delete($id);
            return $this->successResponse($response, "Bus Schedule Deleted", Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function getBusSchedule($id)
    {
        try {
            $busSchedule = $this->busScheduleRepository->getById($id);
            return $this->successResponse($busSchedule, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function changeStatus($id)
    {
        try {

            $response = $this->busScheduleRepository->changeStatus($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($response, "Bus Schedule Status Updated", Response::HTTP_OK);
    }


    public function unscheduledbuslist()
    {
        try {

            $response = $this->busScheduleRepository->unscheduledbuslist();
            return $this->successResponse($response, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }
}
