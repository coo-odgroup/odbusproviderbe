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
       $this->bus_operator_id = Config::get('constants.BUS_OPERATOR_ID');
    }    
    public function getAll()
    {

        return $this->pagecontent->where('bus_operator_id',$this->bus_operator_id)->where('status', 1)->get();
    }


    public function addpagecontent($data)
    {        
       // Log::info($data);

       $pagecontent = new $this->pagecontent;
       $pagecontent->page_name =$data['page_name'];
       $pagecontent->bus_operator_id = $this->bus_operator_id;
       $pagecontent->page_url =$data['page_url'];
       $pagecontent->page_description =$data['page_description'];
       $pagecontent->meta_title =$data['meta_title'];
       $pagecontent->meta_keyword =$data['meta_keyword'];
       $pagecontent->meta_description =$data['meta_description'];
       $pagecontent->extra_meta =$data['extra_meta'];
       $pagecontent->canonical_url =$data['canonical_url'];
       $pagecontent->created_by ="Admin";

       $pagecontent->save();


       return $pagecontent;

    }
    public function updatepagecontent($data, $id)
    {
    	// Log::info($id);
      $pagecontent = $this->pagecontent->find($id);
      $pagecontent->page_name =$data['page_name'];
      $pagecontent->bus_operator_id = $this->bus_operator_id;
	   $pagecontent->page_url =$data['page_url'];
	   $pagecontent->page_description =$data['page_description'];
	   $pagecontent->meta_title =$data['meta_title'];
	   $pagecontent->meta_keyword =$data['meta_keyword'];
	   $pagecontent->meta_description =$data['meta_description'];
	   $pagecontent->extra_meta =$data['extra_meta'];
	   $pagecontent->canonical_url =$data['canonical_url'];
	   $pagecontent->created_by ="Admin";
	   $pagecontent->update();

       return $pagecontent;
    }


    public function deletepagecontent($id)
    {
    	$pagecontent = $this->pagecontent->where('bus_operator_id',$this->bus_operator_id)->find($id);
    	$pagecontent->status = 2;
    	$pagecontent->update();

    	return $pagecontent;
    }


}