<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\ApiClientIssueService;
use APP\Repositories\ApiClientIssueRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Exception;
use App\AppValidator\ApiClientIssueValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;


class ApiClientIssueController extends Controller
{
    use ApiResponser;
    
    protected $ApiClientIssueService;
    protected $ApiClientIssueValidator;
    protected $ApiClientIssueRepository;
    
    public function __construct(ApiClientIssueService $ApiClientIssueService,
                                ApiClientIssueValidator $ApiClientIssueValidator,
                                ApiClientIssueRepository $ApiClientIssueRepository)
    {
        $this->ApiClientIssueService = $ApiClientIssueService;
        $this->ApiClientIssueValidator = $ApiClientIssueValidator;
        $this->ApiClientIssueRepository = $ApiClientIssueRepository;
    }


    // public function apiclientissuetype() {

    //   $data = $this->ApiClientIssueService->apiclientissuetype();
    //   return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    // }

    public function apiclientissuetype() {

      $data = $this->ApiClientIssueRepository->apiclientissuetype();
      return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }

    // public function apiclientissuesubtype(Request $request) {

    //   $data = $this->ApiClientIssueService->apiclientissuesubtype($request);
    //   return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    // }

    public function apiclientissuesubtype(Request $request) {

      $data = $this->ApiClientIssueRepository->apiclientissuesubtype($request);
      return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }

    // public function apiclientissuedata(Request $request) {

    //   $data = $this->ApiClientIssueService->apiclientissuedata($request);
    //   return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    // } 

     public function apiclientissuedata(Request $request) {

      $data = $this->ApiClientIssueRepository->apiclientissuedata($request);
      return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    } 


    // public function allapiclientissuedata(Request $request) {

    //   $data = $this->ApiClientIssueService->allapiclientissuedata($request);
    //   return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    // } 

     public function allapiclientissuedata(Request $request) {

      $data = $this->ApiClientIssueRepository->allapiclientissuedata($request);
      return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    } 
    
    // public function apiclientissuestatue(Request $request) {

    //   $data = $this->ApiClientIssueService->apiclientissuestatue($request);
    //   return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    // } 

    public function apiclientissuestatue(Request $request) {

      $data = $this->ApiClientIssueRepository->apiclientissuestatue($request);
      return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    } 

    // public function addapiclientissue(Request $request) {
        
    //     $data = $request->only([
    //                 'issueType_id','issueSubType_id','reference_id','busId','operatorId','source','destination',
    //                 'message','user_id','created_by'                   
    //               ]);
       
             
    //           $ApiClientIssueValidator = $this->ApiClientIssueValidator->validate($data);
        
    //     if ($ApiClientIssueValidator->fails()) {
    //       $errors = $ApiClientIssueValidator->errors();
    //       return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
    //     }
    //     else
    //     {
    //          $data = $this->ApiClientIssueService->addapiclientissue($request);
    //         return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    //     }
        
      public function addapiclientissue(Request $request) {
        
        $data = $request->only([
                    'issueType_id','issueSubType_id','reference_id','busId','operatorId','source','destination',
                    'message','user_id','created_by'                   
                  ]);
       
             
              $ApiClientIssueValidator = $this->ApiClientIssueValidator->validate($data);
        
        if ($ApiClientIssueValidator->fails()) {
          $errors = $ApiClientIssueValidator->errors();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        else
        {
             $data = $this->ApiClientIssueRepository->addapiclientissue($request);
            return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
        }
           
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
        public function changeStatus(Request $request) {
      
        try{
          $this->ApiClientIssueRepository->changeStatus($request);
        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, "Agent Status Updated", Response::HTTP_ACCEPTED);
      }
}
