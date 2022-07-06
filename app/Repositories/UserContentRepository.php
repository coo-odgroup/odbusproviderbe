<?php

namespace App\Repositories;

// use App\Models\Bus;
use App\Models\User;
use App\Models\UserBusOperator;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class UserContentRepository
{
    
   protected $usercontent;
   protected $userBusOperator;

   public function __construct(User $usercontent, UserBusOperator $userBusOperator )
   {
      $this->usercontent = $usercontent ;
      $this->userBusOperator = $userBusOperator;
   }    
   public function getAll()
   {

      return $this->usercontent->with('UserBusOperator')->where('status', 1)->get();
   }

   public function getAllData($request)
   {
      $paginate = $request['rows_number'] ;
      $operator_id = $request['bus_operator_id'] ;


      $data = $this->usercontent->with('busOperator')->where('status','!=',2)->where('role_id','4')->orderBy('id','DESC');
      if($paginate=='all') 
      {
          $paginate = Config::get('constants.ALL_RECORDS');
      }
      elseif ($paginate == null) 
      {
          $paginate = 10 ;
      }
 
      if($operator_id!= null)
      {
        $data = $data->Where('bus_operator_id', $operator_id);
      }
       $data=$data->paginate($paginate);
       

        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
           return $response;
   }

    
   public function addusercontent($data)
   {      

       $bus_opr_exist= $this->usercontent->where('bus_operator_id', $data['bus_operator_id'])->where('status',1)->get();
       $email_exist= $this->usercontent->where('email', $data['email'])->where('status',1)->get();
       $phone_exist= $this->usercontent->where('phone', $data['phone'])->where('status',1)->get();

       if(count($bus_opr_exist)==0){
        if(count($email_exist)==0){
          if(count($phone_exist)==0){
             $usercontent = new $this->usercontent; 
             $usercontent->name =$data['name'];
             $usercontent->email =$data['email'];
             $usercontent->phone =$data['phone'];
             $usercontent->bus_operator_id =$data['bus_operator_id'];
             $usercontent->role_id ='4';
             $usercontent->user_type ='OPERATOR';
             $usercontent->status ='1';
             $usercontent->password =bcrypt($data['password']);
             $usercontent->created_by ="Admin";
             $usercontent->save();
             return $usercontent;

          }else{
               return 'Phone Number Exist';
          }
        }else{
            return 'Email Id Exist';
        }
       }else
       {
          return 'Bus Operator Exist';
       }
   }

   public function updateusercontent($data, $id)
   {
       $email_exist= $this->usercontent->where('email', $data['email'])->where('id','!=',$id)->where('status',1)->get();
       $phone_exist= $this->usercontent->where('phone', $data['phone'])->where('id','!=',$id)->where('status',1)->get();

       if(count($email_exist)==0){
          if(count($phone_exist)==0){
            $usercontent = $this->usercontent->find($id);
            $usercontent->name =$data['name'];
            $usercontent->email =$data['email'];
            $usercontent->phone =$data['phone'];
            $usercontent->created_by ="Admin";
            $usercontent->update();
            return $usercontent;
          }else{
            return 'Phone Number Exist';
          }
          }else{
             return 'Email Id Exist';
          }
      
   } 

   public function changePassword($data, $id)
   {
      $usercontent = $this->usercontent->find($id);
      $usercontent->password =bcrypt($data['password']);
      $usercontent->update();
      return $usercontent;
   }


    public function deleteusercontent($id)
    {
    	$usercontent = $this->usercontent->find($id);
    	$usercontent->status = 2;
    	$usercontent->update();

    	return $usercontent;
    }

    public function changeStatus($id)
    {

      // Log::info($id);
      $post = $this->usercontent->find($id);
        if($post->status==0){
            $post->status = 1;
        }elseif($post->status==1){
            $post->status = 0;
        }
        $post->update();
        return $post;
    }

}