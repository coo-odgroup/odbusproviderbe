<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusCancelled;
use App\Models\Bus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use Exception;
use InvalidArgumentException;
use App\AppValidator\CancelBusValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use App\Repositories\BusCancelledRepository;

class BusCancelledController extends Controller
{
    use ApiResponser;
    protected $cancelBusValidator;
    protected $busCancelledRepository;

    public function __construct(BusCancelledRepository $busCancelledRepository, CancelBusValidator $cancelBusValidator)
    {

        $this->cancelBusValidator = $cancelBusValidator;
        $this->busCancelledRepository = $busCancelledRepository;
    }
    public function getAllBusCancelled()
    {


        $buscancelled = $this->busCancelledRepository->getAll();
        return $this->successResponse($buscancelled, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function removeOldBusCancelledCronjob()
    {


        $buscancelled = $this->busCancelledRepository->removeOldBusCancelledCronjob();
        return $this->successResponse($buscancelled, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getBusCancelledDT(Request $request)
    {

        $buscancelled = $this->busCancelledRepository->getBusCancelledDT($request);
        return $this->successResponse($buscancelled, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }
    public function busCancelledData(Request $request)
    {

        $buscancelled = $this->busCancelledRepository->busCancelledData($request);
        return $this->successResponse($buscancelled, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function createBusCancelled(Request $request)
    {

        $data = $request->only([

            'bus_id',
            'bus_operator_id',
            'cancelled_date',
            'reason',
            'other_reson',
            'cancelled_by',
            'buses',
            'month',
            'year'
        ]);

        $busCancelledValidation = $this->cancelBusValidator->validate($data);
        if ($busCancelledValidation->fails()) {
            $errors = $busCancelledValidation->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }
        try {

            $data = $this->busCancelledRepository->save($data);
            return $this->successResponse($data, "Bus Cancellation Record Added", Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function busCancelledbyowner(Request $request)
    {
        $message = '';
        $data = $request->only([

            'bus_id',
            'bus_operator_id',
            'cancelled_date',
            'reason',
            'other_reson',
            'cancelled_by',
            'buses',
            'month',
            'year'
        ]);

        $busCancelledValidation = $this->cancelBusValidator->validate($data);
        if ($busCancelledValidation->fails()) {
            $errors = $busCancelledValidation->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        } else {

            $response = $this->busCancelledRepository->busCancelledbyowner($data);


            if ($response['msg'] == 'Some seat already booked on') {
                $message = $response['msg'] . ' ' . $response['dt'] . 'for cancellation of bus plz contact ODBUS Support team';
                return $this->errorResponse($message, Response::HTTP_PARTIAL_CONTENT);
            } else {
                return $this->successResponse($response['msg'], "Bus Cancelled Successfully", Response::HTTP_CREATED);
            }
        }
    }
    public function updateBusCancelled(Request $request, $id)
    {
        $data = $request->only([
            'bus_id',
            'bus_operator_id',
            'cancelled_date',
            'reason',
            'other_reson',
            'cancelled_by',
            'dateLists',
            'month',
            'year',
            'buses'
        ]);

        $busCancelledValidation = $this->cancelBusValidator->validate($data);
        if ($busCancelledValidation->fails()) {
            $errors = $busCancelledValidation->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }
        try {

            $response = $this->busCancelledRepository->update($data, $id);
            return $this->successResponse($response, "Bus Cancellation Record Updated", Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function deleteBusCancelled($id)
    {
        try {

            $response = $this->busCancelledRepository->delete($id);
            return $this->successResponse($response, "Bus Cancellation Record Deleted", Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
    public function getBusCancelled($id)
    {
        try {

            $buscancelledID = $this->busCancelledRepository->getByBusId($id);
            return $this->successResponse($buscancelledID, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
    public function changeStatus($id)
    {
        try {

            $response = $this->busCancelledRepository->changeStatus($id);
            return $this->successResponse($response, "Bus Cancellation Status Updated", Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }
}
