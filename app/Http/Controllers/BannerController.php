<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Services\BannerService;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;
use App\Repositories\BannerRepository;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use App\AppValidator\BannerValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;


class BannerController extends Controller
{
    use ApiResponser;  
    protected $bannerService;
    protected $bannerValidator;
    protected $bannerRepository;

    public function __construct(BannerService $bannerService,
                                 BannerValidator $bannerValidator,
                                 BannerRepository $bannerRepository)
    {
        $this->bannerService = $bannerService;
        $this->bannerValidator = $bannerValidator;
        $this->bannerRepository = $bannerRepository;
    }

    public function getAllBanner() {
        //$banner = $this->sliderService->getAllBanner();
        $banner = $this->bannerRepository->getAllBanner();
        return $this->successResponse($banner,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function getData(Request $request)
    {
        //$bannerData = $this->bannerService->getData($request);
        $bannerData = $this->bannerRepository->getData($request);
        return $this->successResponse($bannerData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function createBanner(Request $request) {

        $data = $request->all();
        $bannerValidation = $this->bannerValidator->validate($data);
        if ($bannerValidation->fails()) {
          $errors = $bannerValidation->errors();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
          //$response = $this->bannerService->save($data);
          $response = $this->bannerRepository->save($data);
          return $this->successResponse($response, "Banner Added", Response::HTTP_CREATED);
      }
      catch(Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }	
    } 

    public function updateBanner(Request $request) {
      
         $data = $request->all();
        // $bannerValidation = $this->bannerValidator->validate($data);
        $bannerValidation = $this->bannerRepository->update($data);
         if ($bannerValidation->fails()) {
           $errors = $bannerValidation->errors();
           return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
         }
        try {
          //$response = $this->bannerService->update($data);
          $response = $this->bannerRepository->update($data);
          return $this->successResponse($response, "Banner Updated", Response::HTTP_CREATED);

      } catch (Exception $e) {
          return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
    }
    public function deleteBanner($id) {
        try{
         // $response = $this->bannerService->deleteById($id);
          $response  = $this->bannerRepository->delete($id);
          return $this->successResponse($response, "Banner Deleted", Response::HTTP_ACCEPTED);
        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        } 
      }
      public function getBanner($id) { 
        try{
          // $banner= $this->bannerService->getById($id);
          $banner = $this->bannerRepository->getById($id);
        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($banner, Config::get('constants.RECORD_FETCHED'), Response::HTTP_ACCEPTED);
      }
      public function changeStatus ($id) {
        try{
          //$response = $this->bannerService->changeStatus($id);
          $response = $this->bannerRepository->changeStatus($id);
          return $this->successResponse($response, "Banner Status Updated", Response::HTTP_ACCEPTED);
        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
       
      }
}
