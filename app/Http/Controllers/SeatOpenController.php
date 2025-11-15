<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\SeatOpenRepository;


class SeatOpenController extends Controller
{
    use ApiResponser;

   
     protected $seatopenRepository;



    public function __construct(SeatOpenRepository $seatopenRepository)
    {
     
        $this->seatopenRepository = $seatopenRepository;
    }

    public function getAllseatopen()
    {
     
        $seatopen = $this->seatopenRepository->getAll();
        return $this->successResponse($seatopen, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    

    public function addseatopen(Request $request)
    {
        try {
           
            $res = $this->seatopenRepository->addseatopen($request);

            if (isset($res['status']) && $res['status'] == 'error') {

                return $this->errorResponse($res['message'], Response::HTTP_OK);

            } else {
                return $this->successResponse($res, "Seat Open  Added", Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function addseatOpenByOperator(Request $request)
    {
        try {
          
            $res = $this->seatopenRepository->addseatOpenByOperator($request);

            if (isset($res['status']) && $res['status'] == 'error') {

                return $this->errorResponse($res['message'], Response::HTTP_OK);

            } else {
                return $this->successResponse($res, "Seat Open  Added", Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }


    

    public function editseatOpen(Request $request)
    {
      
        $seatopen = $this->seatopenRepository->editseatOpen($request);
        return $this->successResponse($seatopen, "Seat Open  Added", Response::HTTP_OK);

    }


    public function updateSeatOpenData(Request $request)
    {
  
        $seatopen = $this->seatopenRepository->updateSeatOpenData($request);
        return $this->successResponse($seatopen, "Seat Open  Added", Response::HTTP_OK);

    }
    public function updateseatopen(Request $request, $id)
    {

 
         $seatopen = $this->seatopenRepository->updateseatopen($request, $id);
        return $this->successResponse($seatopen, "Seat Open Updated", Response::HTTP_OK);

    }


    public function getseatopenDT(Request $request)
    {

       
        $seatopen = $this->seatopenRepository->getseatopenDT($request);
        return $this->successResponse($seatopen, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);

    }

    public function seatopenData(Request $request)
    {

        
        $seatopen = $this->seatopenRepository->seatopenData($request);
        return $this->successResponse($seatopen, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);

    }

    public function alreadyOpen(Request $request)
    {

        
        $seatopen = $this->seatopenRepository->alreadyOpen($request);
        return $this->successResponse($seatopen, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);

    }

    public function changeStatus($id)
    {

        try {
      
            $this->seatopenRepository->changeStatus($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, "Seat Open  Status Updated", Response::HTTP_ACCEPTED);
    }

    public function deleteseatopen(Request $request)
    {
        try {
            $this->seatopenRepository->delete($request);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, "Seat Open Deleted", Response::HTTP_ACCEPTED);
    }



}
