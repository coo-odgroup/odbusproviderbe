<?php

namespace App\Repositories;

// use App\Models\Bus;
use App\Models\User;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class UserContentRepository
{
    
   protected $usercontent;


   public function __construct(User $usercontent )
   {
      $this->usercontent = $usercontent ;
   }    
   public function getAll()
   {

      return $this->usercontent->with('BusOperator')->where('status', 1)->get();
   }

   public function getAllData($request)
   {
      $paginate = $request['rows_number'] ;
      $operator_id = $request['bus_operator_id'] ;


      $data = $this->usercontent->with('BusOperator')->where('status','!=',2)->orderBy('id','DESC');
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
       $usercontent = new $this->usercontent;
       // $usercontent=$this->getModel($data, $usercontent);
       $usercontent->name =$data['name'];
       $usercontent->bus_operator_id =$data['bus_operator_id'];
       $usercontent->email =$data['email'];
       $usercontent->phone =$data['phone'];
       $usercontent->password =$data['password'];
       $usercontent->created_by ="Admin";
       $usercontent->save();
       return $usercontent;

   }
   public function updateusercontent($data, $id)
   {
    	// Log::info($id);
      $usercontent = $this->usercontent->find($id);
      $usercontent->name =$data['name'];
      $usercontent->bus_operator_id =$data['bus_operator_id'];
      $usercontent->email =$data['email'];
      $usercontent->phone =$data['phone'];
      $usercontent->created_by ="Admin";
	    $usercontent->update();
      return $usercontent;
   } 

   public function changePassword($data, $id)
   {
      // Log::info($data);
      $usercontent = $this->usercontent->find($id);
      $usercontent->password =$data['password'];
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