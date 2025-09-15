<?php
namespace App\Http\Controllers;

use App\Models\BusOwnerFare;  
use App\Repositories\AgentNotificationRepository;
use Illuminate\Http\Request;
use App\Models\Bus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Services\AgentNotificationService;
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
    protected $agentnotificationRepository;

    
    public function __construct(AgentNotificationService $agentnotificationService,
                                AgentNotificationValidator $agentnotificationValidator,
                                AgentNotificationRepository $agentnotificationRepository)
    {
        $this->agentnotificationService = $agentnotificationService;
        $this->agentnotificationValidator = $agentnotificationValidator;
        $this->agentnotificationRepository = $agentnotificationRepository;
    }   
  

    // public function getData(Request $request) 
    // {      
    //     $data = $this->agentnotificationService->getData($request);
    //     return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }

    public function getData(Request $request) 
{      
    
    $paginate = $request['rows_number'];
    $name = $request['name'];
    $user_id = $request['user_id'];

    $start_date = "";
    $end_date = "";
    $rangeFromDate = $request->rangeFromDate;
    $rangeToDate = $request->rangeToDate;

    if (!empty($rangeFromDate)) {
        if (strlen($rangeFromDate['month']) == 1) {
            $rangeFromDate['month'] = "0" . $rangeFromDate['month'];
        }
        if (strlen($rangeFromDate['day']) == 1) {
            $rangeFromDate['day'] = "0" . $rangeFromDate['day'];
        }
        $start_date = $rangeFromDate['year'].'-'.$rangeFromDate['month'].'-'.$rangeFromDate['day'];
    }

    if (!empty($rangeToDate)) {
        if (strlen($rangeToDate['month']) == 1) {
            $rangeToDate['month'] = "0" . $rangeToDate['month'];
        }
        if (strlen($rangeToDate['day']) == 1) {
            $rangeToDate['day'] = "0" . $rangeToDate['day'];
        }
        $end_date = $rangeToDate['year'].'-'.$rangeToDate['month'].'-'.$rangeToDate['day'];
    }

    
    $data = $this->agentNotificationRepository->getallnotification($user_id);

    
    if ($paginate == 'all') {
        $paginate = Config::get('constants.ALL_RECORDS');
    } elseif ($paginate == null) {
        $paginate = 10;
    }

    
    if ($name != null) {
        $data = $this->agentNotificationRepository->Filter($data, $name);
    }

 
    if ($start_date != null && $end_date != null) {
        $data = $this->agentNotificationRepository->dateFilter($data, $start_date, $end_date);
    }

   
    $data = $this->agentNotificationRepository->Pagination($data, $paginate);


    $response = [
        "count" => $data->count(),
        "total" => $data->total(),
        "data" => $data
    ];

    return $this->successResponse($response, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
}

    



    //  public function allPushNotification(Request $request) 
    // {     
    //     $data = $this->agentnotificationService->allPushNotification($request);
    //     return $this->successResponse($data,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }

    public function allPushNotification(Request $request) 
{     
    try {
        
        $data = $this->agentNotificationRepository->allPushNotification($request);
    } catch (Exception $e) {
        
         Log::info($e->getMessage());
        throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
    }
    
    return $this->successResponse($data, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
}


    //  public function addNotification(Request $request) 
    //  {
    //     $data = $request->only(['subject','notification','user_id']);
    //     $agentnotificationValidator = $this->agentnotificationValidator->validate($data);

    //     if ($agentnotificationValidator->fails()) 
    //     {
    //         $errors = $agentnotificationValidator->errors();
            
    //         return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
    //     }
    //     try {
    //        $result=$this->agentnotificationService->savePostData($data);
    //        return $this->successResponse($result,"Notification Added",Response::HTTP_CREATED);
    //     } 
    //     catch (Exception $e) {
    //        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
    //     }
         
    // }

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
           $result=$this->agentNotificationRepository->save($data);
           return $this->successResponse($result,"Notification Added",Response::HTTP_CREATED);
        } 
        catch (Exception $e) {
           return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
         
    }


   

    // public function deleteNotification($id) 
    // {
    //   try{
    //     $this->agentnotificationService->deleteById($id);
    //   }
    //   catch (Exception $e){
    //     return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
    //   }
    //   return $this->successResponse(null, "Notification Deleted", Response::HTTP_ACCEPTED);
    // }

    
    
    public function deleteNotification($id) 
    {
      try{
        $this->agentNotificationRepository->delete($id);
      }
      catch (Exception $e){
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(null, "Notification Deleted", Response::HTTP_ACCEPTED);
    }

    // public function getNotification(Request $request)
    // {

    // }

    
}
