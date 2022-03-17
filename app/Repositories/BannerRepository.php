<?php

namespace App\Repositories;

use App\Models\Banner;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File; 

class BannerRepository
{
    
    public function __construct(Banner $banner)
    {
        $this->banner = $banner;
    }
    public function getAllBanner()
    {
        return $this->banner->whereNotIn('status', [2])->get();
    }
    public function getData($request)
    {
        // Log::info($request);
        
        $paginate = $request['per_page'];
        $searchBy = $request['searchBy']; 
        $status = $request['status'];
        $userID = $request['userID'] ;
        $role_id = $request['role_id'] ;

       
        if($searchBy!='' && $status!=''){
            $list = $this->banner->with('User')
                                 ->where('occassion', 'like', '%' .$searchBy . '%')
                                 ->where('status', $status) 
                                 ->orWhereHas('User', function ($query) use ($searchBy){$query->where('name', 'like', '%' .$searchBy . '%');
                            })->whereNotIn('status', [2])->orderBy('id','desc');

        }elseif($searchBy!='' && $status==''){
            $list = $this->banner->with('User')->where('occassion', $searchBy)
                                 ->orWhereHas('User', function ($query) use ($searchBy){
                                    $query->where('name', 'like', '%' .$searchBy . '%');
                                   })->whereNotIn('status', [2])->orderBy('id','desc');
        }elseif($searchBy=='' && $status!=''){
            $list = $this->banner->with('User')->where('status', $status)
                                 ->whereNotIn('status', [2])->orderBy('id','desc');
        }else{
            $list = $this->banner->with('User')->whereNotIn('status', [2])->orderBy('id','desc');    
        }

         if($userID!= null && $role_id!= 1 )
          {
            $list = $list->Where('user_id', $userID);
          }

        $list =  $list->paginate($paginate);
        //return $list;
        $response = array(
            "count" => $list->count(), 
            "total" => $list->total(),
            "data" => $list
           );   
           return $response;
                            
    }
    public function getById($id)
    {
        return $this->banner
            ->where('id', $id)
            ->get();
    }
     public function getModel($data, Banner $banner)
    {
        $banner->occassion = $data['occassion'];
        // $banner->category = $data['category'];
        $banner->url = $data['url'];
        $banner->heading = $data['heading'];
        $banner->alt_tag = $data['alt_tag'];
        $banner->start_date = $data['start_date'];
        $banner->start_time = $data['start_time'];
        $banner->end_date = $data['end_date'];
        $banner->end_time = $data['end_time'];
        $banner->created_by =  $data['created_by'];
        $banner->user_id = $data['user_id'];
        return $banner;
    }

    public function save($data)
    {
        
        $picture="";
        $bannerObject = new $this->banner;
        $banner=$this->getModel($data,$bannerObject);
        $file = collect($data)->get('banner_img');  
        if(($file)!=null)
        {
            $filename  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture   =  rand().'-'.$filename;
            $banner->banner_image = $picture;
            $file->move(public_path('uploads/operatorbanner/'), $picture);          
        }

        $banner->save();

        return $banner ;
    }

    public function update($data)
    {
        $id = $data['id'] ;
        $banner_detail  = $this->banner->where('id', $id)->get();
        $existing_image = $banner_detail[0]->banner_image;

        $banner = $this->banner->find($id);
        $file = collect($data)->get('banner_img');
        
        if($file !="null")
        {
            $banner=$this->getModel($data,$banner);
            $filename  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture =  rand().'-'.$filename;
            $banner->banner_image =  $picture;
       
            $file->move(public_path('uploads/operatorbanner/'), $picture);

            $old_image_path_consumer = public_path('uploads/operatorbanner/').$existing_image;
            if(isset($existing_image))
            {
                if(File::exists($old_image_path_consumer))
                 {
                    unlink($old_image_path_consumer);
                 }  
            }
                   
        }
        else
        {
             $banner=$this->getModel($data,$banner);
        }
        $banner->update();
        return $banner;
    }

    public function delete($id)
    {
        $bann = $this->banner->find($id);
        $bann->status = 2;
        $bann->update();

        return $bann;
    }
    public function changeStatus($id)
    {
        $bann = $this->banner->find($id);
        if($bann->status==0){
            $bann->status = 1;
        }elseif($bann->status==1){
            $bann->status = 0;
        }
        $bann->update();
        return $bann;
    }

}