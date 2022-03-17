<?php

namespace App\Repositories;

use App\Models\SocialMedia;


use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class SocialMediaRepository
{
    
    protected $socialMedia;
    
    public function __construct(SocialMedia $socialMedia)
    {
        $this->socialMedia = $socialMedia; 
    }   

     public function getAll($request)
    {
      // Log::info($request);
      // $paginate = $request['rows_number'] ;

      // $user_id = $request['user_id'] ;

      $paginate = $request['rows_number'] ;
      $user_id = $request['user_id'] ;
      $role_id = $request['role_id'] ;
      $name = $request['name'] ;


      $data = $this->socialMedia->with('User')->where('status','!=',2)->orderBy('id','DESC');
      if($paginate=='all') 
      {
          $paginate = Config::get('constants.ALL_RECORDS');
      }
      elseif ($paginate == null) 
      {
          $paginate = 10 ;
      }
      
      // if($user_id!= null)
      // {
      //   $data = $data->Where('user_id', $user_id);
      // }
      if($user_id!= null && $role_id!= 1 )
      {
        $data = $data->Where('user_id', $user_id);
      }
       $data=$data->paginate($paginate);
       

        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
           return $response;

    }
      
    public function getModel($data, SocialMedia $socialMedia)
    {
       $socialMedia->facebook_link =$data['facebook_link'];
       $socialMedia->twitter_link =$data['twitter_link'];
       $socialMedia->instagram_link =$data['instagram_link'];
       $socialMedia->user_id =$data['user_id'];
       $socialMedia->googleplus_link =$data['googleplus_link'];
       $socialMedia->linkedin_link =$data['linkedin_link'];
       $socialMedia->created_by =$data['created_by'];
        return $socialMedia;
    }

    public function addsocialMedia($data)
    { 
  

      $oldData= $this->socialMedia->where('user_id',$data->user_id)->get();
  // Log::info();exit;   
  if(count($oldData)==0){
       $socialMedia = new $this->socialMedia;
       $socialMedia=$this->getModel($data,$socialMedia);
       $socialMedia->save();

       return $socialMedia;
  }else{
    return "User Social Media Data already exist";
  }
      

    }
    public function updatesocialMedia($data, $id)
    {
        // Log::info($id);
        // exit;
        // // $id = $data['id'] ;      
        $duplicate_data = $this->socialMedia
                               ->where('user_id',$data->user_id)
                               ->where('id','!=',$id )
                               ->where('status','!=',2)
                               ->get();
        if(count($duplicate_data)==0){
             $socialMedia = $this->socialMedia->find($id);
             $socialMedia=$this->getModel($data,$socialMedia);
             $socialMedia->update();
       return $socialMedia;
        } 
        else{
           return "User Social Media Data already exist";
        }                      
      
    }


    public function deletesocialMedia($id)
    {
        $socialMedia = $this->socialMedia->find($id);
        $socialMedia->status = 2;
        $socialMedia->update();

        return $socialMedia;
    }
    public function changeStatus($id)
    {
      $post = $this->socialMedia->find($id);
        if($post->status==0){
            $post->status = 1;
        }elseif($post->status==1){
            $post->status = 0;
        }
        $post->update();
        return $post;
    }


}