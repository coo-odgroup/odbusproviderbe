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
use App\Repositories\VendorSeatBlockRepository;

class VendorSeatBlockController extends Controller
{
    use ApiResponser;


    protected $vendorseatblockRepository;


    public function __construct(VendorSeatBlockRepository $vendorseatblockRepository) {
        $this->vendorseatblockRepository = $vendorseatblockRepository;
    }



    public function vendorSeatblockData(Request $request)
    {
        $seatblock = $this->vendorseatblockRepository->seatblockData($request);
        return $this->successResponse($seatblock, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function vendorAddseatblock(Request $request)
    {
        try {
            $res = $this->vendorseatblockRepository->addseatblock($request);

            if (isset($res['status']) && $res['status'] == 'error') {

                return $this->errorResponse($res['message'], Response::HTTP_OK);
            } else {
                return $this->successResponse($res, "Seat Block Added", Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }


    public function updateseatblock(Request $request, $id)
    {
        try {
            $seatblock = $this->vendorseatblockRepository->updateseatblock($request, $id);
        } catch (Exception $e) {
            return $this->errorResponse(
                Config::get('constants.RECORD_NOT_FOUND'),
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->successResponse($seatblock, "Seat Block Updated", Response::HTTP_OK);
    }

    public function editseatblock(Request $request)
    {


        $seatblock = $this->vendorseatblockRepository->editseatblock($request);
        return $this->successResponse($seatblock, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function updateSeatBlockData(Request $request)
    {

        $seatblock = $this->vendorseatblockRepository->updateSeatBlockData($request);
        if (isset($seatblock['status']) && $seatblock['status'] == 'error') {
            return $this->errorResponse($seatblock['message'], Response::HTTP_OK);
        } else {
            return $this->successResponse($seatblock, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        };
    }

    public function alreadyBlocks(Request $request)
    {
        $seatblock = $this->vendorseatblockRepository->alreadyBlocks($request);
        return $this->successResponse($seatblock, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function changeStatus($id)
    {
        try {
            $this->vendorseatblockRepository->changeStatus($id);
            return $this->successResponse(null, "Seat Block Status Updated", Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function deleteseatblock(Request $request)
    {
        try {
            $this->vendorseatblockRepository->delete($request);
            return $this->successResponse(null, "Seat Block Deleted", Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }
}
