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
        $paginate = $request['paginate'];
        $searchBy = $request['searchBy']; 
        $status = $request['status'];

        $list = $this->slider->where('slider', 'like', '%' .$searchBy . '%')->where('status', $status)->orderBy('id','desc');
        $list =  $list->paginate($paginate);

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
        $slide->slider = $data['slider'];
        $slide->occassion = $data['occassion'];
        $slide->category = $data['category'];
        $slide->url = $data['url'];
        $slide->slider_img = $data['slider_img'];
        $slide->alt_tag = $data['alt_tag'];
        $slide->start_date = $data['start_date'];
        $slide->end_date = $data['end_date'];
        $slide->created_by = "Admin";
        $slide->save();

        return $slide->fresh();
    }

    public function update($data, $id)
    {
        $slide = $this->slider->find($id);
        $slide->slider = $data['slider'];
        $slide->occassion = $data['occassion'];
        $slide->url = $data['url'];
        $slide->slider_img = $data['slider_img'];
        $slide->alt_tag = $data['alt_tag'];
        $slide->start_date = $data['start_date'];
        $slide->end_date = $data['end_date'];
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

}