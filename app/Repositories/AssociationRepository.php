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
     
        $duplicate_email = $this->usercontent
                               ->where('email',$data['email'])
                               ->where('status','!=',2)
                               ->get(); 
        $duplicate_phone = $this->usercontent
                               ->where('phone',$data['phone'])
                               ->where('status','!=',2)
                               ->get();

        if(count($duplicate_email)==0 && count($duplicate_phone)==0)
        {
             $usercontent = new $this->usercontent;
             $usercontent->name =$data['name'];
             $usercontent->email =$data['email'];
             $usercontent->phone =$data['phone'];

             $usercontent->location =$data['location'];
             $usercontent->president_name =$data['president_name'];
             $usercontent->president_phone =$data['president_phone'];
             $usercontent->general_secretary_name =$data['general_secretary_name'];
             $usercontent->general_secretary_phone =$data['general_secretary_phone'];

             $usercontent->user_type ='ASSOCIATION';
             $usercontent->role_id ='5';
             $usercontent->status ='0';
             $usercontent->password =bcrypt($data['password']);
             $usercontent->created_by ="Admin";
             $usercontent->save();

            
             return $usercontent;
        }
        else
        {
             return 'Association Already Exist';
        }

   }
   public function updateusercontent($data, $id)
   {
        $duplicate_email = $this->usercontent
                               ->where('email',$data['email'])
                               ->where('id','!=',$id )
                               ->where('status','!=',2)
                               ->get(); 
        $duplicate_phone = $this->usercontent
                               ->where('phone',$data['phone'])
                               ->where('id','!=',$id )
                               ->where('status','!=',2)
                               ->get();

        if(count($duplicate_email)==0 && count($duplicate_phone)==0)
        { 
              $usercontent = $this->usercontent->find($id);
              $usercontent->name =$data['name'];
              $usercontent->email =$data['email'];
              $usercontent->phone =$data['phone'];
              $usercontent->location =$data['location'];
              $usercontent->president_name =$data['president_name'];
              $usercontent->president_phone =$data['president_phone'];
              $usercontent->general_secretary_name =$data['general_secretary_name'];
              $usercontent->general_secretary_phone =$data['general_secretary_phone'];
              $usercontent->created_by ="Admin";
              $usercontent->status ='0';
              $usercontent->update();
              return $usercontent;
        }
        else
        {
             return 'Association Already Exist';
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