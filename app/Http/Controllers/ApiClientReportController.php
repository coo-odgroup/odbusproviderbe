<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Services\ApiClientReportService;
use App\Traits\ApiResponser;
use Exception;
use InvalidArgumentException;
use App\AppValidator\ApiClientWalletValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;


class ApiClientReportController extends Controller
{
    use ApiResponser;
    protected $ApiClientReportService;
    
    public function __construct(ApiClientReportService $ApiClientReportService)
    {
       
        $this->ApiClientReportService = $ApiClientReportService;
    }

    public function getAllData(Request $request) 
    {           
        $data = $this->ApiClientReportService->getAllData($request);
        return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function getAllCancelData(Request $request) 
    {           
        $data = $this->ApiClientReportService->getAllCancelData($request);
        return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }  

    public function datewiseroute(Request $request) 
    {           
        $data = $this->ApiClientReportService->datewiseroute($request);
        return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 

   
}
