<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SeatBlockService;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\AppValidator\LocationValidator;

class SeatBlockController extends Controller
{
    use ApiResponser;
   
    protected $seatblockService;
    protected $LocationValidator;
    
    
    public function __construct(SeatBlockService $seatblockService)
    {
        $this->seatblockService = $seatblockService;
        
    }

    public function getAllseatblock()
    {
        $seatblock = $this->seatblockService->getAll();
        return $this->successResponse($seatblock,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

     public function addseatblock(Request $request)
     {

      try{
        $res = $this->seatblockService->addseatblock($request);

        if(isset($res['status']) && $res['status'] == 'error'){

          return $this->errorResponse($res['message'],Response::HTTP_OK);

        }else{
          return $this->successResponse($res,"Seat Block Added",Response::HTTP_OK);
        }

        

      }
      catch (Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
     

     }
     public function updateseatblock(Request $request, $id)
     {

      // Log::info($request); exit;
        $seatblock = $this->seatblockService->updateseatblock($request, $id);
            return $this->successResponse($seatblock,"Seat Block Updated",Response::HTTP_OK);

     }


      public function getseatblockDT(Request $request) {      
        
        $seatblock = $this->seatblockService->getseatblockDT($request);
        return $this->successResponse($seatblock,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
        
      } 

      public function seatblockData(Request $request) {      
        
        $seatblock = $this->seatblockService->seatblockData($request);
        return $this->successResponse($seatblock,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
        
      }

      public function changeStatus ($id) {
    
        try{
          $this->seatblockService->changeStatus($id);
        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, "Seat Block Status Updated", Response::HTTP_ACCEPTED);
      }

      public function deleteseatblock (Request $request) {
      try{
        $this->seatblockService->deleteById($request);
      }
      catch (Exception $e){
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(null, "Seat Block Deleted", Response::HTTP_ACCEPTED);
    }

}