<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ContactReportService;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;


class ContactReportController extends Controller
{
    use ApiResponser;
   
    protected $contactreportService; 

    
    public function __construct(ContactReportService $contactreportService)
    {
        $this->contactreportService = $contactreportService;        
    }

    public function getData(Request $request)
    {    
        $contactData = $this->contactreportService->getData($request);
        return $this->successResponse($contactData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function deleteData($id)
    {    
        $contactData = $this->contactreportService->deleteData($id);
        return $this->successResponse($contactData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}
