<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Services\AgentNotificationService;
use App\Traits\ApiResponser;
use Exception;
use App\AppValidator\AgentNotificationValidator;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class AgentNotificationController extends Controller
{
    use ApiResponser;
    protected $agentnotificationService;

    
    public function __construct(AgentNotificationService $agentnotificationService, AgentNotificationValidator $agentnotificationValidator)
    {
        $this->agentnotificationService = $agentnotificationService;
    }   
  

    public function getData(Request $request) 
    {      
        $wallet = $this->agentnotificationService->getData($request);
        return $this->successResponse($wallet,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

     public function addNotification(Request $request) 
    {   
        $data = $request->only(['subject','notification','user_id']);

        $agentnotificationValidator = $this->agentnotificationValidator->validate($data);

        if ($agentnotificationValidator->fails()) 
        {
            $errors = $agentnotificationValidator->errors();
            
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
           $this->agentnotificationService->savePostData($request);
           return $this->successResponse($data,"Notification Added",Response::HTTP_CREATED);
        } catch (Exception $e) {
           return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
         
    }

    
}
