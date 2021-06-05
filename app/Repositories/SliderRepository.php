<?php

namespace App\Repositories;

use App\Models\Slider;

class SliderRepository
{
    /**
     * @var Slider
     */
    protected $slider;

    /**
     * SliderRepository constructor.
     *
     * @param Slider $slider
     */
    public function __construct(Slider $slider)
    {
        $this->slider = $slider;
    }

    /**
     * Get all slider.
     *
     * @return Slider $slider
     */
    public function getAll()
    {
        return $this->slider->whereNotIn('status', [2])->get();
    }

    /**
     * Get slider by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->slider
            ->where('id', $id)
            ->get();
    }

    /**
     * Save slider
     *
     * @param $data
     * @return Slider
     */
    public function save($data)
    {
        $post = new $this->slider;

        $post->occassion = $data['occassion'];
        $post->url = $data['url'];
        $post->slider_img = $data['slider_img'];
        $post->alt_tag = $data['alt_tag'];
        $post->start_date = $data['start_date'];
        $post->end_date = $data['end_date'];
        // $post->created_date = date('Y-m-d H:i:s');
        $post->created_by = "Admin";

        $post->save();

        return $post->fresh();
    }

    /**
     * Update slider
     *
     * @param $data
     * @return Slider
     */
    public function update($data, $id)
    {
        
        $post = $this->slider->find($id);

        $post->occassion = $data['occassion'];
        $post->url = $data['url'];
        $post->slider_img = $data['slider_img'];
        $post->alt_tag = $data['alt_tag'];
        $post->start_date = $data['start_date'];
        $post->end_date = $data['end_date'];

        $post->update();

        return $post;
    }

    /**
     * Update slider
     *
     * @param $data
     * @return Slider
     */
    public function delete($id)
    {
        
        $post = $this->slider->find($id);
        $post->status = 2;
        $post->update();

        return $post;
    }

}