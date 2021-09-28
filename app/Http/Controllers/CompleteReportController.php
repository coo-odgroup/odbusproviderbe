<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CompleteReportService;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\AppValidator\CompleteReportValidator;


class CompleteReportController extends Controller
{
    use ApiResponser;
   
    protected $completereportService; 
    protected $CompleteReportValidator;

    
    public function __construct(CompleteReportService $completereportService,
                                CompleteReportValidator $completeReportValidator)
    {
        $this->completereportService = $completereportService;

        $this->completeReportValidator = $completeReportValidator;

        
    }


    public function getAll()
    {
        $completeData = $this->completereportService->getAll();
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function getData(Request $request)
    {
        // Log:: info($request); 
        // $data = $request->only(['bus_operator_id','date_range','payment_id','date_type','rows_number']);


        $completeData = $this->completereportService->getData($request);
        return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);


        //  $completeReportValidaton = $this->completeReportValidator->validate($data);
        // if ($completeReportValidaton->fails()) {
        //     $errors = $completeReportValidaton->errors();
            
        //     return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        //   }
        // try {
        //     $completeData = $this->completereportService->getData($request);
        //     return $this->successResponse($completeData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);

        // } /*catch (Exception $e) {
        //    return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        // }
      // return $this->successResponse($data,Config::get('constants.RECORD_ADDED'),Response::HTTP_CREATED); */
    }

}
