<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OprAssignOperatorService;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\AppValidator\OprAssignOperatorValidator;

class OprAssignOperatorController extends Controller
{
    use ApiResponser;
   
    protected $OprAssignOperatorService;
    protected $OprAssignOperatorValidator;      
    
    public function __construct(OprAssignOperatorService $OprAssignOperatorService, OprAssignOperatorValidator $OprAssignOperatorValidator)
    {
        $this->OprAssignOperatorService = $OprAssignOperatorService;
        $this->OprAssignOperatorValidator = $OprAssignOperatorValidator;                
    }

    public function getAllAssignOperator(Request $request)
    {
        return $this->OprAssignOperatorService->getAllAssignOperator($request);            
	}   

	public function deleteOprAssignOperator(Request $request)
    {
         $response= $this->OprAssignOperatorService->deleteOprAssignOperator($request);
         return $this->successResponse($response,"Delete Successful", Response::HTTP_CREATED);  
	}   

	public function addAssignOperator(Request $request)
    {
         $response = $this->OprAssignOperatorService->addAssignOperator($request);
         return $this->successResponse($response,"Operators assigned to the Operator", Response::HTTP_CREATED);   
	}     

}