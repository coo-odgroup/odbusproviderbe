<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\ExtraSeatBlockService;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;


class ExtraSeatBlockController extends Controller
{
    use ApiResponser;
    protected $extraseatblockService;    
    
    public function __construct(ExtraSeatBlockService $extraseatblockService)
    {
        $this->extraseatblockService = $extraseatblockService;
        
    }

    public function getAllseatblock()
    {
        $seatblock = $this->extraseatblockService->getAll();
        return $this->successResponse($seatblock,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

     public function addExtraSeatBlock(Request $request)
     {

      try{
        $res = $this->extraseatblockService->addExtraSeatBlock($request);

        if(isset($res['status']) && $res['status'] == 'error'){

          return $this->errorResponse($res['message'],Response::HTTP_OK);

        }else{
          return $this->successResponse($res,"Extra Seat Block Added",Response::HTTP_OK);
        }

      }
      catch (Exception $e){
          //Log::info($e->getMessage());
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }      

     }     

      public function extraSeatBlockData(Request $request) {      
        
        $seatblock = $this->extraseatblockService->extraSeatBlockData($request);
        return $this->successResponse($seatblock,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
        
      }

    

      public function deleteExtraSeatBlock (Request $request) {
      try{
        $this->extraseatblockService->deleteExtraSeatBlock($request);
      }
      catch (Exception $e){
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(null, "Seat Block Deleted", Response::HTTP_ACCEPTED);
    }

}