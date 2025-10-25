<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\AssociationAssignBusService;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\AppValidator\AssociationAssignBusValidator;



class AssociationAssigBusController extends Controller
{
    use ApiResponser;
   
    protected $AssociationAssignBusService;
    protected $AssociationAssignBusValidator;   
    
    
    public function __construct(AssociationAssignBusService $AssociationAssignBusService, AssociationAssignBusValidator $AssociationAssignBusValidator)
    {
        $this->AssociationAssignBusService = $AssociationAssignBusService;
        $this->AssociationAssignBusValidator = $AssociationAssignBusValidator;                
    }


     public function getAssocBuslist(Request $request)
     {
          return $this->AssociationAssignBusService->getAssocBuslist($request);
            
	 }  

	 public function getassocAssignBus(Request $request)
     {
          return $this->AssociationAssignBusService->getassocAssignBus($request);
            
	 }   

	  public function deleteassocAssignBus(Request $request)
     {
          $response= $this->AssociationAssignBusService->deleteassocAssignBus($request);
          return $this->successResponse($response,"Delete Successful", Response::HTTP_CREATED);  
	 }   

	  public function assocAssignBus(Request $request)
     {
          $response = $this->AssociationAssignBusService->assocAssignBus($request);
            return $this->successResponse($response,"Bus assigned to the Association", Response::HTTP_CREATED);   
	 }     

}