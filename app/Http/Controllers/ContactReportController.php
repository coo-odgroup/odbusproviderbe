<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Services\ContactReportService;
use App\Repositories\ContactReportRepository;
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
    protected $contactreportRepository;

    
    public function __construct(//ContactReportService $contactreportService,
                                ContactReportRepository $contactreportRepository )
    {
        //$this->contactreportService = $contactreportService;  
        $this->contactreportRepository = $contactreportRepository;      
    }

    // public function getData(Request $request)
    // {    
    //     $contactData = $this->contactreportService->getData($request);
    //     return $this->successResponse($contactData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }
     public function getData(Request $request)
    {    
        $contactData = $this->contactreportRepository->getData($request);
        return $this->successResponse($contactData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    // public function deleteData($id)
    // {    
    //     $contactData = $this->contactreportService->deleteData($id);
    //     return $this->successResponse($contactData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }
public function deleteData($id)
    {    
        $contactData = $this->contactreportRepository->deleteData($id);
        return $this->successResponse($contactData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
}
