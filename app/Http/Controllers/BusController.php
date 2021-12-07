<?php

namespace App\Http\Controllers;
use App\Models\Bus;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TicketCancelation;
use App\Models\BusType;
use App\Models\BusSitting;
use App\Models\BusSeatLayout;
use App\Services\BusService;
use App\Services\BusSafetyService;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Exception;
use App\AppValidator\BusValidator;
use App\AppValidator\BusSequenceValidator;
use Symfony\Component\HttpFoundation\Response;
use App\Services\BusAmenitiesService;
use App\Services\BusContactsService;
use App\Services\BookingSeizedService;
use App\Models\BusStoppage;
use App\Services\BusStoppageService;

use App\Services\BusLocationSequenceService;

use App\Models\BusStoppageTiming;
use App\Services\BusStoppageTimingService;


use App\Models\BusSeats;
use App\Services\BusSeatsService;
use Illuminate\Support\Facades\Log;
/*Priyadarshi Need to Review*/
class BusController extends Controller
{
    use ApiResponser;

    protected $busService;
    protected $busValidator;
    protected $BusSequenceValidator;
    protected $busAmenitiesService;
    protected $busContactsService;
    protected $BusStoppageService;
    protected $busLocationSequenceService;
    protected $BusStoppageTimingService;
    protected $busSeatsService;
    protected $busSafetyService;
    protected $bookingSeizedService;

    public function __construct(BusContactsService $busContactsService,BusAmenitiesService $busAmenitiesService,BusService $busService,BusValidator $busValidator, BusSequenceValidator $BusSequenceValidator, BusStoppageService $BusStoppageService, BusStoppageTimingService $BusStoppageTimingService, BusSeatsService $busSeatsService, BusSafetyService $busSafetyService, BookingSeizedService $bookingSeizedService,BusLocationSequenceService $busLocationSequenceService)
    {
        $this->busService = $busService;
        $this->busValidator = $busValidator;
        $this->BusSequenceValidator = $BusSequenceValidator;
        $this->busAmenitiesService = $busAmenitiesService;
        $this->busContactsService = $busContactsService;
        $this->BusStoppageService=$BusStoppageService;
        $this->BusStoppageTimingService=$BusStoppageTimingService;
        $this->busSeatsService=$busSeatsService;
        $this->busSafetyService=$busSafetyService;
        $this->bookingSeizedService=$bookingSeizedService;
        $this->busLocationSequenceService=$busLocationSequenceService;

        
    }
    public function seatsBus(Request $request) {
        

        $buses = $this->busService->seatsBus($request);
        return $this->successResponse($buses,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);  
    }

    public function getAll() {
        $buses = $this->busService->getAll();
        return $this->successResponse($buses,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);  
    }
    public function getByOperaor($id) {
        $buses = $this->busService->getByOperaor($id);
        return $this->successResponse($buses,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);  
    }

    public function getLocationBus($source_id,$destination_id)
    {
        $buses = $this->busService->getLocationBus($source_id,$destination_id);
        return $this->successResponse($buses,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);  
    }
    
      //USED WITH auth:api..
    public function createBuses(Request $request) {

        // Log::info();exit;
        $data = $request->only([
            'bus_operator_id','user_id', 'bus_description','cancelation_points', 'name', 'via','bus_number','bus_type_id',
            'bus_sitting_id','amenities_id','cancellationslabs_id','bus_seat_layout_id','running_cycle','has_return_bus','created_by'
          ]);
        
          $busValidation = $this->busValidator->validate($data);

        if ($busValidation->fails()) {
            $errors = $busValidation->errors();
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
          }

      try {
           $this->busService->savePostData($data);
           
      } 
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse($data,"Bus Added",Response::HTTP_CREATED); 
    } 

    public function update(Request $request, $id) {
        
        $data = $request->only([
            'bus_operator_id', 'user_id','amenities','safety', 'ticket_cancelation_id', 'name', 'via','bus_number','bus_description','bus_type_id','bus_sitting_id','cancelation_points','cancellationslabs_id','created_by','bus_seat_layout_id','max_seat_book'
        ]);
       
        $busValidation = $this->busValidator->validate($data);

        if ($busValidation->fails()) {
            $errors = $busValidation->errors();
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
            $this->busService->updatePost($data, $id);
        }
         catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }

        if(isset($data['safety']))
        {
            $safetydata['bus_id']=$id;
            $safetydata['safety']=$data['safety'];
            try {
               $this->busSafetyService->updatePost($safetydata,$id);
            } 
            catch (Exception $e) {
                return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
            }
        }

