<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use Exception;
use InvalidArgumentException;
use App\AppValidator\CancellationSlabValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use App\Repositories\CancellationSlabRepository;


class CancellationSlabController extends Controller
{
    use ApiResponser;
    protected $cancellationSlabService;
    protected $cancellationSlabValidator;
    protected $cancellationSlabRepository;

    public function __construct(CancellationSlabRepository $cancellationSlabRepository, CancellationSlabValidator $cancellationSlabValidator)
    {
  
        $this->CancellationSlabValidator = $cancellationSlabValidator;
        $this->cancellationSlabRepository = $cancellationSlabRepository;
    }

    public function getAllCancellationSlab(Request $request)
    {
      
        $cSlab = $this->cancellationSlabRepository->getAll($request);
        return $this->successResponse($cSlab, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }
    public function cancellationslabsUserData(Request $request)
    {
      
        $cSlab = $this->cancellationSlabRepository->cancellationslabsUserData($request);
        return $this->successResponse($cSlab, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }
    public function cancellationslabsOperator(Request $request)
    {
        
        $cSlab = $this->cancellationSlabRepository->cancellationslabsOperator($request);
        return $this->successResponse($cSlab, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getCancellationSlabDT(Request $request)
    {
       
        $cSlab = $this->cancellationSlabRepository->getCancellationSlabDT($request);
        return $this->successResponse($cSlab, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }
    public function cancellationslabData(Request $request)
    {
        $cSlab = $this->cancellationSlabRepository->cancellationslabData($request);
        return $this->successResponse($cSlab, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function deleteCancellationSlab($id)
    {
        try {
            $response = $this->cancellationSlabRepository->delete($id);
            return $this->successResponse($response, "Cancellation Slab Deleted", Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function getCancellationSlab($id)
    {
        try {
            $cSlabID = $this->cancellationSlabRepository->getById($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($cSlabID, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function createCancellationSlab(Request $request)
    {
        $data = $request->only(['rule_name','slabs','cancellation_policy_desc','created_by','user_id'
        ]);

        $cSlabValidate = $this->CancellationSlabValidator->validate($data);

        if ($cSlabValidate->fails()) {
            $errors = $cSlabValidate->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }
        try {

            $response = $this->cancellationSlabRepository->save($data);
            if ($response  == 'SLAB_EXIST') {
                return $this->errorResponse("Cancellation Slab is already exist for this user", Response::HTTP_PARTIAL_CONTENT);
            } else {
                return $this->successResponse($response, "Cancellation Slab Added", Response::HTTP_CREATED);
            }

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }
    public function updateCancellationSlab(Request $request, $id)
    {
        $data = $request->only([
          'bus_operator_id', 'rule_name', 'slabs','cancellation_policy_desc','created_by','user_id'
        ]);
        $cSlabValidate = $this->CancellationSlabValidator->validate($data);
        if ($cSlabValidate->fails()) {
            $errors = $cSlabValidate->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }
        try {
            $response = $this->cancellationSlabRepository->update($data, $id);

            if ($response  == 'SLAB_EXIST') {
                return $this->errorResponse("Cancellation Slab is already exist for this user", Response::HTTP_PARTIAL_CONTENT);
            } else {
                return $this->successResponse($response, "Cancellation Slab Updated", Response::HTTP_CREATED);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function changeStatus($id)
    {
        try {
            $response = $this->cancellationSlabRepository->changeStatus($id);
            return $this->successResponse($response, "Cancellation Slab Status Updated", Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }
}
