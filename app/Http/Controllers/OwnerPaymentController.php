<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Services\OwnerPaymentService;
use App\Traits\ApiResponser;
use Exception;
use InvalidArgumentException;
use App\AppValidator\OwnerPaymentValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class OwnerPaymentController extends Controller
{
    use ApiResponser;
    protected $OwnerPaymentService;
    protected $OwnerPaymentValidator;
    
    public function __construct(OwnerPaymentService $ownerPaymentService, OwnerPaymentValidator $ownerPaymentValidator)
    {
        $this->ownerPaymentService = $ownerPaymentService;
        $this->ownerPaymentValidator = $ownerPaymentValidator;
    }

    public function getAllOwnerPayment() 
    {
        $ownerpayment = $this->ownerPaymentService->getAll();
        return $this->successResponse($ownerpayment,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function getOwnerPaymentDT(Request $request) 
    {      
        $ownerpayment = $this->ownerPaymentService->dataTable($request);
        return $this->successResponse($ownerpayment,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function createOwnerPayment(Request $request) 
    {
        // Log::info($request);
        
        $data = $request->only(['bus_operator_id','date','transaction_id','amount','remark','created_by']);

        $ownerPaymentValidator = $this->ownerPaymentValidator->validate($data);
        if ($ownerPaymentValidator->fails()) {
            $errors = $ownerPaymentValidator->errors();
            
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
          }
        try {
           $this->ownerPaymentService->savePostData($request);

        } catch (Exception $e) {
           return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($data,Config::get('constants.RECORD_ADDED'),Response::HTTP_CREATED); 
    }  
	     
}
