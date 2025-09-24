<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\AssociationAssignAgentService;
use App\Repositories\AssociationAssignAgentRepository;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\AppValidator\AssociationAssignBusValidator;



class AssociationAssigAgentController extends Controller
{
    use ApiResponser;
   
    protected $AssociationAssignAgentService;
    protected $AssociationAssignBusValidator; 
    protected $AssociationAssignAgentRepository;
    
    
    public function __construct(AssociationAssignAgentService $AssociationAssignAgentService,
                               AssociationAssignBusValidator $AssociationAssignBusValidator,
                               AssociationAssignAgentRepository $AssociationAssignAgentRepository)
    {
        $this->AssociationAssignAgentService = $AssociationAssignAgentService;
        $this->AssociationAssignBusValidator = $AssociationAssignBusValidator;                
    }


     public function getAssocBuslist(Request $request)
     {
          //return $this->AssociationAssignAgentService->getAssocBuslist($request);
          return $this->AssociationAssignAgentRepository->getAssocBuslist($request);
            
	 }  

	 public function getassocAssignAgent(Request $request)
     {
          //return $this->AssociationAssignAgentService->getassocAssignAgent($request);
          return $this->AssociationAssignAgentRepository->getassocAssignAgent($request);
            
	 }   

	  public function deleteassocAssignAgent(Request $request)
     {
          //$response= $this->AssociationAssignAgentService->deleteassocAssignAgent($request);
          return $this->successResponse($response,"Delete Successful", Response::HTTP_CREATED);  
	 }   

	  public function assocAssignAgent(Request $request)
     {
          //$response = $this->AssociationAssignAgentService->assocAssignAgent($request);
          $response = $this->AssociationAssignAgentRepository->deleteassocAssignAgent($request);
            return $this->successResponse($response,"Agent assigned to the Association", Response::HTTP_CREATED);   
	 }     

}