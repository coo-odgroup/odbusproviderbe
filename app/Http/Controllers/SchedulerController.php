<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\SchedulerRepository;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SchedulerController extends Controller
{
    use ApiResponser;
    protected $schedulerRepository;

    public function __construct(SchedulerRepository $schedulerRepository)
    {

        $this->schedulerRepository = $schedulerRepository;
    }

    public function scheduleRefund(Request $request)
    {
        $scheduleRefundData = $this->schedulerRepository->scheduleRefund($request);
        return $this->successResponse($scheduleRefundData, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function scheduleRefundSelected(Request $request)
    {
        $scheduleRefundSelectedData = $this->schedulerRepository->scheduleRefundSelected($request);
        return $this->successResponse($scheduleRefundSelectedData, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function completeRefund(Request $request)
    {
        $completeRefundData = $this->schedulerRepository->completeRefund($request);
        return $this->successResponse($completeRefundData, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }
}
