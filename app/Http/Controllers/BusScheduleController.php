<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusSchedule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use App\Services\BusScheduleService;
use App\AppValidator\BusScheduleValidator;
use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class BusScheduleController extends Controller
{
    use ApiResponser;
    protected $busScheduleService;
    protected $busScheduleValidator;
    
    public function __construct(BusScheduleService $busScheduleService, BusScheduleValidator $busScheduleValidator)
    {
        $this->busScheduleService = $busScheduleService;
        $this->busScheduleValidator = $busScheduleValidator;
    }
    public function getAllBusSchedule(Request $request) {
        $busSchedule = $this->busScheduleService->getAll();
        return $this->successResponse($busSchedule,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function scheduleCronJob() {
        $busSchedule = $this->busScheduleService->scheduleCronJob();
        return $this->successResponse($busSchedule,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function getAllBusScheduleDT(Request $request) {
        $busSchedule = $this->busScheduleService->dataTable($request);
        return $this->successResponse($busSchedule,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 

     public function busScheduleById($id)
    {
        return $this->busScheduleService->busScheduleById($id);
    }   


    public function busSchedulerData(Request $request) {
        $busSchedule = $this->busScheduleService->busSchedulerData($request);
        return $this->successResponse($busSchedule,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function createBusSchedule(Request $request) {
        $data = $request->only([
            'bus_id','created_by','running_cycle', 'entry_date'  
        ]);
        $busScheduleValidation = $this->busScheduleValidator->validate($data);
        if ($busScheduleValidation->fails()) {
            $errors = $busScheduleValidation->errors();
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        else
        {
           $response = $this->busScheduleService->savePostData($data);

           if($response=='Bus Schedule Already Exist')
           {
              return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
           }
           else if($response == 'Can Not Add Old Date')
           {
              return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
           }
           else
           {
               return $this->successResponse($response,"Bus Schedule Added", Response::HTTP_CREATED);
           }
        }
        // try {
        //     $response = $this->busScheduleService->savePostData($data);
        //     return $this->successResponse($response,"Bus Schedule Added", Response::HTTP_CREATED);
        // } catch (Exception $e) {
        //     return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
        // }
    } 

    public function updateBusSchedule(Request $request, $id) {
        $data = $request->only([
            'bus_id','entry_date','created_by','running_cycle'   
        ]);

        $response = $this->busScheduleService->updatePost($data, $id);

           if($response == 'Can Not Add Old Date')
           {
              return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
           }
           else
           {
               return $this->successResponse($response,"Bus Schedule Updated", Response::HTTP_CREATED);
           }

        
        // try {
        //     $response = $this->busScheduleService->updatePost($data, $id);
        //     return $this->successResponse($response,"Bus Schedule Updated", Response::HTTP_CREATED);

        // } catch (Exception $e) {
        //     return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
        // }
    }

    public function deleteBusSchedule ($id) {
        try {
            $response = $this->busScheduleService->deleteById($id);
            return $this->successResponse($response,"Bus Schedule Deleted", Response::HTTP_ACCEPTED);
        }
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
        }    
    }

    public function getBusSchedule($id) {
      try {
        $busschedule= $this->busScheduleService->getById($id);
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
      return $this->successResponse($busschedule, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }  
    public function changeStatus ($id) {
        try{
            $response = $this->busScheduleService->changeStatus($id);
        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($response,"Bus Schedule Status Updated", Response::HTTP_ACCEPTED);
      }

	     
    public function unscheduledbuslist()
    {
        try{
            $response = $this->busScheduleService->unscheduledbuslist();
            return $this->successResponse($response,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
    }
}
