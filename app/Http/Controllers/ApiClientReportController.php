<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Services\ApiClientReportService;
use Illuminate\Support\Facades\DB;
use App\Models\BusOwnerFare;
use App\Traits\ApiResponser;
use Exception;
use InvalidArgumentException;
use App\AppValidator\ApiClientWalletValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use App\Repositories\ApiClientReportRepository; //subhasis Mohanty 12-9-25


class ApiClientReportController extends Controller
{
    use ApiResponser;
    protected $ApiClientReportService;
    protected $ApiClientReportRepository;
    
    public function __construct(ApiClientReportService $ApiClientReportService,
                                ApiClientReportRepository $ApiClientReportRepository )
    {
       
        $this->ApiClientReportService = $ApiClientReportService;
        $this->ApiClientReportRepository = $ApiClientReportRepository;
    }

    // public function getAllData(Request $request) 
    // {           
    //     $data = $this->ApiClientReportService->getAllData($request);
    //     return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // } 

     public function getAllData(Request $request) 
    {           
        $data = $this->ApiClientReportRepository->getAllData($request);
        return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    // public function getAllCancelData(Request $request) 
    // {           
    //     $data = $this->ApiClientReportService->getAllCancelData($request);
    //     return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }  
    public function getAllCancelData(Request $request) 
    {           
        $data = $this->ApiClientReportRepository->getAllCancelData($request);
        return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }  

    // public function datewiseroute(Request $request) 
    // {           
    //     $data = $this->ApiClientReportService->datewiseroute($request);
    //     return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // } 

    public function datewiseroute(Request $request) 
    {           
        $data = $this->ApiClientReportRepository->datewiseroute($request);
        return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 

   
}
