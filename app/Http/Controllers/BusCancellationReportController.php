<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\BusCancellationReportRepository;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class BusCancellationReportController extends Controller
{
    use ApiResponser;

    protected $buscancellationreportService;
    protected $buscancellationreportRepository;

    public function __construct(BusCancellationReportRepository $buscancellationreportRepository)
    {
        $this->buscancellationreportRepository = $buscancellationreportRepository;
    }

    public function getData(Request $request)
    {
        $buscancel = $this->buscancellationreportRepository->getData($request);
        return $this->successResponse($buscancel, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }
}
