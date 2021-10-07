<?php

namespace App\Repositories;

use App\Models\Slider;

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

    public function save($data)
    {
        $slide = new $this->slider;
        $slide->occassion = $data['occassion'];
        $slide->category = $data['category'];
        $slide->url = $data['url'];
        $slide->slider_img = $data['slider_img'];
        $slide->alt_tag = $data['alt_tag'];
        $slide->start_date = $data['start_date'];
        $slide->start_time = $data['start_time'];
        $slide->end_date = $data['end_date'];
        $slide->end_time = $data['end_time'];
        $slide->created_by = "Admin";
        $slide->save();

        return $slide->fresh();
    }

    public function update($data, $id)
    {
        $slide = $this->slider->find($id);
        $slide->occassion = $data['occassion'];
        $slide->url = $data['url'];
        $slide->slider_img = $data['slider_img'];
        $slide->alt_tag = $data['alt_tag'];
        $slide->start_date = $data['start_date'];
        $slide->start_time = $data['start_time'];
        $slide->end_date = $data['end_date'];
        $slide->end_time = $data['end_time'];
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