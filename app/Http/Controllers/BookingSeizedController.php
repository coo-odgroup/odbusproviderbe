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
   
    public function updateSeized(Request $request)
    {

        $bookingseized = $this->bookingseizedService->updateSeized($request);
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
        return $this->successResponse(null, Config::get('constants.RECORD_UPDATED'), Response::HTTP_ACCEPTED);
    }

}