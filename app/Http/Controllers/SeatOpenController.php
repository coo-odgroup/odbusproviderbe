<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SeatOpenService;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;


class SeatOpenController extends Controller
{
    use ApiResponser;
   
    protected $seatopenService;
   
    
    
    public function __construct(SeatOpenService $seatopenService)
    {
        $this->seatopenService = $seatopenService;        
    }

    public function getAllseatopen()
    {
        $seatopen = $this->seatopenService->getAll();
        return $this->successResponse($seatopen,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

     public function addseatopen(Request $request)
     {
        $seatopen = $this->seatopenService->addseatopen($request);
            return $this->successResponse($seatopen,"Seat Open  Added",Response::HTTP_OK);

     } 

     public function editseatOpen(Request $request)
     {
        $seatopen = $this->seatopenService->editseatOpen($request);
            return $this->successResponse($seatopen,"Seat Open  Added",Response::HTTP_OK);

     } 


     public function updateSeatOpenData(Request $request)
     {
        $seatopen = $this->seatopenService->updateSeatOpenData($request);
            return $this->successResponse($seatopen,"Seat Open  Added",Response::HTTP_OK);

     }
     public function updateseatopen(Request $request, $id)
     {

      // Log::info($request); exit;
        $seatopen = $this->seatopenService->updateseatopen($request, $id);
            return $this->successResponse($seatopen,"Seat Open Updated",Response::HTTP_OK);

     }


      public function getseatopenDT(Request $request) {      
        
        $seatopen = $this->seatopenService->getseatopenDT($request);
        return $this->successResponse($seatopen,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
        
      }

      public function seatopenData(Request $request) {      
        
        $seatopen = $this->seatopenService->seatopenData($request);
        return $this->successResponse($seatopen,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
        
      }

      public function alreadyOpen(Request $request) {      
        
        $seatopen = $this->seatopenService->alreadyOpen($request);
        return $this->successResponse($seatopen,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
        
      }

      public function changeStatus ($id) {
    
        try{
          $this->seatopenService->changeStatus($id);
        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, "Seat Open  Status Updated", Response::HTTP_ACCEPTED);
      }

      public function deleteseatopen (Request $request) {
      try{
        $this->seatopenService->deleteById($request);
      }
      catch (Exception $e){
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(null, "Seat Open Deleted", Response::HTTP_ACCEPTED);
    }
    
     

}