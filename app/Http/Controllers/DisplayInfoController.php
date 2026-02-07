<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusDisplayInfo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use InvalidArgumentException;
use Exception;
use App\Repositories\DisplayInfoRepository;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class DisplayInfoController extends Controller
{
    use ApiResponser;

     protected $DisplayInfoRepository;

    public function __construct(
    DisplayInfoRepository $DisplayInfoRepository)
    {
        $this->DisplayInfoRepository = $DisplayInfoRepository;
    }


    public function getAllDisplayInfoData()
    {        
        
        $display_info = $this->DisplayInfoRepository->getAll();
        return $this->successResponse($display_info, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

   
    public function createDisplayInfo(Request $request)
    {
        $data = $request->only(['name']);

        try {
            $data = $this->DisplayInfoRepository->save($data);
            return $this->successResponse($data, "Display Info Added", Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }

    }

    public function updateDisplayInfo(Request $request, $id)
    {
        $data = $request->only([
          'name'
        ]);

        try {
          
            $this->DisplayInfoRepository->update($data, $id);
            return $this->successResponse(null, "Display Info Updated", Response::HTTP_CREATED);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }

    }

    public function deleteDisplayInfo($id)
    {

        try {
            
            $this->DisplayInfoRepository->delete($id);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, "Display Info Deleted", Response::HTTP_ACCEPTED);

    }

    public function getDisplayInfo($id)
    {
        try {
            
            $id = $this->DisplayInfoRepository->getById($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse($id, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);

    }
   
}
