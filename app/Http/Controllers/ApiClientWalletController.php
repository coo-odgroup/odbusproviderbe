<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Services\ApiClientWalletService;
use App\Traits\ApiResponser;
use Exception;
use InvalidArgumentException;
use App\AppValidator\ApiClientWalletValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class ApiClientWalletController extends Controller
{
    use ApiResponser;
    protected $ApiClientWalletService;
    protected $ApiClientWalletValidator;
    
    public function __construct(ApiClientWalletService $ApiClientWalletService, ApiClientWalletValidator $ApiClientWalletValidator)
    {
       
        $this->ApiClientWalletService = $ApiClientWalletService;
        $this->ApiClientWalletValidator = $ApiClientWalletValidator;
    }

   public function getAllData(Request $request) 
    {           
        $wallet = $this->ApiClientWalletService->getAllData($request);
        return $this->successResponse($wallet,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 

     public function agentWalletBalancedetails(Request $request) 
    {      
     
        $wallet = $this->ApiClientWalletService->agentWalletBalancedetails($request);
        return $this->successResponse($wallet,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 

    public function agentAllTransaction(Request $request) 
    {      
     
        $wallet = $this->ApiClientWalletService->agentAllTransaction($request);
        return $this->successResponse($wallet,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
  

    public function getData(Request $request) 
    {      

        $wallet = $this->ApiClientWalletService->getData($request);
        return $this->successResponse($wallet,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function allTransactionData(Request $request) 
    {      

        $wallet = $this->ApiClientWalletService->allTransactionData($request);
        return $this->successResponse($wallet,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function addAgentWallet(Request $request) 
    { 
        
        $data = $request->only(['transaction_id','reference_id','payment_via','amount','remarks','user_id']);

        $ApiClientWalletValidator = $this->ApiClientWalletValidator->validate($data);
        if ($ApiClientWalletValidator->fails()) {
            $errors = $ApiClientWalletValidator->errors();
            
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
          }
        try {
           $this->ApiClientWalletService->savePostData($request);
           return $this->successResponse($data,"Wallet request Added",Response::HTTP_CREATED);
        } catch (Exception $e) {
           return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
         
    }  


    public function changeStatus(Request $request, $id) 
    {       
    	 $data=$this->ApiClientWalletService->changeStatus($request,$id);
         if($data=='Invalid OTP'){
             return $this->errorResponse($data,Response::HTTP_PARTIAL_CONTENT);
         }else{
             return $this->successResponse($data,"Wallet request Updated",Response::HTTP_CREATED);
         }
        
    }  

    public function declineWlletReqStatus(Request $request, $id) 
    {       
    	$dd = $this->ApiClientWalletService->declineWlletReqStatus($request,$id);       

        if($dd !=' '){
        return $this->successResponse($dd,"Wallet Request Declined!",Response::HTTP_CREATED);
        }else{
            return $this->errorResponse($dd,Response::HTTP_PARTIAL_CONTENT);
        }
        
    } 
    
	   
    public function agentWalletBalance($id) 
    {       
         
        $wallet = $this->ApiClientWalletService->agentWalletBalance($id);
        return $this->successResponse($wallet,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
        
    }  
         
}
