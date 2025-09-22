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
use App\Repositories\OwnerPaymentRepository;

use Illuminate\Support\Facades\Log;

class OwnerPaymentController extends Controller
{
    use ApiResponser;
    protected $OwnerPaymentService;
    protected $OwnerPaymentValidator;
    protected $ownerPaymentRepository;
    
    public function __construct(OwnerPaymentService $ownerPaymentService, 
                                OwnerPaymentValidator $ownerPaymentValidator,
                                OwnerPaymentRepository $ownerPaymentRepository)
    {
        $this->ownerPaymentService = $ownerPaymentService;
        $this->ownerPaymentValidator = $ownerPaymentValidator;
                $this->ownerPaymentRepository = $ownerPaymentRepository;


    }

    public function getAllOwnerPayment() 
    {
        //$ownerpayment = $this->ownerPaymentService->getAll();
        $ownerpayment = $this->ownerPaymentRepository->getAll();
        return $this->successResponse($ownerpayment,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function getOwnerPaymentDT(Request $request) 
    {      
       // $ownerpayment = $this->ownerPaymentService->dataTable($request);
          $ownerpayment = $this->ownerPaymentRepository->getDatatable($request);
        return $this->successResponse($ownerpayment,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function getPaymentDetails(Request $request) 
    {      
        //$ownerpayment = $this->ownerPaymentService->getPaymentDetails($request);
        $ownerpayment = $this->ownerPaymentRepository->getPaymentDetails($request);
        return $this->successResponse($ownerpayment,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 
    public function ownerpaymentData(Request $request) 
    {      
        //$ownerpayment = $this->ownerPaymentService->ownerpaymentData($request);
         $ownerpayment = $this->ownerPaymentRepository->ownerpaymentData($request);
        return $this->successResponse($ownerpayment,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function createOwnerPayment(Request $request) 
    {
        // Log::info($request);
        
        $data = $request->only(['bus_operator_id','startDate','endDate','noSeat','noPnr','transaction_id','amount','remark','paymentNote','created_by']);

        $ownerPaymentValidator = $this->ownerPaymentValidator->validate($data);
        if ($ownerPaymentValidator->fails()) {
            $errors = $ownerPaymentValidator->errors();
            
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
          }
        try {
           //$this->ownerPaymentService->savePostData($request);
           $this->ownerPaymentRepository->save($data);
           return $this->successResponse($data,"Owner Payment Added",Response::HTTP_CREATED);
        } catch (Exception $e) {
           return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
         
    }  
	     
}
