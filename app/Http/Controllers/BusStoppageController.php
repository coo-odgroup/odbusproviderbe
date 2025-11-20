<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\BusStoppage;
use App\Repositories\BusStoppageRepository;
use App\Models\BusStoppageTiming;
use App\Models\BusLocationSequence;
use App\Repositories\BusSeatsRepository;
use App\Repositories\BusStoppageTimingRepository;
use App\Repositories\BusLocationSequenceRepository;
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

    protected $busStoppageRepository;
    protected $BusStoppageValidator;
    protected $busStoppageTimingRepository;
    protected $busSeatsRepository;
    protected $busLocationSequenceRepository;
    protected $busStoppageTiming;
    protected $busStoppage;
    protected $busLocationSequence;
    protected $location;


    public function __construct(
        BusStoppageRepository $busStoppageRepository,
        BusStoppageValidator $BusStoppageValidator,
        BusStoppageTimingRepository $busStoppageTimingRepository,
        BusSeatsRepository $busSeatsRepository,
        BusLocationSequenceRepository $busLocationSequenceRepository,
        BusStoppageTiming $busStoppageTiming,
        BusStoppage $busStoppage,
        BusLocationSequence $busLocationSequence,
        Location $location

    ) {

        $this->busStoppageRepository = $busStoppageRepository;
        $this->busStoppageTiming = $busStoppageTiming;
        $this->BusStoppageValidator = $BusStoppageValidator;
        $this->busStoppageTimingRepository = $busStoppageTimingRepository;
        $this->busSeatsRepository = $busSeatsRepository;
        $this->busLocationSequenceRepository = $busLocationSequenceRepository;
        $this->busStoppage = $busStoppage;
        $this->busLocationSequence = $busLocationSequence;
        $this->location = $location;
    }


    public function getAllBusStoppage()
    {

        $busStoppage = $this->busStoppageRepository->getAll();
        return $this->successResponse($busStoppage, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function createBusStoppage(Request $request)
    {
        $data = $request->only([
            'bus_id',
            'user_id',
            'source_id',
            'destination_id',
            'base_seat_fare',
            'base_sleeper_fare',
            'dep_time',
            'arr_time',
            'j_day',
            'created_by'
        ]);
        $busStoppageValidation = $this->BusStoppageValidator->validate($data);
        if ($busStoppageValidation->fails()) {
            $errors = $busStoppageValidation->errors();
            return $this->errorResponse($errors, Response::HTTP_PARTIAL_CONTENT);
        }
        try {
            $this->busStoppageRepository->save($data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, Config::get('constants.RECORD_ADDED'), Response::HTTP_CREATED);
    }
    public function updateBusStoppage(Request $request, $id)
    {

        $data = $request;
        $busRoutesInfo = $data['busRoutesInfo'];
        $busRoutes = $data['busRoutes'];



        /////// get existing data from ticket_price , bus_location_sequence & bus_stoppage_timing table
        // if any data is available in table but not  posted from admin panel then we have to make it status 2

        $update_unused_SequenceList = [];
        $update_unused_routeList = [];
        $update_unused_timingList = [];

        $all_bus_location_seq = $this->busLocationSequence->where('bus_id', $id)->get();

        if (count($all_bus_location_seq) > 0) {

            foreach ($all_bus_location_seq as $as) {

                $match = false;

                foreach ($busRoutes as $asr) {
                    if ($as->location_id == $asr['source_id']) { // if matches 
                        $match = true;
                        continue;
                    }
                }

                if ($match == false) {
                    /////// make status 2
                    $this->busLocationSequenceRepository->updateStatus($as->id);
                }
            }
        }

        $all_busStoppageTiming = $this->busStoppageTiming
            ->where('bus_id', $id)
            ->get();

        if (count($all_busStoppageTiming) > 0) {
            foreach ($all_busStoppageTiming as $astopg) {

                $match = false;

                foreach ($busRoutes as $ast) {

                    if ($ast['sourceBoarding']) {


                        foreach ($ast['sourceBoarding'] as $destinations) {
                            if ($astopg->boarding_droping_id == $destinations['boarding_droping_id']) { // if matches 
                                $match = true;
                                continue;
                            }
                        }
                    }
                }

                if ($match == false) {
                    /////// make status
                    $this->busStoppageTimingRepository->updateStatus($astopg->id);
                }
            }
        }

        $chk_tkt_prc = $this->busStoppage
            ->where('bus_id', $id)
            ->get();


        if (count($chk_tkt_prc) > 0) {

            foreach ($chk_tkt_prc as $atp) {

                $match = false;

                foreach ($busRoutesInfo as $ri) {

                    if ($atp->source_id == $ri['from_location'] && $atp->destination_id == $ri['to_location']) { // if matches 
                        $match = true;
                        continue;
                    }
                }

                if ($match == false) {
                    /////// make status 2

                    $this->busStoppageRepository->updateStatus($atp->id);
                }
            }
        }

        ////////////////////////////////////////////////////////////


        foreach ($busRoutes as $routeKey => $routeValue) {
            $bus_location_sequence['bus_id'] = $id;
            $bus_location_sequence['location_id'] = $routeValue['source_id'];
            $bus_location_sequence['sequence'] = $routeValue['sequence'];
            $bus_location_sequence['location_time'] = $routeValue['location_time'];

            ////////// check if exists

            $check_bus_seq = $this->busLocationSequence
                ->where('bus_id', $id)
                ->where('location_id', $routeValue['source_id'])
                ->get();

            if (count($check_bus_seq) > 0) {
                ///////update
                $bus_location_sequence['status'] = 1;
                $this->busLocationSequenceRepository->update($bus_location_sequence, $check_bus_seq[0]->id);
            } else {
                /////// add 
                $this->busLocationSequenceRepository->save($bus_location_sequence);
            }


            $timing_grp['bus_id'] = $id;
            $timing_grp['location_id'] = $routeValue['source_id'];

            $found_arrival = 0;
            $depature_time = "";

            foreach ($routeValue['sourceBoarding'] as $destinations) {

                if ($destinations['sourcechecked'] == "true" || $destinations['sourcechecked'] == true) {

                    if ($found_arrival == 0) {
                        $location_arrival[$timing_grp['location_id']]['arr_time'] = $destinations['sourceTime'];
                        $location_depature[$timing_grp['location_id']]['dep_time'] = $depature_time;

                        $found_arrival++;
                    }

                    $depature_time = $destinations['sourceTime'];

                    $timing_grp['stoppage_name'] = $destinations['sourceLocation'];
                    $timing_grp['stoppage_time'] = $destinations['sourceTime'];
                    $timing_grp['boarding_droping_id'] = $destinations['boarding_droping_id'];

                    ////////// check if exists

                    $check_existing = $this->busStoppageTiming
                        ->where('boarding_droping_id', $destinations['boarding_droping_id'])
                        ->where('bus_id', $id)->get();
                    if (count($check_existing) > 0) {
                        ///////// update bus stopagge timing table
                        try {
                            $timing_grp['status'] = 1;
                            $this->busStoppageTimingRepository->update($timing_grp, $check_existing[0]->id);
                        } catch (Exception $e) {
                            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
                        }
                    } else {
                        //////////// add new in bus stopagge timing table

                        try {
                            $this->busStoppageTimingRepository->save($timing_grp);
                        } catch (Exception $e) {

                            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
                        }
                    }
                }
            }

            if ($timing_grp['location_id'] != "") {
                $location_depature[$timing_grp['location_id']]['dep_time'] = $depature_time;
            }
        }
        foreach ($busRoutesInfo as $routeinfoKey => $routeinfoVal) {

            //CHECK FOR EXISTING ROUTE AND SKIP IF ALREADY ADDED
            // Log::info($busRoutesInfo);
            $routeinfoData['bus_id'] = $id; //get it from return id
            $routeinfoData['bus_operator_id'] = $data['bus_operator_id'];
            $routeinfoData['source_id'] = $routeinfoVal['from_location'];
            $routeinfoData['destination_id'] = $routeinfoVal['to_location'];
            $routeinfoData['start_j_days'] = $routeinfoVal['arr_days'];
            $routeinfoData['j_day'] = $routeinfoVal['dep_days'];
            if (!isset($location_arrival[$routeinfoVal['from_location']]['arr_time'])) {
                continue;
            }
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

            ////////// check if exists

            $chk_tkt_prc = $this->busStoppage
                ->where('bus_id', $id)
                ->where('source_id', $routeinfoVal['from_location'])
                ->where('destination_id', $routeinfoVal['to_location'])
                ->get();

            if (count($chk_tkt_prc) > 0) {
                ///////update  ticket price table

                try {

                    $this->busStoppageRepository->update($routeinfoData, $chk_tkt_prc[0]->id);
                } catch (Exception $e) {
                    // Log::info($e);
                    return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
                }
            } else {
                /////// add to ticket price table
                try {

                    $this->busStoppageRepository->save($routeinfoData);
                } catch (Exception $e) {
                    // Log::info($e);
                    return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
                }
            }
        }

        $new_arr['bus_id'] = $data['bus_id'];
        $new_arr['bus_seat_layout_data'] = $data['bus_seat_layout_data'];
        $new_arr['bus_seat_layout_id'] = $data['bus_seat_layout_id'];
        $new_arr['created_by'] = $data['created_by'];






        $this->busSeatsRepository->update($new_arr, $id);


        return $this->successResponse($request, Config::get('constants.RECORD_UPDATED'), Response::HTTP_CREATED);
    }
    public function deleteBusStoppage($id)
    {
        try {
            $this->busStoppageRepository->delete($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, Config::get('constants.RECORD_REMOVED'), Response::HTTP_ACCEPTED);
    }

    public function getBusStoppage($id)
    {

        $busStoppage = $this->busStoppageRepository->getById($id);
        return $this->successResponse($busStoppage, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getBusStoppagebyRoutes($source_id, $destination_id)
    {
        $busStoppage = $this->busStoppageRepository->getBusStoppagebyRoutes($source_id, $destination_id);
        return $this->successResponse($busStoppage, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }
    public function getBusStoppagebyBusId($busid)
    {
        try {
            $result = $this->busStoppageRepository->getBusStoppagebyBusId($busid);

            // Fetch all location_ids from bus_location_sequence
            $locations = $this->busLocationSequence
                ->where('status', 1)
                ->where('bus_id', $busid)
                ->select('location_id')
                ->get();


            if ($locations->count() > 0) {
                foreach ($locations as $loc) {
                    $loc->location_name = $this->location
                        ->where('id', $loc->location_id)
                        ->value('location_name');
                }
            }


            $data = [
                'result'    => $result,
                'locations' => $locations
            ];

            return $this->successResponse($data, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse("Unable to fetch bus stoppage", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function getBusByOperator($operator_id)
    {
        $busStoppage = $this->busStoppageRepository->getBusByOperator($operator_id);
        return $this->successResponse($busStoppage, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getbusRoutebyBusId($id)
    {
        $busStoppage = $this->busStoppageRepository->getbusRoutebyBusId($id);
        return $this->successResponse($busStoppage, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function AllRoute(Request $request)
    {
        $routes = $this->busStoppageRepository->AllRoute($request);
        return $this->successResponse($routes, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }
}
