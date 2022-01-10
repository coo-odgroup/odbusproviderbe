<?php

namespace App\Repositories;

use App\Models\Testimonial;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class TestimonialRepository
{
    
    protected $testimonial;

    
    public function __construct(Testimonial $testimonial )
    {
       $this->testimonial = $testimonial ;
       
    }    
    public function getAll($request)
    {
      $paginate = $request['rows_number'] ;
      $name = $request['name'] ;
      $user_id = $request['user_id'] ;


      $data = $this->testimonial->with('User')->where('status','!=',2)->orderBy('id','DESC');
      if($paginate=='all') 
      {
          $paginate = Config::get('constants.ALL_RECORDS');
      }
      elseif ($paginate == null) 
      {
          $paginate = 10 ;
      }

      if($name!= null)
      {
        $data = $data->where('posted_by','like', '%' . $name . '%')                    
                     ->orWhere('testinmonial_content','like', '%' . $name . '%')
                     ->orWhere('destination','like', '%' . $name . '%')
                     ->orWhere('source','like', '%' . $name . '%')
                     ->orWhere('travel_date','like', '%' . $name . '%')
                     ->orWhere('designation','like', '%' . $name . '%');
      } 
      if($user_id!= null)
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
      
    public function getModel($data, Testimonial $testimonial)
    {
       $testimonial->posted_by =$data['posted_by'];
       $testimonial->testinmonial_content =$data['testinmonial_content'];
       $testimonial->travel_date =$data['travel_date'];
       $testimonial->user_id =$data['user_id'];
       $testimonial->destination =$data['destination'];
       $testimonial->source =$data['source'];
       $testimonial->designation = $data['designation'];
       $testimonial->created_by = $data['created_by'];
        return $testimonial;
    }


    public function addtestimonial($data)
    {        
       $testimonial = new $this->testimonial;
       $testimonial=$this->getModel($data,$testimonial);
       $testimonial->save();

       return $testimonial;

    }
    public function updatetestimonial($data, $id)
    {
       $testimonial = $this->testimonial->find($id);
       $testimonial=$this->getModel($data,$testimonial);
  	   $testimonial->update();

       return $testimonial;
    }


    public function deletetestimonial($id)
    {
    	$testimonial = $this->testimonial->find($id);
    	$testimonial->status = 2;
    	$testimonial->update();

    	return $testimonial;
    }
    public function changeStatus($id)
    {
      $post = $this->testimonial->find($id);
        if($post->status==0){
            $post->status = 1;
        }elseif($post->status==1){
            $post->status = 0;
        }
        $post->update();
        return $post;
    }


}