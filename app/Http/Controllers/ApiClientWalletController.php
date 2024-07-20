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
use DB;
use App\Models\ApiClientWalletNew;
use App\Models\ClientFeeSlab;



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

    public function apiClientTotalTransactions(Request $request) 
    {      
     
        $wallet = $this->ApiClientWalletService->apiClientTotalTransactions($request);
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


    // clientTransByAdmin

    public function clientTransByAdmin(Request $request) 
    { 
        $data= $this->ApiClientWalletService->clientTransByAdmin($request);
           return $this->successResponse($data,"Wallet request Added",Response::HTTP_CREATED);         
    } 
    // clientTransUpdateByAdmin

    public function clientTransUpdateByAdmin(Request $request) 
    { 
        $data= $this->ApiClientWalletService->clientTransUpdateByAdmin($request);
        return $this->successResponse($data,"Wallet request Updated",Response::HTTP_CREATED);         
    } 

    public function UpdateApiClientWallet(){
       $wallet= DB::table('client_wallet')->where('user_id',372)->orderBy('id','asc')->get();
       foreach($wallet as $k => $w){
          if($w->payment_via!='' && $w->transaction_type=='c' && $w->booking_id ==null){ // recharge
                ///////// insert 

                $getLastBalance = DB::table('client_wallet_new')->where('user_id',372)->where('status',1)->orderBy('id','DESC')->limit(1)->first();

                $ApiClientWallet = new ApiClientWalletNew();
                $ApiClientWallet->transaction_id = $w->transaction_id;
                $ApiClientWallet->reference_id = $w->reference_id;
                $ApiClientWallet->amount = $w->amount;
                $ApiClientWallet->balance = ($k==0) ? $w->balance : $getLastBalance->balance + $w->amount ;
                $ApiClientWallet->payment_via = $w->payment_via;
                $ApiClientWallet->remarks = $w->remarks;
                $ApiClientWallet->user_id = $w->user_id;
                $ApiClientWallet->transaction_type = $w->transaction_type;       
                $ApiClientWallet->created_by = $w->created_by;
                $ApiClientWallet->created_at = $w->created_at;
                $ApiClientWallet->updated_at = $w->updated_at;
                $ApiClientWallet->status = 1;
                $ApiClientWallet->otp = "";       
                $ApiClientWallet->save();

          }

          if($w->payment_via=='' && $w->transaction_type=='d' && $w->booking_id !=null){ // booking
                $getBooking=DB::table('booking')->where('id',$w->booking_id)->first();

                $getLastBalance = DB::table('client_wallet_new')->where('user_id',372)->where('status',1)->orderBy('id','DESC')->limit(1)->first();

                 //////// insert booking debit

                 $ApiClientWallet2 = new ApiClientWalletNew();
                 $ApiClientWallet2->transaction_id = $w->transaction_id;
                 $ApiClientWallet2->reference_id = $w->reference_id;
                 $ApiClientWallet2->amount = $w->amount;
                 $ApiClientWallet2->balance = $balance= $getLastBalance->balance - $w->amount;
                 $ApiClientWallet2->payment_via = $w->payment_via;
                 $ApiClientWallet2->remarks = $w->remarks;
                 $ApiClientWallet2->user_id = $w->user_id;
                 $ApiClientWallet2->transaction_type = $w->transaction_type;
                 $ApiClientWallet2->type =  $w->type;        
                 $ApiClientWallet2->booking_id = $w->booking_id;       
                 $ApiClientWallet2->created_by = $w->created_by;
                 $ApiClientWallet2->created_at = $w->created_at;
                $ApiClientWallet2->updated_at = $w->updated_at;
                 $ApiClientWallet2->status = 1;
                 $ApiClientWallet2->otp = "";       
                 $ApiClientWallet2->save();


                 /////// insert commission credit


                 $ApiClientWallet3 = new ApiClientWalletNew();
                 $ApiClientWallet3->transaction_id = $w->transaction_id;
                 $ApiClientWallet3->reference_id = $w->reference_id;
                 $ApiClientWallet3->payment_via = $w->payment_via;
                 $ApiClientWallet3->remarks = $w->remarks;
                 $ApiClientWallet3->user_id = $w->user_id;
                 $ApiClientWallet3->transaction_type = 'c';
                 $ApiClientWallet3->type =  "Commission";  
                 $ApiClientWallet3->created_at = $w->created_at;
                $ApiClientWallet3->updated_at = $w->updated_at;    
                 $ApiClientWallet3->booking_id = $w->booking_id;       
                 $ApiClientWallet3->created_by = $w->created_by;
                 $ApiClientWallet3->status = 1;
                 $ApiClientWallet3->otp = "";  
                 
                 /////// calculation

                 
                  
                 $actual_fare_for_commission=  $getBooking->total_fare - $getBooking->client_gst ;

                 $commission = round($getBooking->client_percentage/100 * $actual_fare_for_commission,2);
                 
                 $ApiClientWallet3->amount = $commission;
                 $ApiClientWallet3->balance = $balance + $commission;

                 /////////////////////////////
                 if($commission>0){
                     $ApiClientWallet3->save();

                 }

                      /////  update client_comission column in booking table

                      DB::table('booking')->where('id',$getBooking->id)->update(['client_comission'=>$commission]);


          }

          if($w->booking_id !='' && $w->transaction_type=='c'  && $w->type=='Refund'){ /// refund
            $getBooking=DB::table('booking')->where('id',$w->booking_id)->first();
            /////// calculate refund credit
           $paid_amount_without_gst= $getBooking->total_fare -  $getBooking->client_gst;
            $deductAmt = round($paid_amount_without_gst*($getBooking->deduction_percent/100),2);
            $GstOnCancelCharge= $deductAmt * (5/100); 
            $refundAmt = $getBooking->total_fare - ($deductAmt - $GstOnCancelCharge) ;
            
            /////// calculate  cancel commission credit

            $clientCancelComPer =0;
            $clientCancelCom = ClientFeeSlab::where('user_id',372)->first();
    
            if($clientCancelCom){
                $clientCancelComPer = $clientCancelCom->cancellation_commission;
            }
            
            if($clientCancelComPer == 0){
                $OdbusCancelProfit = $deductAmt;
                $clientCancelProfit = 0; 
            }else{
              $OdbusCancelProfit = round($deductAmt * ((100 - $clientCancelComPer))/100,2); 
              $clientCancelProfit = round($deductAmt - $OdbusCancelProfit,2);
            }
            $clientCancelProfit = $clientCancelProfit - $getBooking->client_comission;

             /////// insert refund credit

             $clientWallet4 = new ApiClientWalletNew();
             $clientWallet4->transaction_id = $w->transaction_id;
             $clientWallet4->type = $w->type;
             $clientWallet4->booking_id = $w->booking_id;
             $clientWallet4->transaction_type = 'c';
             $clientWallet4->user_id = $w->user_id;
             $clientWallet4->created_by = $w->created_by;
             $clientWallet4->status = 1;
             $clientWallet4->created_at = $w->created_at;
             $clientWallet4->updated_at = $w->updated_at;
             $getLastBalance = DB::table('client_wallet_new')->where('user_id',372)->where('status',1)->orderBy('id','DESC')->limit(1)->first();

             $clientWallet4->amount = $refundAmt;
             $clientWallet4->balance = $balance= $getLastBalance->balance + $refundAmt;
             $clientWallet4->save();

             /////// insert cancel commission credit

             if($clientCancelProfit != 0){
            
                $transactionId = date('YmdHis') . gettimeofday()['usec'];
                $clientWallet5 = new ApiClientWalletNew();
                $clientWallet5->transaction_id = $transactionId;
                //$clientWallet5->amount = $deductAmt/2;
                $clientWallet5->amount = $clientCancelProfit;
                $clientWallet5->type = 'CancelCommission';
                $clientWallet5->booking_id = $w->booking_id;
                $clientWallet5->transaction_type = 'c';
                $clientWallet5->balance = $balance + $clientCancelProfit;
                $clientWallet5->user_id = $w->user_id;
                $clientWallet5->created_by = "Amit Kumar Singh";
                $clientWallet5->created_at = $w->created_at;
                $clientWallet5->updated_at = $w->updated_at;
                $clientWallet5->status = 1;
                $clientWallet5->save(); 
            }


          }
       }
    }
         
}
