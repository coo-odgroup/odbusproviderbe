<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ExtraSeatOpenReportService;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ExtraSeatOpenReportController extends Controller
{
    use ApiResponser;
   
    protected $extraseatopenreportService;    
    
    public function __construct(ExtraSeatOpenReportService $extraseatopenreportService)
    {
        $this->extraseatopenreportService = $extraseatopenreportService;        
    }

    public function getAllextraseatopen()
    {
        $extraseatopen = $this->extraseatopenreportService->getAll();
        return $this->successResponse($extraseatopen,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}