<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\ExtraSeatBlockService;
use App\Repositories\ExtraSeatBlockRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
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
    protected $extraSeatBlockRepository;   
    
    public function __construct(ExtraSeatBlockService $extraseatblockService, 
                                ExtraSeatBlockRepository $extraSeatBlockRepository)
    {
        $this->extraseatblockService = $extraseatblockService;
        $this->extraseatblockRepository = $extraSeatBlockRepository;
        
    }

    public function getAllseatblock()
    {
        $seatblock = $this->extraseatblockService->getAll();
        return $this->successResponse($seatblock,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    //  public function addExtraSeatBlock(Request $request)
    //  {

    //   try{
    //     $res = $this->extraseatblockService->addExtraSeatBlock($request);

    //     if(isset($res['status']) && $res['status'] == 'error'){

    //       return $this->errorResponse($res['message'],Response::HTTP_OK);

    //     }else{
    //       return $this->successResponse($res,"Extra Seat Block Added",Response::HTTP_OK);
    //     }

    //   }
    //   catch (Exception $e){
    //       //Log::info($e->getMessage());
    //       return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
    //   }      

    //  }  

     public function addExtraSeatBlock(Request $request)
     {

      try{
        $res = $this->extraSeatBlockRepository->addExtraSeatBlock($request);

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

    //  public function addExtraSeatBlockByOperator(Request $request)
    //  {
    //   try{
    //     $res = $this->extraseatblockService->addExtraSeatBlockByOperator($request);
    //     if(isset($res['status']) && $res['status'] == 'error'){
    //       return $this->errorResponse($res['message'],Response::HTTP_OK);
    //     }else{
    //       return $this->successResponse($res,"Extra Seat Block Added",Response::HTTP_OK);
    //     }
    //   }
    //   catch (Exception $e){
    //       return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
    //   }      

    //  }     

     public function addExtraSeatBlockByOperator(Request $request)
     {
      try{
        $res = $this->extraSeatBlockRepository->addExtraSeatBlockByOperator($request);
        if(isset($res['status']) && $res['status'] == 'error'){
          return $this->errorResponse($res['message'],Response::HTTP_OK);
        }else{
          return $this->successResponse($res,"Extra Seat Block Added",Response::HTTP_OK);
        }
      }
      catch (Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }      

     }     

      // public function extraSeatBlockData(Request $request) {      
        
      //   $seatblock = $this->extraseatblockService->extraSeatBlockData($request);
      //   return $this->successResponse($seatblock,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
        
      // }

       public function extraSeatBlockData(Request $request) {      
        
        $seatblock = $this->extraSeatBlockRepository->extraSeatBlockData($request);
        return $this->successResponse($seatblock,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
        
      }

    

    //   public function deleteExtraSeatBlock (Request $request) {
    //   try{
    //     $this->extraseatblockService->deleteExtraSeatBlock($request);
    //   }
    //   catch (Exception $e){
    //     return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
    //   }
    //   return $this->successResponse(null, "Seat Block Deleted", Response::HTTP_ACCEPTED);
    // }


    public function deleteExtraSeatBlock(Request $request)
{
    try {
        
        $seatblock = $this->extraSeatBlockRepository->deleteExtraSeatBlock($request);
    } catch (Exception $e) {
        
        Log::error("Error deleting extra seat block: " . $e->getMessage());

        
        return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
    }

   
    return $this->successResponse(null, "Seat Block Deleted", Response::HTTP_ACCEPTED);
}


}