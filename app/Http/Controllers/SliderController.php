<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Slider;

use App\Services\SliderService;
use Exception;
use Illuminate\Support\Facades\Validator;


class SliderController extends Controller
{
     /**
     * @var sliderService
     */
    protected $sliderService;

    /**
     * PostController Constructor
     *
     * @param SliderService $sliderService
     *
     */
    public function __construct(SliderService $sliderService)
    {
        $this->sliderService = $sliderService;
    }



    public function getAllSlider() {
        $prod = $this->sliderService->getAll();;
        $output ['status']=1;
        $output ['message']='All Data Fetched Successfully';
        $output ['result']=$prod;
        return response($output, 200);
    }

    public function createSlider(Request $request) {
        $data = $request->only([
            'occassion', 'url', 'slider_img','alt_tag','start_date','end_date',
          
        ]);
        
        $sliderRules = [
            'occassion' => 'required',
            'url' => 'required',
            'slider_img' => 'required',
            'alt_tag' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
          ];
        
          $sliderValidation = Validator::make($data, $sliderRules);
          
          if ($sliderValidation->fails()) {
            $errors = $sliderValidation->errors();
            return $errors->toJson();
          }
        $this->sliderService->savePostData($data);
    
        $output ['status']=1;
        $output ['message']='Data Added Successfully';
        return response($output, 200);
	
    } 

    public function updateSlider(Request $request, $id) {
        $data = $request->only([
            'occassion', 'url', 'slider_img','alt_tag','start_date','end_date',
          
        ]);
        $sliderRules = [
            'occassion' => 'required',
            'url' => 'required',
            'slider_img' => 'required',
            'alt_tag' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ];
        
        $sliderValidation = Validator::make($data, $sliderRules);
          
        if ($sliderValidation->fails()) {
            $errors = $sliderValidation->errors();
            return $errors->toJson();
        }
        $this->sliderService->updatePost($data, $id);
        $output ['status']=1;
        $output ['message']='Data updated successfully';
        return response($output, 200);
        
    }

    public function deleteSlider ($id) {
      $this->sliderService->deleteById($id);
      $output ['status']=1;
      $output ['message']='Data Deleted successfully';
      return response($output, 200);
    }

    public function getSlider($id) {
      $ame= $this->sliderService->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$ame;
      return response($output, 200);


  	}
}
