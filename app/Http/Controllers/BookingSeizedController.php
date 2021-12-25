<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BookingSeizedService;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;


class BookingSeizedController extends Controller
{
    use ApiResponser;
   
    protected $bookingseizedService;
      
    public function __construct(BookingSeizedService $bookingseizedService)
    {
        $this->bookingseizedService = $bookingseizedService;        
    }

    public function getAllseized()
    {

        $bookingseized = $this->bookingseizedService->getAll();
        return $this->successResponse($bookingseized,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 

    public function bookingseizedById($id)
    {

        $bookingseized = $this->bookingseizedService->bookingseizedById($id);
        return $this->successResponse($bookingseized,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
   
    public function saveSeized(Request $request)
    {

        $bookingseized = $this->bookingseizedService->saveSeized($request);
            return $this->successResponse($bookingseized,"Booking Seized Updated",Response::HTTP_OK);
    }  

     public function bookingseizedData(Request $request)
    {

        $bookingseized = $this->bookingseizedService->bookingseizedData($request);
            return $this->successResponse($bookingseized,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 


      public function deletebookingseized($id)
    {

        $bookingseized = $this->bookingseizedService->deletebookingseized($id);
            return $this->successResponse($bookingseized,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }   

    public function changeStatus($id) 
    {
    
        try{
          $this->bookingseizedService->changeStatus($id);
        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, "Booking Seized Status Updated", Response::HTTP_ACCEPTED);
    }

}