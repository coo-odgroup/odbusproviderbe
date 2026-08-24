<?php

namespace App\Repositories;

use App\Models\Coupon;
use App\Models\Slider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

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
        $userID = $request['userID'];
        $role_id = $request['role_id'];
        //return $request->all();

        if ($searchBy != '' && $status != '') {
            $list = $this->slider->where('occassion', 'like', '%' . $searchBy . '%')
                ->where('status', $status)
                ->whereNotIn('status', [2])
                ->orderBy('id', 'desc');
        } elseif ($searchBy != '' && $status == '') {
            $list = $this->slider->where('occassion', $searchBy)
                ->whereNotIn('status', [2])
                ->orderBy('id', 'desc');
        } elseif ($searchBy == '' && $status != '') {
            $list = $this->slider->where('status', $status)
                ->whereNotIn('status', [2])
                ->orderBy('id', 'desc');
        } else {
            $list = $this->slider->with('coupon')->whereNotIn('status', [2])
                ->orderBy('id', 'desc');
        }
        if ($userID != null && $role_id != 1) {
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
        if ($data['coupon_id'] != 'null') {
            $slide->coupon_id  = $data['coupon_id'];
        } else {
            $slide->coupon_id  = 0;
        }

        $slide->unique_id = strtoupper(Str::random(8));
        $slide->slider_description = $data['slider_description'];
        $slide->created_by = $data['created_by'];
        return $slide;
    }

    public function save($data)
    {
        // return $data['coupon_id'];
        $unique_id = Coupon::where('id',$data['coupon_id'])->value('unique_id');

        // return $unique_id;
        $slide = new $this->slider();
        $slide = $this->getModel($data, $slide);

        // Slider Image
        $file = collect($data)->get('slider_img');

        if ($file) {

            $picture = time() . rand(1000, 9999) . '.webp';

            Image::make($file)
                ->encode('webp', 80)
                ->save(public_path('uploads/slider_photos/' . $picture));

            $slide->slider_photo = $picture;
        }

        // Android Image
        $android_file = collect($data)->get('android_image');

        if ($android_file) {

            $picture = time() . rand(1000, 9999) . '.webp';

            Image::make($android_file)
                ->encode('webp', 80)
                ->save(public_path('uploads/slider_photos/' . $picture));

            $slide->android_image = $picture;
        }

        $slide->coupon_unique_id = $unique_id;
        $slide->save();

        return $slide->fresh();
    }

    public function update($data)
    {
        $sliderId = $data['id'];

        $slide = $this->slider->find($sliderId);

        $slide = $this->getModel($data, $slide);

        $file = collect($data)->get('slider_img');
        $android_file = collect($data)->get('android_image');

        // Update Slider Image
        if ($file && $file != 'null' && $file != 'undefined') {

            $picture = time() . rand(1000, 9999) . '.webp';

            Image::make($file)
                ->encode('webp', 80)
                ->save(public_path('uploads/slider_photos/' . $picture));

            // Delete old image
            if (!empty($slide->slider_photo)) {
                $oldImage = public_path('uploads/slider_photos/' . $slide->slider_photo);

                if (File::exists($oldImage)) {
                    File::delete($oldImage);
                }
            }

            $slide->slider_photo = $picture;
        }

        // Update Android Image
        if ($android_file && $android_file != 'null' && $android_file != 'undefined') {

            $picture = time() . rand(1000, 9999) . '.webp';

            Image::make($android_file)
                ->encode('webp', 80)
                ->save(public_path('uploads/slider_photos/' . $picture));

            // Delete old image
            if (!empty($slide->android_image)) {
                $oldImage = public_path('uploads/slider_photos/' . $slide->android_image);

                if (File::exists($oldImage)) {
                    File::delete($oldImage);
                }
            }

            $slide->android_image = $picture;
        }

        $slide->save();

        return $slide->fresh();
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
        if ($slide->status == 0) {
            $slide->status = 1;
        } elseif ($slide->status == 1) {
            $slide->status = 0;
        }
        $slide->update();
        return $slide;
    }
}
