<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Services\AgentWalletService;
use App\Traits\ApiResponser;
use Exception;
use InvalidArgumentException;
use App\AppValidator\AgentWalletValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class AgentWalletController extends Controller
{
    use ApiResponser;
    protected $agentWalletService;
    protected $agentWalletValidator;
    
    public function __construct(AgentWalletService $agentWalletService, AgentWalletValidator $agentWalletValidator)
    {
       
        $this->agentWalletService = $agentWalletService;
        $this->agentWalletValidator = $agentWalletValidator;
    }

   public function getAllData(Request $request) 
    {      
     
        $wallet = $this->agentWalletService->getAllData($request);
        return $this->successResponse($wallet,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
  

    public function getData(Request $request) 
    {      

        $wallet = $this->agentWalletService->getData($request);
        return $this->successResponse($wallet,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function addAgentWallet(Request $request) 
    { 

        $data = $request->only(['transaction_id','reference_id','payment_via','amount','remarks','user_id']);

        $agentWalletValidator = $this->agentWalletValidator->validate($data);
        if ($agentWalletValidator->fails()) {
            $errors = $agentWalletValidator->errors();
            
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
          }
        try {
           $this->agentWalletService->savePostData($request);
           return $this->successResponse($data,"Wallet request Added",Response::HTTP_CREATED);
        } catch (Exception $e) {
            
           return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
         
    }  


    public function changeStatus(Request $request, $id) 
    {       
    	 $data=$this->agentWalletService->changeStatus($request,$id);
         if($data=='Invalid OTP'){
             return $this->errorResponse($data,Response::HTTP_PARTIAL_CONTENT);
         }else{
             return $this->successResponse($data,"Wallet request Updated",Response::HTTP_CREATED);
         }
        
    }  
	     
}
