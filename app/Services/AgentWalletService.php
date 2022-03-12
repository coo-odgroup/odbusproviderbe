<?php
namespace App\Services;

use App\Models\BusOwnerFare;
use App\Repositories\AgentWalletRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class AgentWalletService
{
    
    protected $agentWalletRepository;

    
    public function __construct(AgentWalletRepository $agentWalletRepository)
    {
        $this->agentWalletRepository = $agentWalletRepository;
    } 

     public function getAllData($request)
    {
      // Log::info($request);
         $paginate = $request['rows_number'] ;
         $name = $request['name'] ;

      $data= $this->agentWalletRepository->getAllWalletRecord();

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
            $data = $this->agentWalletRepository->Filter($data, $name);                     
        }     

        $data= $this->agentWalletRepository->Pagination($data,$paginate); 

        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   

        // Log::info($response['data']);
           return $response;  


        //return $this->agentWalletRepository->getData($request);
    }


    public function getData($request)
    { 
      // Log::info($request);
         $paginate = $request['rows_number'] ;
         $name = $request['name'] ;
         $payment_via = $request['payment_via'] ;
         $user_id = $request['user_id'] ;


          $data= $this->agentWalletRepository->getWalletRecord($user_id);

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
            $data = $this->agentWalletRepository->Filter($data, $name);                     
        } 

        if($payment_via!=null)
        {
            $data = $this->agentWalletRepository->payViaFilter($data, $payment_via);                     
        }     

        $data= $this->agentWalletRepository->Pagination($data,$paginate); 

        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   

        // Log::info($response);
           return $response;  


        //return $this->agentWalletRepository->getData($request);
    }
    public function agentWalletBalance($id)
    {
      
      return $this->agentWalletRepository->balance($id);
    }

    public function agentAllTransaction($id)
    {
      
      return $this->agentWalletRepository->agentAllTransaction($id);
    }

    public function agentWalletBalancedetails($request)
    {
      
      return $this->agentWalletRepository->agentWalletBalancedetails($request);
    }
        
    public function savePostData($data)
    {
        try {
            $post = $this->agentWalletRepository->save($data);

        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        }
        return $post;
    }
   
   public function changeStatus($data,$id)
   {
           $otp_status= $this->agentWalletRepository->Otp($id,$data);

       if(sizeof($otp_status)>0)
       {
       
            $post = $this->agentWalletRepository->update_Status($id);
           
            $user_id = $post->user_id;
            
            $prvious_balance = $this->agentWalletRepository->balance($user_id);
            
               if($post->transaction_type == "c")
                {           
                    $balance=$prvious_balance[0]->balance + (int)$post->amount;

                }
                else if($post->transaction_type == "d")
                {        
                    $balance=$prvious_balance[0]->balance - (int)$post->amount;
                } 
               
                 return $updated_balance =$this->agentWalletRepository->update_balance($id,$balance,$otp_status,$data);   
       }
       else
       {
         return 'Invalid OTP';
       }
   }


   public function declineWlletReqStatus($data,$id)
   {
       return $this->agentWalletRepository->declineWalletReq($data,$id);
   }

}

 