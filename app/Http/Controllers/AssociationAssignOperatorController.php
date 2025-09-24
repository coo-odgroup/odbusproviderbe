<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AssociationAssignOperatorService;
use App\Repositories\AssociationAssignOperatorRepository;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\AppValidator\AssociationAssignOperatorValidator;



class AssociationAssignOperatorController extends Controller
{
    use ApiResponser;
   
    protected $AssociationAssignOperatorService;
    protected $AssociationAssignOperatorValidator; 
     protected $AssociationAssignOperatorRepository;  
    
    
    public function __construct(AssociationAssignOperatorService $AssociationAssignOperatorService,
                             AssociationAssignOperatorValidator $AssociationAssignOperatorValidator,
                             AssociationAssignOperatorRepository $AssociationAssignOperatorRepository)
    {
        $this->AssociationAssignOperatorService = $AssociationAssignOperatorService;
        $this->AssociationAssignOperatorValidator = $AssociationAssignOperatorValidator;    
        $this->AssociationAssignOperatorRepository = $AssociationAssignOperatorRepository;            
    }


     public function getAllAssignOperator(Request $request)
     {
          //return $this->AssociationAssignOperatorService->getAllAssignOperator($request);
         return $this->AssociationAssignOperatorRepository->getAllAssignOperator($request);
            
	 }   

	  public function deleteassocAssignOperator(Request $request)
     {
         // $response= $this->AssociationAssignOperatorService->deleteassocAssignOperator($request);
          $response = $this->AssociationAssignOperatorRepository->deleteassocAssignOperator($request);
          return $this->successResponse($response,"Delete Successful", Response::HTTP_CREATED);  
	 }   

	  public function addAssignOperator(Request $request)
     {
          //$response = $this->AssociationAssignOperatorService->addAssignOperator($request);
          $response = $this->AssociationAssignOperatorRepository->addAssignOperator($request);
            return $this->successResponse($response,"Operators assigned to the Association", Response::HTTP_CREATED);   
	 }     

}