<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OprAssignAgentService;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\AppValidator\OprAssignAgentValidator;

class OprAssignAgentController extends Controller
{
    use ApiResponser;
   
    protected $oprAssignAgentService;
    protected $oprAssignAgentValidator;      
    
    public function __construct(oprAssignAgentService $oprAssignAgentService, OprAssignAgentValidator $oprAssignAgentValidator)
    {
        $this->oprAssignAgentService = $oprAssignAgentService;
        $this->oprAssignAgentValidator = $oprAssignAgentValidator;                
    }

    public function getAllAssignAgent(Request $request)
    {
        return $this->oprAssignAgentService->getAllAssignAgent($request);            
	}   

	public function deleteOprAssignAgent(Request $request)
    {
         $response= $this->oprAssignAgentService->deleteOprAssignAgent($request);
         return $this->successResponse($response,"Delete Successful", Response::HTTP_CREATED);  
	}   

	public function addAssignAgent(Request $request)
    {
         //log::info($request);
         $response = $this->oprAssignAgentService->addAssignAgent($request);
         return $this->successResponse($response,"Agents assigned to the Operator", Response::HTTP_CREATED);   
	}     

}