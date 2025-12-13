<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Services\AgentNotificationService;
use App\Repositories\AgentNotificationRepository;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;
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
    protected $agentNotificationRepository;


    public function __construct(
        AgentNotificationService $agentnotificationService,
        AgentNotificationValidator $agentnotificationValidator,
        AgentNotificationRepository $agentNotificationRepository
    ){
        $this->agentnotificationService = $agentnotificationService;
        $this->agentnotificationValidator = $agentnotificationValidator;
        $this->agentNotificationRepository = $agentNotificationRepository;
    }


    public function getData(Request $request){
        try {
            $data = $this->agentnotificationService->getData($request);
            return $this->successResponse($data, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        
    }


    public function allPushNotification(Request $request)
    {
        try {
            $data = $this->agentNotificationRepository->allPushNotification($request);
            return $this->successResponse($data, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        
    }

    public function addNotification(Request $request)
    {
        $data = $request->only(['subject','notification','user_id']);
        $agentnotificationValidator = $this->agentnotificationValidator->validate($data);

        if ($agentnotificationValidator->fails()) {
            $errors = $agentnotificationValidator->errors();

            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        DB::beginTransaction();
        try {
            $result = $this->agentNotificationRepository->save($data);
            DB::commit();
            return $this->successResponse($result, "Notification Added", Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }

    }



    public function deleteNotification($id)
    {
        DB::beginTransaction();
        try {
            $this->agentNotificationRepository->delete($id);

            DB::commit();
            return $this->successResponse(null, "Notification Deleted", Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }
}
