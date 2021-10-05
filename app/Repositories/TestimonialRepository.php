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
      $content = $request->searchvalue;
      Log::info($request);

      $data = $this->testimonial->where('status','!=',2);
      if($content!= null)
      {
        $data = $data->where('posted_by','like', '%' . $content . '%')
                     ->orWhere('testinmonial_content','like', '%' . $content . '%')
                     ->orWhere('location','like', '%' . $content . '%')
                     ->orWhere('designation','like', '%' . $content . '%');
      }
      return $data->get();
        // return $this->testimonial->where('status','!=',2)->get();

    }
    public function getModel($data, Testimonial $testimonial)
    {
       $testimonial->posted_by =$data['posted_by'];
       $testimonial->testinmonial_content =$data['testinmonial_content'];
       $testimonial->location =$data['location'];
       $testimonial->designation =$data['designation'];
       $testimonial->created_by ="Admin";
       $testimonial->status = 0;
        return $testimonial;
    }


    public function addtestimonial($data)
    {        
       // Log::info($data);
       $testimonial = new $this->testimonial;
       $testimonial=$this->getModel($data,$testimonial);
       $testimonial->save();

       return $testimonial;

    }
    public function updatetestimonial($data, $id)
    {
    	// Log::info($id);
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