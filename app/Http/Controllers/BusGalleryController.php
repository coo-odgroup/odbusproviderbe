<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\BusGalleryRepository;
use App\AppValidator\BusGalleryValidator;
use App\Traits\ApiResponser;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Config;
use Exception;

class BusGalleryController extends Controller
{
    use ApiResponser;

    protected $busGalleryRepository;
    protected $busGalleryValidator;

    public function __construct(
        BusGalleryRepository $busGalleryRepository,
        BusGalleryValidator $busGalleryValidator
    ) {
        $this->busGalleryRepository = $busGalleryRepository;
        $this->busGalleryValidator = $busGalleryValidator;
    }

    /**
     * Get all Bus Galleries
     */
    public function getAllBusGallery()
    {
        try {
            $busGallery = $this->busGalleryRepository->getAll();
            return $this->successResponse(
                $busGallery,
                Config::get('constants.RECORD_FETCHED'),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * View Bus Galleries with filters and pagination
     */
    public function viewBusGallery(Request $request)
    {
        try {
            $data = $request->only([
                'bus_id',
                'bus_operator_id',
                'rows_number',
                'USER_BUS_OPERATOR_ID'
            ]);

            $busGallery = $this->busGalleryRepository->viewBusGallery($data);

            return $this->successResponse(
                $busGallery,
                Config::get('constants.RECORD_FETCHED'),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Add new Bus Gallery
     */
    public function addBusGallery(Request $request)
    {
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

        $validation = $this->busGalleryValidator->validate($data);
        if ($validation->fails()) {
            return $this->errorResponse($validation->errors()->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        try {
            $response = $this->busGalleryRepository->save($data);

            if ($response === 'Bus Already Exist') {
                return $this->errorResponse($response, Response::HTTP_CONFLICT);
            }

            return $this->successResponse($response, "Bus Gallery Image Added", Response::HTTP_CREATED);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        Log::info('BusGallery Request:', $request->all());

    }

    /**
     * Update existing Bus Gallery
     */
    public function updateGallery(Request $request)
    {
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

        $validation = $this->busGalleryValidator->validate($data);
        if ($validation->fails()) {
            return $this->errorResponse($validation->errors()->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        try {
            $response = $this->busGalleryRepository->update($data);

            if ($response === 'Bus Already Exist') {
                return $this->errorResponse($response, Response::HTTP_CONFLICT);
            }

            return $this->successResponse($response, "Bus Gallery Image Updated", Response::HTTP_OK);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete Bus Gallery by ID
     */
    public function deleteBusGallery($id)
    {
        try {
            $this->busGalleryRepository->delete($id);
            return $this->successResponse(null, "Gallery Image Deleted", Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get Bus Gallery by ID
     */
    public function getBusGallery($id)
    {
        try {
            $gallery = $this->busGalleryRepository->getById($id);
            return $this->successResponse($gallery, "Single Data Fetched Successfully", Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get Bus Gallery by Bus ID
     */
    public function getBusGalleryBus($bid)
    {
        try {
            $gallery = $this->busGalleryRepository->getByBusId($bid);
            return $this->successResponse($gallery, "Single Data Fetched Successfully", Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
