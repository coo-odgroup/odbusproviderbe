<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use App\Services\SliderService;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use App\AppValidator\SliderValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;


class SliderController extends Controller
{
    use ApiResponser;  
    protected $sliderService;
    protected $sliderValidator;

    public function __construct(SliderService $sliderService, SliderValidator $sliderValidator)
    {
        $this->sliderService = $sliderService;
        $this->sliderValidator = $sliderValidator;
    }

    public function getAllSlider() {
        $slider = $this->sliderService->getAllSlider();
        return $this->successResponse($slider,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function getData(Request $request)
    {
        $sliderData = $this->sliderService->getData($request);
        return $this->successResponse($sliderData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function createSlider(Request $request) {

        $data = $request->all();
        $sliderValidation = $this->sliderValidator->validate($data);
        if ($sliderValidation->fails()) {
          $errors = $sliderValidation->errors();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
          $response = $this->sliderService->save($data);
          return $this->successResponse($response, "Special Slider Added", Response::HTTP_CREATED);
      }
      catch(Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }	
    } 

    public function updateSlider(Request $request) {
      
         $data = $request->all();
         $sliderValidation = $this->sliderValidator->validate($data);
         if ($sliderValidation->fails()) {
           $errors = $sliderValidation->errors();
           return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
         }
        try {
          $response = $this->sliderService->update($data);
          return $this->successResponse($response, "Special Slider Updated", Response::HTTP_CREATED);

      } catch (Exception $e) {
          return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
    }
    public function deleteSlider($id) {
        try{
          $response = $this->sliderService->deleteById($id);
          return $this->successResponse($response, "Special Slider Deleted", Response::HTTP_ACCEPTED);
        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        } 
      }
      public function getSlider($id) { 
        try{
          $slider= $this->sliderService->getById($id);
        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($slider, Config::get('constants.RECORD_FETCHED'), Response::HTTP_ACCEPTED);
      }
      public function changeStatus ($id) {
        try{
          $response = $this->sliderService->changeStatus($id);
          return $this->successResponse($response, "Special Slider Status Updated", Response::HTTP_ACCEPTED);
        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
       
      }
}
