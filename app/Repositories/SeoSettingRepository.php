<?php

namespace App\Repositories;

use App\Models\SeoSetting;
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

        return $this->seosetting->whereNotIn('status', [2])->get();

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

    public function seosettingData($request)
    {        
      
        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;
       

        $data= $this->seosetting->whereNotIn('status', [2])
                                ->orderBy('id','DESC');

        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }

        if($name!=null)
        {
            $data=$data->where('page_url', $name);
        } 
      

        $data=$data->paginate($paginate);

        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
           return $response;

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


     public function changeStatusseosetting($id)
    {
        $seosetting = $this->seosetting->find($id);
        if($seosetting->status==0){
            $seosetting->status = 1;
        }elseif($seosetting->status==1){
            $seosetting->status = 0;
        }
        $seosetting->update();
        return $seosetting;
    }



}