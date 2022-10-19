<?php

namespace App\Repositories;
use App\Models\Faq;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class FaqRepository
{
    
   protected $faq;


   public function __construct(Faq $faq )
   {
      $this->faq = $faq ;
   }    
   public function getAll()
   {

      return $this->faq->with('User')->where('status', 1)->get();
   }

   public function getAllData($request)
   {
      $paginate = $request['rows_number'] ;
      $title = $request['title'] ;


      $data = $this->faq->where('status','!=',2)->orderBy('id','DESC');
      if($paginate=='all') 
      {
          $paginate = Config::get('constants.ALL_RECORDS');
      }
      elseif ($paginate == null) 
      {
          $paginate = 10 ;
      }


      if($title!= null)
      {
        $data = $data->Where('title', $title);
      }

       $data=$data->paginate($paginate);
       // log::info($data);

        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
           return $response;
   }

   public function getModel($data, faq $faq)
   {
      $faq->title = $data['title'];
      $faq->content =$data['content'];
      $faq->created_by =$data['created_by'];
      return $faq;
   }
   public function addfaq($data)
   {        

       $faq = new $this->faq;
       $faq=$this->getModel($data, $faq);
       $faq->save();
       return $faq;

   }
   public function updatefaq($data, $id)
   {
    	// Log::info($id);
      $faq = $this->faq->find($id);
      $faq=$this->getModel($data, $faq);
	   $faq->update();
      return $faq;
   }


    public function deletefaq($id)
    {
    	$faq = $this->faq->find($id);
    	$faq->status = 2;
    	$faq->update();

    	return $faq;
    }

     public function changeStatus($id)
    {
        $faq = $this->faq->find($id);
        if($faq->status==0){
            $faq->status = 1;
        }elseif($faq->status==1){
            $faq->status = 0;
        }
        $faq->update();
        return $faq;
    }


}