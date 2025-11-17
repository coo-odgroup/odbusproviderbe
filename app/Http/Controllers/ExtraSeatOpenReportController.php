<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ExtraSeatOpenReportRepository;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ExtraSeatOpenReportController extends Controller
{
    use ApiResponser;


    protected $extraseatopenreportRepository;

    public function __construct(ExtraSeatOpenReportRepository $extraseatopenreportRepository)
    {

        $this->extraseatopenreportRepository = $extraseatopenreportRepository;
    }

    public function getAllextraseatopen(Request $request)
    {

        $extraseatopen = $this->extraseatopenreportRepository->getAll($request);
        return $this->successResponse($extraseatopen, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }
}
