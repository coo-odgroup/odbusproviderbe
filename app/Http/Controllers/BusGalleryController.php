<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusGallery;
use App\Services\BusGalleryService;
use Illuminate\Support\Facades\Validator;
use App\AppValidator\BusGalleryValidator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

use Exception;

class BusGalleryController extends Controller
{
    use ApiResponser;

    /**
     * @var busGalleryService
     */
    protected $busGalleryService;
    protected $busGalleryValidator;
    /**
     * PostController Constructor
     *
     * @param BusGalleryService $busGalleryService
     *
     */
    public function __construct(BusGalleryService $busGalleryService, BusGalleryValidator $busGalleryValidator)
    {
        $this->busGalleryService = $busGalleryService;
        $this->busGalleryValidator = $busGalleryValidator;
    }
    public function getAllBusGallery() {
        $busGallery = $this->busGalleryService->getAll();;
        return $this->successResponse($busGallery, Config::get('constants.RECORD_FETCHED'), Response::HTTP_CREATED);
    }
    public function viewBusGallery(Request $request) {

        $data = $request->only([
            'bus_id',
            'rows_number',
          ]);
        $busGallery = $this->busGalleryService->viewBusGallery($data);

         return $this->successResponse($busGallery, Config::get('constants.RECORD_FETCHED'), Response::HTTP_CREATED);
    }

    public function addBusGallery(Request $request)
    {

        $data = $request->only([
            'bus_id',
            'icon',
            'created_by',
          ]);
          $busGalleryValidation = $this->busGalleryValidator->validate($data);
          if ($busGalleryValidation->fails()) {
            $errors = $busGalleryValidation->errors();
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
          }
          try {
            $response = $this->busGalleryService->savePostData($data);
            return $this->successResponse($response, Config::get('constants.RECORD_ADDED'), Response::HTTP_CREATED);
        }
        catch(Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }	
    }

    public function deleteBusGallery ($id) {
        $this->busGalleryService->deleteById($id);
        $output ['status']=1;
        $output ['message']='Data Deleted successfully';
        return response($output, 200);
    }
  
    public function getBusGallery($id) {
        $ame= $this->busGalleryService->getById($id);
        $output ['status']=1;
        $output ['message']='Single Data Fetched Successfully';
        $output ['result']=$ame;
        return response($output, 200);
    }
    public function getBusGalleryBus($bid) {
        $ame= $this->busGalleryService->getByBusId($bid);
        $output ['status']=1;
        $output ['message']='Single Data Fetched Successfully';
        $output ['result']=$ame;
        return response($output, 200);
    }






}
