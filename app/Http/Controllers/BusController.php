<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use InvalidArgumentException;
use Exception;

// Models
use App\Models\{
    Bus, User, TicketCancelation, BusType, BusSitting,
    BusSeatLayout, BusStoppage, BusStoppageTiming, BusSeats
};

// Traits & Validators
use App\Traits\ApiResponser;
use App\AppValidator\{BusValidator, BusSequenceValidator};

// Services
use App\Services\{
    BusService, BusSafetyService, BusAmenitiesService,
    BusContactsService, BookingSeizedService,
    BusStoppageService, BusLocationSequenceService,
    BusStoppageTimingService, BusSeatsService
};

// Repositories
use App\Repositories\{
    BusContactsRepository, BusAmenitiesRepository,
    BusRepository, BusSafetyRepository
};

// Jobs
use App\Jobs\TestingEmailJob;
<<<<<<< Updated upstream
use App\Models\BusSeats;
use App\Services\BusSeatsService;
use Illuminate\Support\Facades\Log;

/*Priyadarshi Need to Review*/
=======

/* Priyadarshi - Review Done */
>>>>>>> Stashed changes
class BusController extends Controller
{
    use ApiResponser;

<<<<<<< Updated upstream
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

    public function __construct(BusContactsService $busContactsService, BusAmenitiesService $busAmenitiesService, BusService $busService, BusValidator $busValidator, BusSequenceValidator $BusSequenceValidator, BusStoppageService $BusStoppageService, BusStoppageTimingService $BusStoppageTimingService, BusSeatsService $busSeatsService, BusSafetyService $busSafetyService, BookingSeizedService $bookingSeizedService, BusLocationSequenceService $busLocationSequenceService)
    {
=======
    protected $busService, $busValidator, $BusSequenceValidator,
              $busAmenitiesService, $busContactsService, $BusStoppageService,
              $busLocationSequenceService, $BusStoppageTimingService,
              $busSeatsService, $busSafetyService, $bookingSeizedService,
              $busContactsRepository, $busAmenitiesRepository,
              $busRepository, $busSafetyRepository;

    public function __construct(
        BusContactsService $busContactsService,
        BusSafetyRepository $busSafetyRepository,
        BusAmenitiesRepository $busAmenitiesRepository,
        BusContactsRepository $busContactsRepository,
        BusAmenitiesService $busAmenitiesService,
        BusService $busService,
        BusValidator $busValidator,
        BusSequenceValidator $BusSequenceValidator,
        BusStoppageService $BusStoppageService,
        BusStoppageTimingService $BusStoppageTimingService,
        BusSeatsService $busSeatsService,
        BusSafetyService $busSafetyService,
        BookingSeizedService $bookingSeizedService,
        BusLocationSequenceService $busLocationSequenceService,
        BusRepository $busRepository
    ) {
>>>>>>> Stashed changes
        $this->busService = $busService;
        $this->busValidator = $busValidator;
        $this->BusSequenceValidator = $BusSequenceValidator;
        $this->busAmenitiesService = $busAmenitiesService;
        $this->busContactsService = $busContactsService;
        $this->BusStoppageService = $BusStoppageService;
        $this->BusStoppageTimingService = $BusStoppageTimingService;
        $this->busSeatsService = $busSeatsService;
        $this->busSafetyService = $busSafetyService;
        $this->bookingSeizedService = $bookingSeizedService;
        $this->busLocationSequenceService = $busLocationSequenceService;
<<<<<<< Updated upstream


=======
        $this->busContactsRepository = $busContactsRepository;
        $this->busAmenitiesRepository = $busAmenitiesRepository;
        $this->busRepository = $busRepository;
        $this->busSafetyRepository = $busSafetyRepository;
>>>>>>> Stashed changes
    }

    

    public function seatsBus(Request $request)
    {
<<<<<<< Updated upstream
        $buses = $this->busService->seatsBus($request);
=======
        $buses = $this->busRepository->seatsBus($request);
>>>>>>> Stashed changes
        return $this->successResponse($buses, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getAll(Request $request)
    {
<<<<<<< Updated upstream
        $buses = $this->busService->getAll();
=======
        $buses = $this->busRepository->getAllBusDT($request);
>>>>>>> Stashed changes
        return $this->successResponse($buses, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getByOperaor($id)
    {
<<<<<<< Updated upstream
        $buses = $this->busService->getByOperaor($id);
=======
        $buses = $this->busRepository->getByOperaor($id);
>>>>>>> Stashed changes
        return $this->successResponse($buses, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getLocationBus($source_id, $destination_id)
    {
<<<<<<< Updated upstream
        $buses = $this->busService->getLocationBus($source_id, $destination_id);
=======
        $buses = $this->busRepository->getLocationBus($source_id, $destination_id);
>>>>>>> Stashed changes
        return $this->successResponse($buses, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function locationBusss(Request $request)
    {
<<<<<<< Updated upstream
        $buses = $this->busService->locationBusss($request);
=======
        $buses = $this->busRepository->locationBusss($request);
>>>>>>> Stashed changes
        return $this->successResponse($buses, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

   

    public function createBuses(Request $request)
    {
<<<<<<< Updated upstream

        // Log::info($request);exit;
=======
>>>>>>> Stashed changes
        $data = $request->only([
            'bus_operator_id','user_id','bus_description','cancelation_points',
            'name','via','bus_number','bus_type_id','bus_sitting_id','amenities_id',
            'cancellationslabs_id','bus_seat_layout_id','running_cycle','has_return_bus','created_by'
        ]);

        $busValidation = $this->busValidator->validate($data);

        if ($busValidation->fails()) {
            return $this->errorResponse($busValidation->errors()->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        try {
<<<<<<< Updated upstream
            $this->busService->savePostData($data);

=======
            $this->busRepository->save($data);
>>>>>>> Stashed changes
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }

        return $this->successResponse($data, "Bus Added", Response::HTTP_CREATED);
    }

<<<<<<< Updated upstream
    public function update(Request $request, $id)
    {
        // log::info($request);exit;
        $data = $request->only([
            'bus_operator_id','bus_number','user_id','amenities','safety','type','lower_sleeper_extra_fare','ticket_cancelation_id', 'name', 'via','bus_number','bus_description','bus_type_id','bus_sitting_id','cancelation_points','cancellationslabs_id','created_by','bus_seat_layout_id','max_seat_book'
        ]);

        $busValidation = $this->busValidator->basicValidate($data);
=======

    public function update(Request $request, $id)
    {
        $data = $request->only([
            'bus_operator_id','bus_number','user_id','amenities','safety','type',
            'lower_sleeper_extra_fare','ticket_cancelation_id','name','via','bus_description',
            'bus_type_id','bus_sitting_id','cancelation_points','cancellationslabs_id',
            'created_by','bus_seat_layout_id','max_seat_book'
        ]);

        $busValidation = $this->busValidator->basicValidate($data);

        if ($busValidation->fails()) {
            return $this->errorResponse($busValidation->errors()->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }
>>>>>>> Stashed changes

        if ($busValidation->fails()) {
            $errors = $busValidation->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }
        try {
<<<<<<< Updated upstream
            $this->busService->updatePost($data, $id);
=======
            $this->busRepository->update($data, $id);
>>>>>>> Stashed changes
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }

        if (isset($data['safety'])) {
<<<<<<< Updated upstream
            $safetydata['bus_id'] = $id;
            $safetydata['safety'] = $data['safety'];
            try {
                $this->busSafetyService->updatePost($safetydata, $id);
=======
            $safetydata = [
                'bus_id' => $id,
                'safety' => $data['safety']
            ];

            try {
                $this->busSafetyRepository->update($safetydata, $id);
>>>>>>> Stashed changes
            } catch (Exception $e) {
                return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
            }
        }

        return $this->successResponse($data, "Bus Updated", Response::HTTP_OK);
    }
<<<<<<< Updated upstream
    public function updateBusSequence(Request $request, $id)
    {
        $data = $request->only([
            'sequence'
        ]);

        $bussequenceValidation = $this->BusSequenceValidator->validate($data);

        if ($bussequenceValidation->fails()) {
            $errors = $bussequenceValidation->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        try {
            $this->busService->updateSequncePost($data, $id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($data, "Bus Sequence Updated", Response::HTTP_OK);
    }

    public function deleteById($id)
    {
        try {
            $bus = $this->busService->deleteById($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse($bus, "Bus Deleted", Response::HTTP_ACCEPTED);
    }
=======

    public function updateBusSequence(Request $request, $id)
    {
        $data = $request->only(['sequence']);
        $bussequenceValidation = $this->BusSequenceValidator->validate($data);

        if ($bussequenceValidation->fails()) {
            return $this->errorResponse($bussequenceValidation->errors()->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        try {
            $this->busRepository->updatesequence($data, $id);
            return $this->successResponse($data, "Bus Sequence Updated", Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse(Config::get('constants.RECORD_NOT_FOUND'), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    
    public function deleteById($id)
    {
        try {
            $bus = $this->busRepository->delete($id);
            return $this->successResponse($bus, "Bus Deleted", Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return $this->errorResponse(Config::get('constants.RECORD_NOT_FOUND'), Response::HTTP_NOT_FOUND);
        }
    }

  
>>>>>>> Stashed changes

    public function getById($id)
    {
        try {
<<<<<<< Updated upstream
            $busID = $this->busService->getById($id);
=======
            $busID = $this->busRepository->getById($id);
>>>>>>> Stashed changes
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse($busID, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getBusDT(Request $request)
    {
<<<<<<< Updated upstream

        $buses = $this->busService->getAllBusDT($request);
=======
        $buses = $this->busRepository->getAllBusDT($request);
>>>>>>> Stashed changes
        return $this->successResponse($buses, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

  

<<<<<<< Updated upstream
    public function busSeatsFareData(Request $request)
    {

        $buses = $this->busService->busSeatsFareData($request);
        return $this->successResponse($buses, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function busseatfarereport(Request $request)
    {

        $busesData = $this->busService->busseatfarereport($request);
        return $this->successResponse($busesData, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function busupdatesequenceData(Request $request)
    {

        $buses = $this->busService->busupdatesequenceData($request);
        return $this->successResponse($buses, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function BusData(Request $request)
    {

        $buses = $this->busService->BusData($request);
        return $this->successResponse($buses, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }
    public function busContactInfo(Request $request)
    {
        // log::info($request);
        // exit;
        $data = $request;
        //$bus_name=$data['bus_number'];
        // $this->busService->updateBusName($data,$data['id']);
        $this->busContactsService->deleteByBusId($data['id']);
        if (isset($data['conductor_no'])) {
            $cond['bus_id'] = $data['id'];
            $cond['type'] = "2";
            $cond['phone'] = $data['conductor_no'];
            $cond['booking_sms_send'] = ($data['c_sms_ticket'] == "0") ? "0" : "1";
            $cond['cancel_sms_send'] = ($data['c_sms_cancel'] == "0") ? "0" : "1";
            $cond['created_by'] = $data['created_by'];
            try {
                $this->busContactsService->savePostData($cond);
            } catch (Exception $e) {
                return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
            }
=======
    public function busContactInfo(Request $request)
    {
        $data = $request->all();
        $id = $data['id'] ?? null;

        if (!$id) {
            return $this->errorResponse("Bus ID is required", Response::HTTP_BAD_REQUEST);
>>>>>>> Stashed changes
        }

        $this->busContactsRepository->deletebyBusid($id);

<<<<<<< Updated upstream
            try {
                $this->busContactsService->savePostData($mng);
            } catch (Exception $e) {
                return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
            }
        }


        if (isset($data['owner_no'])) {
            $own['bus_id'] = $data['id'];
            $own['type'] = "0";
            $own['phone'] = $data['owner_no'];
            $own['booking_sms_send'] = ($data['o_sms_ticket'] == "0") ? "0" : "1";
            $own['cancel_sms_send'] = ($data['o_sms_cancel'] == "0") ? "0" : "1";
            $own['created_by'] = $data['created_by'];

            try {
                $this->busContactsService->savePostData($own);
            } catch (Exception $e) {
                return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
=======
        // Save contact types
        foreach (['conductor_no' => 2, 'manager_no' => 1, 'owner_no' => 0] as $field => $type) {
            if (!empty($data[$field])) {
                $record = [
                    'bus_id' => $id,
                    'type' => $type,
                    'phone' => $data[$field],
                    'booking_sms_send' => ($data["{$field[0]}_sms_ticket"] ?? 0) ? "1" : "0",
                    'cancel_sms_send' => ($data["{$field[0]}_sms_cancel"] ?? 0) ? "1" : "0",
                    'created_by' => $data['created_by'] ?? null,
                ];

                try {
                    $this->busContactsRepository->save($record);
                } catch (Exception $e) {
                    return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
                }
>>>>>>> Stashed changes
            }
        }

        return $this->successResponse($data, "Bus Contact Details Updated", Response::HTTP_OK);
    }

<<<<<<< Updated upstream
    public function allCouponBusList($id)
    {

        $buses = $this->busService->allCouponBusList($id);
        return $this->successResponse($buses, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);

    }

    public function busDisplayInfo()
    {

        $buses = $this->busService->busDisplayInfo();
        return $this->successResponse($buses, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);

    }



    public function save(Request $request)
    {


        $data = $request;

        $NewBus['name'] = $data['name'];
        $NewBus['via'] = $data['via'];
        $NewBus['bus_description'] = $data['bus_description'];
        $NewBus['user_id'] = $data['user_id'];
        $NewBus['bus_operator_id'] = $data['bus_operator_id'];
        $NewBus['bus_type_id'] = $data['bus_type_id'];
        $NewBus['bus_sitting_id'] = $data['bus_sitting_id'];
        $NewBus['bus_seat_layout_id'] = $data['bus_seat_layout_id'];
        $NewBus['cancelation_points'] = $data['cancelation_points'];
        $NewBus['cancellationslabs_id'] = $data['cancellationslabs_id'];
        $NewBus['created_by'] = $data['created_by'];
        $NewBus['bus_number'] = $data['bus_number'];
        $NewBus['amenities'] = $data['amenities'];
        $NewBus['type'] = $data['type'];
        $NewBus['lower_sleeper_extra_fare'] = $data['lower_sleeper_extra_fare'];
        $NewBus['max_seat_book'] = $data['max_seat_book'];

        $busValidation = $this->busValidator->validate($data->all());

        if ($busValidation->fails()) {
            $errors = $busValidation->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }
        try {


            $up = 0;
            $lp = 0;

            if ($data['bus_seat_layout_data']) {
                foreach ($data['bus_seat_layout_data'] as $sLayoutData) {
                    if (isset($sLayoutData['upperBerth'])) {
                        if (count($sLayoutData['upperBerth']) > 0) {
                            foreach ($sLayoutData['upperBerth'] as $upperBerthData) {
                                if ($upperBerthData['seatChecked'] == true) {
                                    if ($upperBerthData['seatId'] != "") {
                                        $up++;
                                    }

                                }
                            }
                        }
                    }



                    if (isset($sLayoutData['lowerBerth'])) {
                        if (count($sLayoutData['lowerBerth']) > 0) {
                            foreach ($sLayoutData['lowerBerth'] as $lowerBerthData) {
                                if ($lowerBerthData['seatChecked'] == true) {
                                    if ($lowerBerthData['seatId'] != "") {
                                        $lp++;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if ($up == 0 && $lp == 0) { // check if both upper berth & lower berth is empty

                return $this->errorResponse("Bus must have selected at least 1 seat.Please check seat layout and try again.", Response::HTTP_PARTIAL_CONTENT);

            }

            $bus_last_insert_id = $this->busService->savePostData($NewBus);
            /////////////// add safety
            $safetydata['bus_id'] = $bus_last_insert_id;
            $safetydata['safety'] = $data['safety'];
            $this->busSafetyService->savePostData($safetydata);
            ///////////////// add ///////////
            if (isset($data['conductor_no']) && $data['conductor_no'] != '') {
                $cond['bus_id'] = $bus_last_insert_id;
                $cond['type'] = "2";
                $cond['phone'] = $data['conductor_no'];
                $cond['booking_sms_send'] = ($data['c_sms_ticket'] == "true" || $data['c_sms_ticket'] == 1) ? "1" : "0";
                $cond['cancel_sms_send'] = ($data['c_sms_cancel'] == "true" || $data['c_sms_cancel'] == 1) ? "1" : "0";
                $cond['created_by'] = $data['created_by'];
                $this->busContactsService->savePostData($cond);
            }
            ////////// manager no
            if (isset($data['manager_no']) && $data['manager_no'] != '') {
                $mng['bus_id'] = $bus_last_insert_id;
                $mng['type'] = "1";
                $mng['phone'] = $data['manager_no'];
                $mng['booking_sms_send'] = ($data['m_sms_ticket'] == "true" || $data['m_sms_ticket'] == 1) ? "1" : "0";
                $mng['cancel_sms_send'] = ($data['m_sms_cancel'] == "true" || $data['m_sms_cancel'] == 1) ? "1" : "0";
                $mng['created_by'] = $data['created_by'];

                $this->busContactsService->savePostData($mng);
            }
            /////////// owner no////////////
            if (isset($data['owner_no']) && $data['owner_no'] != '') {

                $own['bus_id'] = $bus_last_insert_id;
                $own['type'] = "0";
                $own['phone'] = $data['owner_no'];
                $own['booking_sms_send'] = ($data['o_sms_ticket'] == "true" || $data['o_sms_cancel'] == 1) ? "1" : "0";
                $own['cancel_sms_send'] = ($data['o_sms_cancel'] == "true" || $data['o_sms_cancel'] == 1) ? "1" : "0";
                $own['created_by'] = $data['created_by'];

                $this->busContactsService->savePostData($own);
            }


            $busRoutesInfo = $data['busRoutesInfo'];

            $busRoutes = $data['busRoutes'];
            $location_arrival = [];
            $location_depature = [];
            $bus_location_sequence = [];

            if ($busRoutes) {

                foreach ($busRoutes as $routeKey => $routeValue) {
                    $bus_location_sequence['bus_id'] = $timing_grp['bus_id'] = $bus_last_insert_id;
                    $bus_location_sequence['location_id'] = $timing_grp['location_id'] = $routeValue['source_id'];
                    $bus_location_sequence['sequence'] = $routeValue['sequence'];
                    $bus_location_sequence['location_time'] = $routeValue['location_time'];

                    $this->busLocationSequenceService->savePostData($bus_location_sequence);

                    $found_arrival = 0;
                    $depature_time = "";
                    foreach ($routeValue['sourceBoarding'] as $destinations) {
                        if ($destinations['sourcechecked'] == "true") {
                            if ($found_arrival == 0) {
                                $location_arrival[$timing_grp['location_id']]['arr_time'] = $destinations['sourceTime'];
                                $found_arrival++;
                            }
                            $depature_time = $destinations['sourceTime'];
                            $timing_grp['stoppage_name'] = $destinations['sourceLocation'];
                            $timing_grp['boarding_droping_id'] = $destinations['boarding_droping_id'];
                            $timing_grp['stoppage_time'] = $destinations['sourceTime'];
                            $this->BusStoppageTimingService->savePostData($timing_grp);
                        }
                    }
                    if ($timing_grp['location_id'] != "") {
                        $location_depature[$timing_grp['location_id']]['dep_time'] = $depature_time;
                    }


                }

            }

            if ($busRoutesInfo) {

                foreach ($busRoutesInfo as $routeinfoKey => $routeinfoVal) {
                    $booking_seized_array['bus_id'] = $routeinfoData['bus_id'] = $bus_last_insert_id; //get it from return id
                    //CHECK DUPLICATE ROUTES
                    $recordArray = array(
                        "bus_id" => $routeinfoData['bus_id'],
                        "source_id" => $routeinfoVal['from_location'],
                        "destination_id" => $routeinfoVal['to_location'],

                    );
                    $old_data = $this->BusStoppageService->checkDuplicate($recordArray);
                    if (count($old_data) > 0) {
                        continue;
                    }
                    // $booking_seized_array['location_id']=$routeinfoVal['from_location'];
                    // $booking_seized_array['seize_booking_minute']=$routeinfoVal['booking_seized'];
                    // $booking_seized_array['created_by']=$data['created_by'];
                    // $this->bookingSeizedService->savePostData($booking_seized_array);


                    $routeinfoData['bus_operator_id'] = $data['bus_operator_id'];
                    $routeinfoData['source_id'] = $routeinfoVal['from_location'];
                    $routeinfoData['destination_id'] = $routeinfoVal['to_location'];
                    $routeinfoData['start_j_days'] = $routeinfoVal['arr_days'];
                    $routeinfoData['j_day'] = $routeinfoVal['dep_days'];

                    $routeinfoData['arr_time'] = $location_arrival[$routeinfoVal['from_location']]['arr_time'];
                    $routeinfoData['dep_time'] = $location_depature[$routeinfoVal['to_location']]['dep_time'];

                    $routeinfoData['user_id'] = "1";
                    $routeinfoData['base_seat_fare'] = $routeinfoVal['seater_fare'];
                    $routeinfoData['base_sleeper_fare'] = $routeinfoVal['sleeper_fare'];
                    $routeinfoData['seize_booking_minute'] = $routeinfoVal['booking_seized'];
                    $routeinfoData['actual_time'] = $routeinfoVal['actual_time'];
                    if ($routeinfoVal['route_status'] == "true") {
                        $routeinfoData['status'] = "1";
                    } else {
                        $routeinfoData['status'] = "0";
                    }

                    $stoppage_id = $this->BusStoppageService->savePostData($routeinfoData);

                    if (isset($data['bus_seat_layout_data'])) {
                        $seatLayoutData['bus_id'] = $bus_last_insert_id;
                        $seatLayoutData['created_by'] = "Admin";
                        $seatLayoutData['category'] = "0";
                        $seatLayoutData['duration'] = "0";
                        $seatLayoutData['ticket_price_id'] = $stoppage_id;
                        $seatLayoutData['bus_seat_layout_data'] = $data['bus_seat_layout_data'];
                        $this->busSeatsService->savePostData($seatLayoutData);
                    }

                    //ADD TO STOPPAGE THEN TO TIMING

                }

            }


            return $this->successResponse($data, Config::get('constants.RECORD_ADDED'), Response::HTTP_ACCEPTED);

            // }else{
            //     return $this->errorResponse("Some mandatory fileds are missing.Please verify and try again.",Response::HTTP_PARTIAL_CONTENT);
            // }

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }


    }
    public function changeStatus($id)
    {

        try {
            $status = $this->busService->changeStatus($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($status, "Bus Status Updated", Response::HTTP_ACCEPTED);
=======
    public function getBusScheduleEntryDatesFilter(Request $request)
    {
        $bus = $this->busRepository->getBusScheduleEntryDatesFilter($request->all());
        return $this->successResponse($bus, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getBusScheduleEntry(Request $request)
    {
        $busId = $request->input('busId');
        $bus = $this->busRepository->getBusScheduleEntryDates($busId);
        return $this->successResponse($bus, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getBusScheduleEntryforOperator(Request $request)
    {
        $bus = $this->busRepository->getBusScheduleEntryforOperator($request->all());
        return $this->successResponse($bus, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function GetBusList(Request $request)
    {
        $bus = $this->busRepository->GetBusList($request->all());
        return $this->successResponse($bus, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    

    public function changeStatus($id)
    {
        try {
            $status = $this->busRepository->changeStatus($id);
            return $this->successResponse($status, "Bus Status Updated", Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return $this->errorResponse(Config::get('constants.UNABLE_CHANGE_STATUS'), Response::HTTP_PARTIAL_CONTENT);
        }
>>>>>>> Stashed changes
    }

    public function getBusbyBuschedule($id)
    {
        try {
<<<<<<< Updated upstream
            $buses = $this->busService->getBusbyBuschedule($id);
=======
            $buses = $this->busRepository->getBusbyBuschedule($id);
>>>>>>> Stashed changes
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($buses, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getBusScheduleEntryDates($busId)
    {
        try {
<<<<<<< Updated upstream
            $buses = $this->busService->getBusScheduleEntryDates($busId);
=======
            $buses = $this->busRepository->getBusScheduleEntryDates($busId);
>>>>>>> Stashed changes
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse($buses, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

<<<<<<< Updated upstream
    public function getBusScheduleEntryDatesFilter(Request $request)
    {

        $bus = $this->busService->getBusScheduleEntryDatesFilter($request);
        return $this->successResponse($bus, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);

    }

    public function getBusScheduleEntry(Request $request)
    {

        $bus = $this->busService->getBusScheduleEntry($request);
        return $this->successResponse($bus, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);

    }

    public function getBusScheduleEntryforOperator(Request $request)
    {

        $bus = $this->busService->getBusScheduleEntryforOperator($request);
        return $this->successResponse($bus, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);

    }


=======
>>>>>>> Stashed changes
    public function testingEmail(Request $request)
    {
        $to = $request->input('email');
        $name = $request->input('name');
        $res = TestingEmailJob::dispatch($to, $name);
        return $this->successResponse($res, Config::get('constants.RECORD_ADDED'), Response::HTTP_CREATED);
    }

    public function allCouponBusList($id)
    {
<<<<<<< Updated upstream

        $bus = $this->busService->GetBusList($request);
        return $this->successResponse($bus,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);

=======
        try {
            $buses = $this->busRepository->allCouponBusList($id);
            return $this->successResponse($buses, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse(Config::get('constants.RECORD_NOT_FOUND'), Response::HTTP_NOT_FOUND);
        }
>>>>>>> Stashed changes
    }

    public function busDisplayInfo()
    {
        $buses = $this->busRepository->busDisplayInfo();
        return $this->successResponse($buses, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }
}
