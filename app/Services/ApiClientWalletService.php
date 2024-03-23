<?php
namespace App\Services;

use App\Models\BusOwnerFare;
use App\Repositories\ApiClientWalletRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class ApiClientWalletService
{
    
    protected $ApiClientWalletRepository;

    
    public function __construct(ApiClientWalletRepository $ApiClientWalletRepository)
    {
        $this->ApiClientWalletRepository = $ApiClientWalletRepository;
    } 

     public function getAllData($request)
    {
        
         $paginate = $request['rows_number'] ;
         $name = $request['name'] ;
         $user_id = $request['user_id'] ;
         $start_date  =  $request['rangeFromDate'];
         $end_date  =  $request['rangeToDate'];
         $reqs_status  =  $request['status'];

        // Log::info($request);

      $data= $this->ApiClientWalletRepository->getAllWalletRecord(); 

      if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }

        if($name!=null)
        {
            $data = $this->ApiClientWalletRepository->Filter($data, $name);                     
        }     

        if(!empty($user_id))
        {     
            $data = $this->ApiClientWalletRepository->Filter_user($data, $user_id);        
        }        
            
        if($start_date != null && $end_date != null)
        {
            $data = $this->ApiClientWalletRepository->FilterDate($data, $start_date,$end_date);        
                       
        }
        if($reqs_status != null)
        {
            if($reqs_status == 0)
            {
                $data = $data->where('status',0)
                             ->orderBy('created_at','DESC');
            }
            if($reqs_status == 1)
            {
                $data = $data->where('status',1)
                             ->orderBy('created_at','DESC');
            }

            if($reqs_status == 3)
            {
                $data = $data->where('status',3)
                             ->orderBy('created_at','DESC');
            }
        }

        $data= $this->ApiClientWalletRepository->Pagination($data,$paginate); 

        // log::info($data);
        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
             "data" => $data
           );   

        return $response;  

        //return $this->ApiClientWalletRepository->getData($request);
    }


    public function getData($request)
    { 
      // Log::info($request);
         $paginate = $request['rows_number'] ;
         $name = $request['name'] ;
         $payment_via = $request['payment_via'] ;
         $user_id = $request['user_id'] ;


          $data= $this->ApiClientWalletRepository->getWalletRequestRecord($user_id);

      if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }

        if($name!=null)
        {
            $data = $this->ApiClientWalletRepository->Filter($data, $name);                     
        } 

        if($payment_via!=null)
        {
            $data = $this->ApiClientWalletRepository->payViaFilter($data, $payment_via);                     
        }  
           

        $data= $this->ApiClientWalletRepository->Pagination($data,$paginate); 

        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   

        // Log::info($response);
           return $response;  


        //return $this->ApiClientWalletRepository->getData($request);
    }
    public function agentWalletBalance($id)
    {
      
      return $this->ApiClientWalletRepository->balance($id);
    }

    public function allTransactionData($request)
    {
      
      return $this->ApiClientWalletRepository->allTransactionData($request);
    }

    public function agentAllTransaction($id)
    {
      
      return $this->ApiClientWalletRepository->agentAllTransaction($id);
    }

    public function apiClientTotalTransactions($request)
    {
      
      return $this->ApiClientWalletRepository->apiClientTotalTransactions($request);
    }

    public function agentWalletBalancedetails($request)
    {
      
      return $this->ApiClientWalletRepository->ApiClientWalletBalancedetails($request);
    }
        
    public function savePostData($data)
    {
        try {
            $post = $this->ApiClientWalletRepository->save($data);

        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        }
        return $post;
    }
   
   public function changeStatus($data,$id)
   {
           $otp_status= $this->ApiClientWalletRepository->Otp($id,$data);
           // log::info($otp_status);
           // exit;

       if(sizeof($otp_status)>0)
       {
       
            return $post = $this->ApiClientWalletRepository->update_Status($id,$otp_status[0],$data);
           
            // $user_id = $post->user_id;
            
            // $prvious_balance = $this->ApiClientWalletRepository->balance($user_id);
            
               // if($post->transaction_type == "c")
               //  {           
               //      $balance=$prvious_balance[0]->balance + (int)$post->amount;

               //  }
               //  else if($post->transaction_type == "d")
               //  {        
               //      $balance=$prvious_balance[0]->balance - (int)$post->amount;
               //  } 
               
               //   return $updated_balance =$this->ApiClientWalletRepository->update_balance($id,$balance,$otp_status,$data);   
       }
       else
       {
         return 'Invalid OTP';
       }
   }


   public function declineWlletReqStatus($data,$id)
   {
       return $this->ApiClientWalletRepository->declineWalletReq($data,$id);
   }

    public function clientTransByAdmin($data)
    {
      return $this->ApiClientWalletRepository->clientTransByAdmin($data);
    }

}

 