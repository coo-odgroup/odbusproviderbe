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
        //return $request->all();
       
        if($searchBy!='' && $status!=''){
            $list = $this->slider->where('occassion', 'like', '%' .$searchBy . '%')->where('status', $status)
                                 ->whereNotIn('status', [2])->orderBy('id','desc');
        }elseif($searchBy!='' && $status==''){
            $list = $this->slider->where('occassion', $searchBy)
                                 ->whereNotIn('status', [2])->orderBy('id','desc');
        }elseif($searchBy=='' && $status!=''){
            $list = $this->slider->where('status', $status)
                                 ->whereNotIn('status', [2])->orderBy('id','desc');
        }else{
            $list = $this->slider->whereNotIn('status', [2])->orderBy('id','desc');    
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
        $slide->bus_operator_id = $data['bus_operator_id'];
        $slide->occassion = $data['occassion'];
        $slide->url = $data['url'];
        $slide->slider_img = $data['slider_img'];
        $slide->alt_tag = $data['alt_tag'];
        $slide->start_date = $data['start_date'];
        $slide->start_time = $data['start_time'];
        $slide->end_date = $data['end_date'];
        $slide->end_time = $data['end_time'];
        $slide->created_by = $data['created_by'];
        return $slide;
    }
    public function save($data)
    {
        $slide = new $this->slider;
        $slide = $this->getModel($data,$slide);
        $file = collect($data)->get('slider_img');
        //Log::info($file);
        if(($file)!=null){

            $filename  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture   = $filename;
            $slide->slider_photo = $picture;
            $file->move(Config::get('constants.UPLOAD_PATH_CONSUMER').'slider_photos', $picture);
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

        if(($file)!='null'){
            $slide=$this->getModel($data,$slide);
            $filename  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture   = $filename;
            $slide->slider_photo = $picture;
            $file->move(Config::get('constants.UPLOAD_PATH_CONSUMER').'slider_photos', $picture);
           
          if($slider_data[0]->slider_photo !=''){
            
             $old_image_path_consumer = Config::get('constants.UPLOAD_PATH_CONSUMER').'slider_photos/'.$slider_data[0]->slider_photo;
             if(File::exists($old_image_path_consumer)){
                    unlink($old_image_path_consumer);
                }     
          }
            
        }else{
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