        return $this->successResponse($data,"Bus Updated",Response::HTTP_OK);
    }
    public function updateBusSequence(Request $request, $id) {
        $data = $request->only([
            'sequence'
        ]);
       
        $bussequenceValidation = $this->BusSequenceValidator->validate($data);

        if ($bussequenceValidation->fails()) {
            $errors = $bussequenceValidation->errors();
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }

        try {
            $this->busService->updateSequncePost($data, $id);
        }
         catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($data,"Bus Sequence Updated",Response::HTTP_OK);
    }

    public function deleteById ($id) {
      try {
          $bus=$this->busService->deleteById($id);
      } 
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
      return $this->successResponse($bus,"Bus Deleted",Response::HTTP_ACCEPTED); 
    }

    public function getById($id) {
        try {
            $busID= $this->busService->getById($id);
        }
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse($busID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }   
    
    public function getBusDT(Request $request) {      
        
        $buses = $this->busService->getAllBusDT($request);
        return $this->successResponse($buses,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    
    public function busSeatsFareData(Request $request) {      
        
        $buses = $this->busService->busSeatsFareData($request);
        return $this->successResponse($buses,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 

    public function busupdatesequenceData(Request $request) {      
        
        $buses = $this->busService->busupdatesequenceData($request);
        return $this->successResponse($buses,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function BusData(Request $request) {      
        
        $buses = $this->busService->BusData($request);
        return $this->successResponse($buses,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function busContactInfo(Request $request)
    {
        $data=$request;
        $this->busContactsService->deleteByBusId($data['id']);
        if(isset($data['conductor_no']))
        {
            $cond['bus_id']=$data['id'];
            $cond['type']="2";
            $cond['phone']=$data['conductor_no'];
            $cond['booking_sms_send']=($data['c_sms_ticket']=="true")?"1":"0";
            $cond['cancel_sms_send']=($data['c_sms_cancel']=="true")?"1":"0";
            $cond['created_by']=$data['created_by'];
            try {
                $this->busContactsService->savePostData($cond);
            } 
            catch (Exception $e) {
                return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
            }
        }
        if(isset($data['manager_no']))
        {
            $mng['bus_id']=$data['id'];
            $mng['type']="1";
            $mng['phone']=$data['manager_no'];
            $mng['booking_sms_send']=($data['m_sms_ticket']=="true")?"1":"0";
            $mng['cancel_sms_send']=($data['m_sms_cancel']=="true")?"1":"0";
            $mng['created_by']=$data['created_by'];

            try {
                $this->busContactsService->savePostData($mng);
            } 
            catch (Exception $e) {
                return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
            }
        }
        if(isset($data['owner_no']))
        {
            $own['bus_id']=$data['id'];
            $own['type']="0";
            $own['phone']=$data['owner_no'];
            $own['booking_sms_send']=($data['o_sms_ticket']=="true")?"1":"0";
            $own['cancel_sms_send']=($data['o_sms_cancel']=="true")?"1":"0";
            $own['created_by']=$data['created_by'];

            try {
                $this->busContactsService->savePostData($own);
            } 
            catch (Exception $e) {
                return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
            }
        }
        
        
        return $this->successResponse($data, "Bus Contact Added", Response::HTTP_ACCEPTED);
    }
    public function save(Request $request) {
        $data=$request;
       
        $NewBus['name']=$data['name'];
        $NewBus['via']=$data['via'];
        $NewBus['bus_description']=$data['bus_description'];
        $NewBus['user_id']=$data['user_id'];
        $NewBus['bus_operator_id']=$data['bus_operator_id'];
        $NewBus['bus_type_id']=$data['bus_type_id'];
        $NewBus['bus_sitting_id']=$data['bus_sitting_id'];
        $NewBus['bus_seat_layout_id']=$data['bus_seat_layout_id'];
        $NewBus['cancelation_points']=$data['cancelation_points'];
        $NewBus['cancellationslabs_id']=$data['cancellationslabs_id'];
        $NewBus['created_by']=$data['created_by'];
        $NewBus['bus_number']=$data['bus_number'];
        $NewBus['amenities']=$data['amenities'];
        $NewBus['max_seat_book']=$data['max_seat_book'];

        $busValidation = $this->busValidator->validate($NewBus);

        if ($busValidation->fails()) {
            $errors = $busValidation->errors();
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        } 
        try {

            if($data['safety'] && $data['busRoutesInfo'] && $data['busRoutes'] && $data['bus_seat_layout_data'])
           {
                $bus_last_insert_id=$this->busService->savePostData($NewBus); 
                /////////////// add safety 
                $safetydata['bus_id']=$bus_last_insert_id;
                $safetydata['safety']=$data['safety'];
                $this->busSafetyService->savePostData($safetydata);
                ///////////////// add ///////////
                if(isset($data['conductor_no']) && $data['conductor_no']!=''){
                    $cond['bus_id']=$bus_last_insert_id;
                    $cond['type']="2";
                    $cond['phone']=$data['conductor_no'];
                    $cond['booking_sms_send']=($data['c_sms_ticket']=="true")?"1":"0";
                    $cond['cancel_sms_send']=($data['c_sms_cancel']=="true")?"1":"0";
                    $cond['created_by']=$data['created_by'];
                    $this->busContactsService->savePostData($cond);
                }
                ////////// manager no
                if(isset($data['manager_no']) && $data['manager_no']!=''){
                    $mng['bus_id']=$bus_last_insert_id;
                    $mng['type']="1";
                    $mng['phone']=$data['manager_no'];
                    $mng['booking_sms_send']=($data['m_sms_ticket']=="true")?"1":"0";
                    $mng['cancel_sms_send']=($data['m_sms_cancel']=="true")?"1":"0";
                    $mng['created_by']=$data['created_by'];
                
                    $this->busContactsService->savePostData($mng);
                }
                    /////////// owner no////////////                   
                    if(isset($data['owner_no']) && $data['owner_no']!=''){

                    $own['bus_id']=$bus_last_insert_id;
                    $own['type']="0";
                    $own['phone']=$data['owner_no'];
                    $own['booking_sms_send']=($data['o_sms_ticket']=="true")?"1":"0";
                    $own['cancel_sms_send']=($data['o_sms_cancel']=="true")?"1":"0";
                    $own['created_by']=$data['created_by'];
                
                    $this->busContactsService->savePostData($own);
                }


                     $busRoutesInfo=$data['busRoutesInfo'];

                        $busRoutes=$data['busRoutes'];
                        $location_arrival=[];
                        $location_depature=[];
                        $bus_location_sequence=[];

                        if($busRoutes){

                        foreach($busRoutes as $routeKey=>$routeValue)
                        {
                            $bus_location_sequence['bus_id']=$timing_grp['bus_id']=$bus_last_insert_id;
                            $bus_location_sequence['location_id']=$timing_grp['location_id']=$routeValue['source_id'];
                            $bus_location_sequence['sequence']=$routeValue['sequence'];

                            $this->busLocationSequenceService->savePostData($bus_location_sequence);

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
                                    $depature_time=$destinations['sourceTime'];
                                    $timing_grp['stoppage_name']=$destinations['sourceLocation'];
                                    $timing_grp['boarding_droping_id']=$destinations['boarding_droping_id'];
                                    $timing_grp['stoppage_time']=$destinations['sourceTime'];
                                    $this->BusStoppageTimingService->savePostData($timing_grp);
                                }
                            }
                            if( $timing_grp['location_id']!="")
                            {
                                $location_depature[$timing_grp['location_id']]['dep_time']=$depature_time;
                            }
                            
                            
                        }

                        }

                        if($busRoutesInfo){

                        foreach($busRoutesInfo as $routeinfoKey=>$routeinfoVal)
                        {                          
                            $booking_seized_array['bus_id']=$routeinfoData['bus_id']=$bus_last_insert_id; //get it from return id
                            // $booking_seized_array['location_id']=$routeinfoVal['from_location'];
                            // $booking_seized_array['seize_booking_minute']=$routeinfoVal['booking_seized'];
                            // $booking_seized_array['created_by']=$data['created_by'];                           
                            // $this->bookingSeizedService->savePostData($booking_seized_array);

                            
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
                            $routeinfoData['seize_booking_minute']=$routeinfoVal['booking_seized'];
                            $stoppage_id=$this->BusStoppageService->savePostData($routeinfoData);

                            if(isset($data['bus_seat_layout_data']))
                            {
                                $seatLayoutData['bus_id']=$bus_last_insert_id;
                                $seatLayoutData['created_by']="Admin";
                                $seatLayoutData['category']="0";
                                $seatLayoutData['duration']="0";
                                $seatLayoutData['ticket_price_id']=$stoppage_id;
                                $seatLayoutData['bus_seat_layout_data']=$data['bus_seat_layout_data'];
                                $this->busSeatsService->savePostData($seatLayoutData);
                            }
                            
                            //ADD TO STOPPAGE THEN TO TIMING
                            
                        }

                        }


                         return $this->successResponse($data, Config::get('constants.RECORD_ADDED'), Response::HTTP_ACCEPTED);

            }else{
                return $this->errorResponse("Some mandatory fileds are missing.Please verify and try again.",Response::HTTP_PARTIAL_CONTENT);
            }

        } 
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        
       
    } 
    public function changeStatus ($id) {
    
        try{
          $status=$this->busService->changeStatus($id);
        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($status,"Bus Status Updated", Response::HTTP_ACCEPTED);
      }

      public function getBusbyBuschedule($id) {
        try {
          $buses= $this->busService->getBusbyBuschedule($id);
        }
        catch (Exception $e) {
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($buses, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
      }

      public function getBusScheduleEntryDates($busId) {
        try {
          $buses= $this->busService->getBusScheduleEntryDates($busId);
        }
        catch (Exception $e) {
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($buses, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
      }

      public function getBusScheduleEntryDatesFilter(Request $request) {

      $bus = $this->busService->getBusScheduleEntryDatesFilter($request);
      return $this->successResponse($bus,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    
      }
     
}
