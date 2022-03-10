<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\OprAssignBusService;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\AppValidator\OprAssignBusValidator;

class OprAssigBusController extends Controller
{
    use ApiResponser;
   
    protected $OprAssignBusService;
    protected $OprAssignBusValidator;   
    
    
    public function __construct(OprAssignBusService $OprAssignBusService, OprAssignBusValidator $OprAssignBusValidator)
    {
        $this->OprAssignBusService = $OprAssignBusService;
        $this->OprAssignBusValidator = $OprAssignBusValidator;                
    }


     public function getOprBuslist(Request $request)
     {
          return $this->OprAssignBusService->getOprBuslist($request);
            
	 }  

	 public function getOprAssignBus(Request $request)
     {
          return $this->OprAssignBusService->getOprAssignBus($request);
            
	 }   

	 public function deleteOprAssignBus(Request $request)
     {
          $response= $this->OprAssignBusService->deleteOprAssignBus($request);
          return $this->successResponse($response,"Delete Successful", Response::HTTP_CREATED);  
	 }   

	 public function OprAssignBus(Request $request)
     {
          log::info($request);
          $response = $this->OprAssignBusService->OprAssignBus($request);
          return $this->successResponse($response,"Bus assigned to the Association", Response::HTTP_CREATED);   
	 } 
}