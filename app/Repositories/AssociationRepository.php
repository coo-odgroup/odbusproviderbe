<?php

namespace App\Repositories;

// use App\Models\Bus;
use App\Models\User;
use App\Models\UserBusOperator;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class AssociationRepository
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


      $data = $this->usercontent->with('UserBusOperator')->where('status','!=',2)->where('role_id','5')->orderBy('id','DESC');
      if($paginate=='all') 
      {
          $paginate = Config::get('constants.ALL_RECORDS');
      }
      elseif ($paginate == null) 
      {
          $paginate = 10 ;
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
       $usercontent = new $this->usercontent;
       $usercontent->name =$data['name'];
       $usercontent->email =$data['email'];
       $usercontent->phone =$data['phone'];
       $usercontent->role_id ='5';
       $usercontent->status ='1';
       $usercontent->password =bcrypt($data['password']);
       $usercontent->created_by ="Admin";
       $usercontent->save();

      
       return $usercontent;

   }
   public function updateusercontent($data, $id)
   {
      $usercontent = $this->usercontent->find($id);
      $usercontent->name =$data['name'];
      $usercontent->email =$data['email'];
      $usercontent->phone =$data['phone'];
      $usercontent->created_by ="Admin";
	  $usercontent->update();
      return $usercontent;
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