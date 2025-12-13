<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ApiUserCancelTicketReportRepository;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiUserCancelTicketReportController extends Controller
{
    use ApiResponser;

   protected $apiusercancelticketreportRepository;

    public function __construct(ApiUserCancelTicketReportRepository $apiusercancelticketreportRepository)
    {
       $this->apiusercancelticketreportRepository = $apiusercancelticketreportRepository;

    }
    public function getData(Request $request)
    {
        $cancelticketData = $this->apiusercancelticketreportRepository->getData($request);
        return $this->successResponse($cancelticketData, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

}
