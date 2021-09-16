<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BusCancellationReportService;

use Illuminate\Support\Facades\Validator;
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
    
    public function __construct(BusCancellationReportService $buscancellationreportService)
    {
        $this->buscancellationreportService = $buscancellationreportService;        
    }

    public function getAll()
    {
        $extraseatopen = $this->buscancellationreportService->getAll();
        return $this->successResponse($extraseatopen,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}