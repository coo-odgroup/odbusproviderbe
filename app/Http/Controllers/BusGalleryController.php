<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusGallery;
use App\Services\BusGalleryService;
use Illuminate\Support\Facades\Validator;
use App\AppValidator\BusGalleryValidator;
use App\Repositories\BusGalleryRepository;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;


use Exception;

class BusGalleryController extends Controller
{
    use ApiResponser;

    /**
     * @var busGalleryService
     */
    protected $busGalleryService;
    protected $busGalleryValidator;
    protected $busGalleryRepository;
    /**
     * PostController Constructor
     *
     * @param BusGalleryService $busGalleryService
     *
     */
    public function __construct(BusGalleryService $busGalleryService,
                                 BusGalleryValidator $busGalleryValidator,
                                 BusGalleryRepository $busGalleryRepository)
    {
        $this->busGalleryService = $busGalleryService;
        $this->busGalleryValidator = $busGalleryValidator;
        $this->busGalleryRepository = $busGalleryRepository;
    }
    public function getAllBusGallery() {
        //$busGallery = $this->busGalleryService->getAll();
        $busGallery = $this->busGalleryRepository->getAll();
        return $this->successResponse($busGallery, Config::get('constants.RECORD_FETCHED'), Response::HTTP_CREATED);
    }
    public function viewBusGallery(Request $request) {

        $data = $request->only([
            'bus_id',
            'bus_operator_id',
            'rows_number',
            'USER_BUS_OPERATOR_ID'
          ]);
        //$busGallery = $this->busGalleryService->viewBusGallery($data);
        $busGallery = $this->busGalleryRepository->viewBusGallery($data);

         return $this->successResponse($busGallery, Config::get('constants.RECORD_FETCHED'), Response::HTTP_CREATED);
    }

    public function addBusGallery(Request $request)
    {
      // log:info($request);
        $data = $request->only([
            'bus_id',
            'bus_operator_id',
            'bus_image_1',
            'bus_image_2',
            'bus_image_3',
            'bus_image_4',
            'bus_image_5',
            'created_by',
          ]);
          $busGalleryValidation = $this->busGalleryValidator->validate($data);
          if ($busGalleryValidation->fails()) {
            $errors = $busGalleryValidation->errors();
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
          }
           else
        {
         // $response =  $this->busGalleryService->savePostData($data);
         $response = $this->busGalleryRepository->save($data);

           if($response=='Bus Already Exist')
           {
              return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
           }
           else
           {
               return $this->successResponse($response,"Bus Gallery Image Added", Response::HTTP_CREATED);
           }

        }

        //   try {
        //     $response = $this->busGalleryService->savePostData($data);
        //     return $this->successResponse($response, "Bus Gallery Image Added", Response::HTTP_CREATED);
        // }
        // catch(Exception $e){
        //     return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        // }	
    }

    public function deleteBusGallery ($id) {
        //$this->busGalleryService->deleteById($id);
        $this->busGalleryRepository->delete($id);
        $output ['status']=1;
        $output ['message']='Gallery Image Deleted ';
        return response($output, 200);
    }
  
    public function getBusGallery($id) {
        //$ame= $this->busGalleryService->getById($id);
        $ame = $this->busGalleryRepository->getById($id);
        $output ['status']=1;
        $output ['message']='Single Data Fetched Successfully';
        $output ['result']=$ame;
        return response($output, 200);
    }
    public function getBusGalleryBus($bid) {
        //$ame= $this->busGalleryService->getByBusId($bid);
        $ame = $this->busGalleryRepository->getByBusId($bid);
        $output ['status']=1;
        $output ['message']='Single Data Fetched Successfully';
        $output ['result']=$ame;
        return response($output, 200);
    }
    public function updateGallery(Request $request) {
      // log::info($request);exit;
        $data = $request->only([
          'id',
          'bus_id',
          'bus_operator_id',
          'bus_image_1',
          'bus_image_2',
          'bus_image_3',
          'bus_image_4',
          'bus_image_5',
          'created_by',
        ]);

        $busGalleryValidation = $this->busGalleryValidator->validate($data);
        if ($busGalleryValidation->fails()) {
            $errors = $busGalleryValidation->errors();
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
          }
        else
        {
         // $response =  $this->busGalleryService->updatePost($data);
          $response = $this->busGalleryRepository->update($data);

           if($response=='Bus Already Exist')
           {
              return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
           }
           else
           {
               return $this->successResponse($response,"Bus Gallery Image Updated", Response::HTTP_CREATED);
           }

        }
    }
}
