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
    protected $agentnotificationValidator;

    
    public function __construct(AgentNotificationService $agentnotificationService, AgentNotificationValidator $agentnotificationValidator)
    {
        $this->agentnotificationService = $agentnotificationService;
        $this->agentnotificationValidator = $agentnotificationValidator;
    }   
  

    public function getData(Request $request) 
    {      
        // Log::info($request);exit;
        $data = $this->agentnotificationService->getData($request);
        return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }


     public function allPushNotification(Request $request) 
    {     
        $data = $this->agentnotificationService->allPushNotification($request);
        return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
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
           $result=$this->agentnotificationService->savePostData($data);
           return $this->successResponse($result,"Notification Added",Response::HTTP_CREATED);
        } 
        catch (Exception $e) {
           return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
         
    }

   

    public function deleteNotification($id) 
    {
      try{
        $this->agentnotificationService->deleteById($id);
      }
      catch (Exception $e){
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(null, "Notification Deleted", Response::HTTP_ACCEPTED);
    }

    public function getNotification(Request $request)
    {

    }

    
}
