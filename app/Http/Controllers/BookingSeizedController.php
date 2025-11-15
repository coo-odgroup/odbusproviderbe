<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use App\Repositories\BusContactsRepository;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\BookingSeizedRepository;

class BookingSeizedController extends Controller
{
    use ApiResponser;


    protected $busContactsRepository;
    protected $bookingseizedRepository;

    public function __construct(
        busContactsRepository $busContactsRepository,
        BookingSeizedRepository $bookingseizedRepository
    ) {

        $this->busContactsRepository = $busContactsRepository;
        $this->bookingseizedRepository = $bookingseizedRepository;
    }

    public function getAllseized()
    {


        $bookingseized = $this->busContactsRepository->getAll();
        return $this->successResponse($bookingseized, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function bookingseizedById($id)
    {

       
        $bookingseized = $this->bookingseizedRepository->bookingseizedById($id);
        return $this->successResponse($bookingseized, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function saveSeized(Request $request)
    {

    
        $bookingseized = $$this->bookingseizedRepository->save($request);
        return $this->successResponse($bookingseized, "Booking Seized Updated", Response::HTTP_OK);
    }

    public function bookingseizedData(Request $request)
    {

        
        $bookingseized = $this->bookingseizedRepository->bookingseizedData($request);
        return $this->successResponse($bookingseized, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }


    public function deletebookingseized($id)
    {

        
        $bookingseized = $this->bookingseizedRepository->deletebookingseized($id);
        return $this->successResponse($bookingseized, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function changeStatus($id)
    {

        try {
           
            $this->bookingseizedRepository->changeStatus($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, "Booking Seized Status Updated", Response::HTTP_ACCEPTED);
    }
}
