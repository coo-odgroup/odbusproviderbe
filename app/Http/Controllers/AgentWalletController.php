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
use App\Repositories\AgentWalletRepository;
use Illuminate\Support\Facades\DB;

class AgentWalletController extends Controller
{
    use ApiResponser;
    protected $agentWalletService;
    protected $agentWalletValidator;
    protected $agentWalletRepository;
    
    public function __construct(AgentWalletService $agentWalletService, AgentWalletValidator $agentWalletValidator,AgentWalletRepository $agentWalletRepository)
    {
        $this->agentWalletService = $agentWalletService;
        $this->agentWalletValidator = $agentWalletValidator;
        $this->agentWalletRepository = $agentWalletRepository;
    }

    public function getAllData(Request $request){
        try {
            $wallet = $this->agentWalletService->getAllData($request);
            return $this->successResponse($wallet,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function agentWalletBalancedetails(Request $request){
        try {
            $wallet = $this->agentWalletRepository->agentWalletBalancedetails($request);
            return $this->successResponse($wallet,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function agentAllTransaction(Request $request){
        try {
            $wallet = $this->agentWalletRepository->agentAllTransaction($request);
            return $this->successResponse($wallet,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function getData(Request $request){
        try {
            $wallet = $this->agentWalletService->getData($request);
            return $this->successResponse($wallet, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function addAgentWallet(Request $request){
        $data = $request->only(['transaction_id','reference_id','payment_via','amount','remarks','user_id']);
        $agentWalletValidator = $this->agentWalletValidator->validate($data);

        if ($agentWalletValidator->fails()) {
            $errors = $agentWalletValidator->errors();
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }

        DB::beginTransaction();
        try {
           $this->agentWalletRepository->save($request);

           DB::commit();
           return $this->successResponse($data,"Wallet request Added",Response::HTTP_CREATED);
        } catch (Exception $e) {
           DB::rollBack();
           return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
         
    }

    public function agentTransByAdmin(Request $request){
        try {
            $data= $this->agentWalletRepository->agentTransByAdmin($request);
            return $this->successResponse($data,"Wallet request Added",Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        // $data = $request->only(['transaction_id','reference_id','payment_via','amount','remarks','user_id']);

        // $agentWalletValidator = $this->agentWalletValidator->validate($data);
        // if ($agentWalletValidator->fails()) {
        //     $errors = $agentWalletValidator->errors();

        //     return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        //   }
        // try {
        //    $this->agentWalletService->agentTransByAdmin($request);
        //    return $this->successResponse($data,"Wallet request Added",Response::HTTP_CREATED);
        // } catch (Exception $e) {
        //    return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        // }
         
    }


    public function changeStatus(Request $request, $id){
        $data=$this->agentWalletService->changeStatus($request,$id);
        if($data=='Invalid OTP'){
            return $this->errorResponse($data,Response::HTTP_PARTIAL_CONTENT);
        }else{
            return $this->successResponse($data,"Wallet request Updated",Response::HTTP_CREATED);
        }
        
    }

    public function declineWlletReqStatus(Request $request, $id){
    	$dd = $this->agentWalletRepository->declineWalletReq($request,$id);

        if($dd !=' '){
            return $this->successResponse($dd,"Wallet Request Declined!",Response::HTTP_CREATED);
        }else{
            return $this->errorResponse($dd,Response::HTTP_PARTIAL_CONTENT);
        }
        
    }
    
	   
    public function agentWalletBalance($id){
        try {
            $wallet = $this->agentWalletRepository->balance($id);
            return $this->successResponse($wallet,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        
    }
         
}
