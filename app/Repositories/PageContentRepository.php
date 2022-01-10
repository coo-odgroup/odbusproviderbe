<?php

namespace App\Repositories;

// use App\Models\Bus;
use App\Models\PageContent;
// use App\Models\SeatOpenSeats;

// use App\Models\TicketPrice;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class PageContentRepository
{
    
   protected $pagecontent;


   public function __construct(PageContent $pagecontent )
   {
      $this->pagecontent = $pagecontent ;
   }    
   public function getAll()
   {

      return $this->pagecontent->with('User')->where('status', 1)->get();
   }

   public function getAllData($request)
   {
      $paginate = $request['rows_number'] ;
      $user_id = $request['user_id'] ;


      $data = $this->pagecontent->with('User')->where('status','!=',2)->orderBy('id','DESC');
      if($paginate=='all') 
      {
          $paginate = Config::get('constants.ALL_RECORDS');
      }
      elseif ($paginate == null) 
      {
          $paginate = 10 ;
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

   public function getModel($data, PageContent $pagecontent)
   {
      $pagecontent->page_name =$data['page_name'];
      // $pagecontent->bus_operator_id =$data['bus_operator_id'];
      $pagecontent->user_id = $data['user_id'];
      $pagecontent->page_url =$data['page_url'];
      $pagecontent->page_description =$data['page_description'];
      $pagecontent->meta_title =$data['meta_title'];
      $pagecontent->meta_keyword =$data['meta_keyword'];
      $pagecontent->meta_description =$data['meta_description'];
      $pagecontent->extra_meta =$data['extra_meta'];
      $pagecontent->canonical_url =$data['canonical_url'];
      $pagecontent->created_by =$data['created_by'];
      return $pagecontent;
   }
   public function addpagecontent($data)
   {        
       // Log::info($data);

       $pagecontent = new $this->pagecontent;
       $pagecontent=$this->getModel($data, $pagecontent);
       $pagecontent->save();
       return $pagecontent;

   }
   public function updatepagecontent($data, $id)
   {
    	// Log::info($id);
      $pagecontent = $this->pagecontent->find($id);
      $pagecontent=$this->getModel($data, $pagecontent);
	   $pagecontent->update();
      return $pagecontent;
   }


    public function deletepagecontent($id)
    {
    	$pagecontent = $this->pagecontent->find($id);
    	$pagecontent->status = 2;
    	$pagecontent->update();

    	return $pagecontent;
    }


}