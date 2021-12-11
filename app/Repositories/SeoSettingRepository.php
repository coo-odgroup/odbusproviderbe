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
       // Log::info($data);exit; 

       $seosetting = new $this->seosetting;
       $seosetting->page_url =$data['page_url'];
       $seosetting->seo_type =$data['seo_type'];
       if($data['seo_type'] == 2)
       {
       $seosetting->source_id =null;
       $seosetting->destination_id = null;
       $seosetting->url_description ='';
       }
       else
       {
         $seosetting->source_id =$data['source_id'];
         $seosetting->destination_id =$data['destination_id'];
         $seosetting->url_description =$data['url_description'];
       }  
       $seosetting->bus_operator_id =$data['bus_operator_id'];
       $seosetting->url_description =$data['url_description'];
       $seosetting->meta_title =$data['meta_title'];
       $seosetting->meta_keyword =$data['meta_keyword'];
       $seosetting->meta_description =$data['meta_description'];
       $seosetting->extra_meta =$data['extra_meta'];
       $seosetting->canonical_url =$data['canonical_url'];
       $seosetting->created_by = $data['created_by'];
       $seosetting->save();

       return $seosetting;

    }

    public function seosettingData($request)
    {        
      
        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;
        $operator_id = $request['bus_operator_id'] ;
       

        $data= $this->seosetting->with('BusOperator')->whereNotIn('status', [2])
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
            $data=$data->where('page_url', $name)->orwhere('created_by', 'like', '%' .$name . '%');
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

    public function updateseosetting($data, $id)
    {
      
     $seosetting = $this->seosetting->find($id);   
     $seosetting->seo_type =$data['seo_type'];
     if($data['seo_type'] == 2)
     {
       $seosetting->source_id =null;
       $seosetting->destination_id = null;
       $seosetting->url_description ='';
     }
     else
     {
       $seosetting->source_id =$data['source_id'];
       $seosetting->destination_id =$data['destination_id'];
       $seosetting->url_description =$data['url_description'];
     }
         
     $seosetting->bus_operator_id =$data['bus_operator_id'];
	   $seosetting->page_url =$data['page_url'];     
	   $seosetting->meta_title =$data['meta_title'];
	   $seosetting->meta_keyword =$data['meta_keyword'];
	   $seosetting->meta_description =$data['meta_description'];
	   $seosetting->extra_meta =$data['extra_meta'];
	   $seosetting->canonical_url =$data['canonical_url'];
	   $seosetting->created_by =$data['created_by'];
     // Log::info($seosetting);
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