<?php

namespace App\Repositories;

use App\Models\Slider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File; 

class SliderRepository
{
    
    public function __construct(Slider $slider)
    {
        $this->slider = $slider;
    }

    public function getAllSlider()
    {
        return $this->slider->whereNotIn('status', [2])->get();
    }
    public function getData($request)
    {
        $paginate = $request['per_page'];
        $searchBy = $request['searchBy']; 
        $status = $request['status'];
        $userID = $request['userID'] ;
        $role_id = $request['role_id'] ;
        //return $request->all();
       
        if($searchBy!='' && $status!=''){
            $list = $this->slider->where('occassion', 'like', '%' .$searchBy . '%')
                                 ->where('status', $status)
                                 ->whereNotIn('status', [2])
                                 ->orderBy('id','desc');
        }elseif($searchBy!='' && $status==''){
            $list = $this->slider->where('occassion', $searchBy)
                                 ->whereNotIn('status', [2])
                                 ->orderBy('id','desc');
        }elseif($searchBy=='' && $status!=''){
            $list = $this->slider->where('status', $status)
                                 ->whereNotIn('status', [2])
                                 ->orderBy('id','desc');
        }else{
            $list = $this->slider->with('coupon')->whereNotIn('status', [2])
                                 ->orderBy('id','desc');    
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
        return $this->slider
            ->where('id', $id)
            ->get();
    }
    public function getModel($data, Slider $slide)
    {
        $slide->user_id = $data['user_id'];
        $slide->occassion = $data['occassion'];
        $slide->url = $data['url'];
        $slide->slider_img = $data['slider_img'];
        $slide->alt_tag = $data['alt_tag'];
        $slide->start_date = $data['start_date'];
        $slide->start_time = $data['start_time'];
        $slide->end_date = $data['end_date'];
        $slide->end_time = $data['end_time'];
        if($data['coupon_id']!= 'null'){
            $slide->coupon_id  = $data['coupon_id'];
        }else
        {
             $slide->coupon_id  = 0;
        }
        
       
        $slide->slider_description = $data['slider_description'];
        $slide->created_by = $data['created_by'];
        return $slide;
    }
    public function save($data)
    {
        // Log::info($data);exit;

        $slide = new $this->slider;
        $slide = $this->getModel($data,$slide);
        $file = collect($data)->get('slider_img');
        //Log::info($file);
        if(($file)!=null){

            $filename  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture   = $filename;
            $slide->slider_photo = $picture;
            $file->move(public_path('uploads/slider_photos/'), $picture);
       }

       $android_file = collect($data)->get('android_image');
        if(($android_file)!=null){

            $filename  = $android_file->getClientOriginalName();
            $extension = $android_file->getClientOriginalExtension();
            $picture   = $filename;
            $slide->android_image = $picture;
            $android_file->move(public_path('uploads/slider_photos/'), $picture);
       }
        $slide->save();
        return $slide->fresh();
    }

    public function update($data)
    {
        $sliderId = $data['id'];
        $slider_data = $this->slider->where('id', $sliderId)->get();
        $slide = $this->slider->find($sliderId); 
        $file = collect($data)->get('slider_img');

        $android_file = collect($data)->get('android_image');

        if(($file)!='null'){
            $slide=$this->getModel($data,$slide);
            $filename  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture   = $filename;
            $slide->slider_photo = $picture;
            $file->move(public_path('uploads/slider_photos/'), $picture);
           
          if($slider_data[0]->slider_photo !=''){
            
             $old_image_path_consumer = public_path('uploads/slider_photos/').$slider_data[0]->slider_photo;
             if(File::exists($old_image_path_consumer)){
                    unlink($old_image_path_consumer);
                }     
          }
            
        }
        elseif(($android_file)!='null' && ($android_file)!='undefined' ){
            $slide=$this->getModel($data,$slide);
            $filename  = $android_file->getClientOriginalName();
            $extension = $android_file->getClientOriginalExtension();
            $picture   = $filename;
            $slide->android_image = $picture;
            $android_file->move(public_path('uploads/slider_photos/'), $picture);
           
          if($slider_data[0]->android_image !=''){
            
             $old_image_path_consumer = public_path('uploads/slider_photos/').$slider_data[0]->android_image;
             if(File::exists($old_image_path_consumer)){
                    unlink($old_image_path_consumer);
                }     
          }
            
        }
        else{
             $slide=$this->getModel($data,$slide);
        }
        $slide->update();
        return $slide;
    }

    public function delete($id)
    {
        $slide = $this->slider->find($id);
        $slide->status = 2;
        $slide->update();

        return $slide;
    }
    public function changeStatus($id)
    {
        $slide = $this->slider->find($id);
        if($slide->status==0){
            $slide->status = 1;
        }elseif($slide->status==1){
            $slide->status = 0;
        }
        $slide->update();
        return $slide;
    }

}