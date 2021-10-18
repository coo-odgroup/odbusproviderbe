<?php

namespace App\Repositories;

// use App\Models\Bus;
use App\Models\SeoSetting;
// use App\Models\SeatOpenSeats;

// use App\Models\TicketPrice;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class SeoSettingRepository
{
    
    protected $seosetting;

    
    public function __construct(SeoSetting $seosetting )
    {
       $this->seosetting = $seosetting ;
       
    }    
    public function getAll()
    {

        return $this->seosetting->where('status', 1)->get();

    }


    public function addseosetting($data)
    {        
       // Log::info($data);

       $seosetting = new $this->seosetting;
       $seosetting->page_url =$data['page_url'];
       $seosetting->meta_title =$data['meta_title'];
       $seosetting->meta_keyword =$data['meta_keyword'];
       $seosetting->meta_description =$data['meta_description'];
       $seosetting->extra_meta =$data['extra_meta'];
       $seosetting->canonical_url =$data['canonical_url'];
       $seosetting->created_by ="Admin";
       $seosetting->save();

       return $seosetting;

    }
    public function updateseosetting($data, $id)
    {
    	// Log::info($id);
       $seosetting = $this->seosetting->find($id);       
	   $seosetting->page_url =$data['page_url'];
	   $seosetting->meta_title =$data['meta_title'];
	   $seosetting->meta_keyword =$data['meta_keyword'];
	   $seosetting->meta_description =$data['meta_description'];
	   $seosetting->extra_meta =$data['extra_meta'];
	   $seosetting->canonical_url =$data['canonical_url'];
	   $seosetting->created_by ="Admin";
	   $seosetting->update();

       return $seosetting;
    }


    public function deleteseosetting($id)
    {
    	$seosetting = $this->seosetting->find($id);
    	$seosetting->status = 2;
    	$seosetting->update();

    	return $seosetting;
    }


}