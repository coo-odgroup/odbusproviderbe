<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\OwnerPaymentReportRepository;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OwnerPaymentReportController extends Controller
{
    use ApiResponser;

    protected $ownerpaymentreportRepository;

    public function __construct(OwnerPaymentReportRepository $ownerpaymentreportRepository)
    {
        $this->ownerpaymentreportRepository = $ownerpaymentreportRepository;
    }

    public function getData(Request $request)
    {
        $ownerpayment = $this->ownerpaymentreportRepository->getData($request);
        return $this->successResponse($ownerpayment, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }
}
