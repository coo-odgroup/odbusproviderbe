<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusStoppage;
use App\Services\BusStoppageService;
use App\Services\BusStoppageTimingService;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use App\AppValidator\BusStoppageValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class BusStoppageController extends Controller
{
    use ApiResponser;
    protected $busStoppageService;
    protected $BusStoppageValidator;
    protected $BusStoppageTimingService;
    
    public function __construct(BusStoppageService $busStoppageService, BusStoppageValidator $BusStoppageValidator, BusStoppageTimingService $BusStoppageTimingService)
    {
        $this->busStoppageService = $busStoppageService;
        $this->BusStoppageValidator = $BusStoppageValidator;
        $this->BusStoppageTimingService = $BusStoppageTimingService;
    }


    public function getAllBusStoppage() {

        $busStoppage = $this->busStoppageService->getAll();
        return $this->successResponse($busStoppage,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function createBusStoppage(Request $request) {
        $data = $request->only([
            'bus_id', 'user_id','source_id','destination_id','base_seat_fare','base_sleeper_fare',
            'dep_time','arr_time','j_day','created_by'    
        ]);
        $busStoppageValidation = $this->BusStoppageValidator->validate($data);
        if ($busStoppageValidation->fails()) {
            $errors = $busStoppageValidation->errors();
            return $this->errorResponse($errors,Response::HTTP_PARTIAL_CONTENT);
        }
        try {
            $this->busStoppageService->savePostData($data);
        }
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
      return $this->successResponse(null, Config::get('constants.RECORD_ADDED'), Response::HTTP_CREATED);
    }
    public function updateBusStoppage(Request $request, $id) {

        $data=$request;
        //Log::info($data);
        $busRoutesInfo=$data['busRoutesInfo'];
        $busRoutes=$data['busRoutes'];
        $this->BusStoppageTimingService->deleteByStoppageId($id);
        $this->busStoppageService->deletebyBusId($id);
        foreach($busRoutes as $routeKey=>$routeValue)
        {
            $timing_grp['bus_id']=$id;
            $timing_grp['location_id']=$routeValue['source_id'];

            $found_arrival=0;
            $depature_time="";
            
            foreach($routeValue['sourceBoarding'] as $destinations)
            {
                if($destinations['sourcechecked']=="true")
                {

                    if($found_arrival==0)
                    {
                        $location_arrival[$timing_grp['location_id']]['arr_time']=$destinations['sourceTime'];
                        $found_arrival++;
                    }

                    $timing_grp['stoppage_name']=$destinations['sourceLocation'];
                    $timing_grp['stoppage_time']=$destinations['sourceTime'];
                    $timing_grp['boarding_droping_id']=$destinations['boarding_droping_id'];
                    try {
                        $this->BusStoppageTimingService->savePostData($timing_grp);
                    } 
                    catch (Exception $e) {
                        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
                    }
                }
            }

            if( $timing_grp['location_id']!="")
            {
                $location_depature[$timing_grp['location_id']]['dep_time']=$depature_time;
            }
            
        }
        foreach($busRoutesInfo as $routeinfoKey=>$routeinfoVal)
        {
            $routeinfoData['bus_id']=$id; //get it from return id
            $routeinfoData['bus_operator_id']=$data['bus_operator_id'];
            $routeinfoData['source_id']=$routeinfoVal['from_location'];
            $routeinfoData['destination_id']=$routeinfoVal['to_location'];
            $routeinfoData['start_j_days']=$routeinfoVal['arr_days'];
            $routeinfoData['j_day']=$routeinfoVal['dep_days'];

            $routeinfoData['arr_time']=$location_arrival[$routeinfoVal['from_location']]['arr_time'];
            $routeinfoData['dep_time']=$location_depature[$routeinfoVal['to_location']]['dep_time'];

            $routeinfoData['user_id']="1";
            $routeinfoData['base_seat_fare']=$routeinfoVal['seater_fare'];
            $routeinfoData['base_sleeper_fare']=$routeinfoVal['sleeper_fare'];
            $stoppage_id=0;
            try {
                $stoppage_id=$this->busStoppageService->savePostData($routeinfoData);
            } 
            catch (Exception $e) {
                Log::info($e);
                return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
            }

            
            //ADD TO STOPPAGE THEN TO TIMING
            
        }
        

        
        return $this->successResponse($request, Config::get('constants.RECORD_UPDATED'), Response::HTTP_CREATED);
    }
    public function deleteBusStoppage ($id) {
      try {
          $this->busStoppageService->deleteById($id);
      }
      catch (Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(null, Config::get('constants.RECORD_REMOVED'), Response::HTTP_ACCEPTED);
    }

    public function getBusStoppage($id) {
      $busStoppage = $this->busStoppageService->getById($id);
      return $this->successResponse($busStoppage,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }      
	   
    public function getBusStoppagebyRoutes($source_id,$destination_id) {
        $busStoppage = $this->busStoppageService->getBusStoppagebyRoutes($source_id,$destination_id);
        return $this->successResponse($busStoppage,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function getBusStoppagebyBusId($busid) {
        $busStoppage = $this->busStoppageService->getBusStoppagebyBusId($busid);
        return $this->successResponse($busStoppage,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 
    
    public function getBusByOperator($operator_id) {
        $busStoppage = $this->busStoppageService->getBusByOperator($operator_id);
        return $this->successResponse($busStoppage,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
      }   
}
