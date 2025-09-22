<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offers;
use App\Services\OffersService;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\OffersRepository;

use Exception;

class OffersController extends Controller
{
    use ApiResponser;

    /**
     * @var busGalleryService
     */
    protected $offersService;
    protected $offersRepository;
    /**
     * PostController Constructor
     *
     * @param BusGalleryService $busGalleryService
     *
     */
    public function __construct(OffersService $offersService,
        OffersRepository $offersRepository)
    {
        $this->offersRepository = $offersRepository;
        $this->offersService = $offersService;
    }
    
    public function getAllBusGallery() {
        //$offers = $this->offersService->getAll();
        $offers = $this->offersRepository->getAll();
       
        return $this->successResponse($offers, Config::get('constants.RECORD_FETCHED'), Response::HTTP_CREATED);
    }
    public function getOffersDT(Request $request)
    {
        //$result = $this->offersService->dataTable($request);
        $result = $this->offersRepository->getDatatable($request);
        return $this->successResponse($result,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function addBusGallery(Request $request)
    {

        try {
            //$response = $this->offersService->savePostData($data);
            $data = $request->all();
               $response = $this->offersRepository->save($data);
            return $this->successResponse($response, Config::get('constants.RECORD_ADDED'), Response::HTTP_CREATED);
        }
        catch(Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }	
    }

    public function deleteBusGallery ($id) {
        //$response=$this->offersService->deleteById($id);
        $response=$this->offersRepository->delete($id);
        return $this->successResponse($response, Config::get('constants.RECORD_REMOVED'), Response::HTTP_CREATED);
    }
  
    public function getBusGallery($id) {
       // $ame= $this->offersService->getById($id);
        $ame= $this->offersRepository->getById($id);
        $output ['status']=1;
        $output ['message']='Single Data Fetched Successfully';
        $output ['result']=$ame;
        return $this->successResponse($output, Config::get('constants.RECORD_FETCHED'), Response::HTTP_CREATED);
    }
    public function getBusGalleryBus($bid) {
        //$ame= $this->offersService->getByBusId($bid);
        $ame= $this->offersRepository->getByBusId($bid);
        $output ['status']=1;
        $output ['message']='Single Data Fetched Successfully';
        $output ['result']=$ame;
        return $this->successResponse($output, Config::get('constants.RECORD_FETCHED'), Response::HTTP_CREATED);
    }






}
