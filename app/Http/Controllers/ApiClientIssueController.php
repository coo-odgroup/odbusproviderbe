<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\ApiClientIssueService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use InvalidArgumentException;
use Exception;
use App\AppValidator\AgentValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;


class ApiClientIssueController extends Controller
{
    use ApiResponser;
    
    protected $ApiClientIssueService;
    
    public function __construct(ApiClientIssueService $ApiClientIssueService)
    {
        $this->ApiClientIssueService = $ApiClientIssueService;
    }


    public function apiclientissuetype() {

      $data = $this->ApiClientIssueService->apiclientissuetype();
      return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }

    public function apiclientissuesubtype(Request $request) {

      $data = $this->ApiClientIssueService->apiclientissuesubtype($request);
      return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    } 

   

    // public function changeStatus(Request $request) {
      
    //     try{
    //       $this->ApiClientIssueService->changeStatus($request);
    //     }
    //     catch (Exception $e){
    //         return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
    //     }
    //     return $this->successResponse(null, "Agent Status Updated", Response::HTTP_ACCEPTED);
    //   }
}
