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
      $paginate = $request['rows_number'] ;

      $operator_id = $request['bus_operator_id'] ;


      $data = $this->socialMedia->with('BusOperator')->where('status','!=',2)->orderBy('id','DESC');
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
      
    public function getModel($data, SocialMedia $socialMedia)
    {
       $socialMedia->facebook_link =$data['facebook_link'];
       $socialMedia->twitter_link =$data['twitter_link'];
       $socialMedia->instagram_link =$data['instagram_link'];
       $socialMedia->bus_operator_id =$data['bus_operator_id'];
       $socialMedia->googleplus_link =$data['googleplus_link'];
       $socialMedia->linkedin_link =$data['linkedin_link'];
       $socialMedia->created_by ="Admin";
       $socialMedia->status = 0;
        return $socialMedia;
    }


    public function addsocialMedia($data)
    {    
     Log::info($data);

       $socialMedia = new $this->socialMedia;
       $socialMedia=$this->getModel($data,$socialMedia);
       $socialMedia->save();

       return $socialMedia;

    }
    public function updatesocialMedia($data, $id)
    {
        // Log::info($id);
       $socialMedia = $this->socialMedia->find($id);
       $socialMedia=$this->getModel($data,$socialMedia);
       $socialMedia->update();

       return $socialMedia;
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