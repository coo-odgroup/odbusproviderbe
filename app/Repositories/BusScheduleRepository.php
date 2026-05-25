<?php

namespace App\Repositories;

use App\Models\BusSchedule;
use App\Models\BusScheduleDate;
use App\Models\Location;
use App\Models\Bus;
use App\Models\BusSeats;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
class BusScheduleRepository
{
    protected $busSchedule;
    protected $busScheduleDate;
    protected $location;
    protected $bus;

    public function __construct(BusSchedule $busSchedule, Location $location, Bus $bus, BusScheduleDate $busScheduleDate)
    {
        $this->busSchedule = $busSchedule;
        $this->location = $location;
        $this->bus = $bus;
        $this->busScheduleDate = $busScheduleDate;
    }
    /**
     * Get All bus Schedule in Data Table
     *
     * @param $id
     * @return mixed
     */
    public function getDatatable($request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        if (!is_numeric($rowperpage)) {
            $rowperpage = Config::get('constants.ALL_RECORDS');
        }
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');
        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value
        // Total records
        $totalRecords = $this->busSchedule->whereNotIn('status', [2])->count();
        $totalRecordswithFilter = $this->busSchedule->with('busScheduleDate')->with('bus.busOperator', 'bus.busstoppage')
        ->whereHas('bus', function ($query) use ($searchValue) {
            $query->where('name', 'like', '%' .$searchValue . '%');
        })->whereNotIn('status', [2])->count();

        $busRecords =  $this->busSchedule->with('busScheduleDate')->with('bus.busOperator', 'bus.busstoppage')
        ->orderBy($columnName, $columnSortOrder)
        ->whereHas('bus', function ($query) use ($searchValue) {
            $query->where('name', 'like', '%' .$searchValue . '%')
            ->groupBy('bus_id');
        })

        ->skip($start)
        ->take($rowperpage)
        ->whereNotIn('status', [2])
        ->get();
        $data_arr = array();
        $bus_stoppage = array();


        foreach ($busRecords as $key => $busRecord) {

            $dateRecord = $busRecord->busScheduleDate;
            $name = $busRecord->bus->name;
            $name = $name." >> ".$busRecord->bus->bus_number;
            $operatorName = $busRecord->bus->busOperator->operator_name;
            $bStoppages = $busRecord->bus->busstoppage;
            $data_arr[] = $busRecord->toArray();
            $data_arr[$key]['name'] = $name;
            $data_arr[$key]['operatorName'] = $operatorName;
            $stoppageName = "";
            $routesdata = "";
            $entry_dates = array();
            foreach ($dateRecord as $edate) {
                $entryDates = $edate->entry_date;
                $entry_dates[] = array(
                    "entryDates" => date('j M Y ', strtotime($entryDates)),
                );
            }
            $data_arr[$key]['entryDates'] = $entry_dates;

            $sourceId = $bStoppages[0]->source_id;
            $destinationId = $bStoppages[0]->destination_id;
            $stoppageName = $this->location->whereIn('id', array($sourceId, $destinationId))->orderBy('id', 'ASC')->get('name');
            $bus_stoppage[] = array(
                "sourceName" => $stoppageName,
                "destinationName" => $stoppageName,
            );
            $routesdata =  $stoppageName[1]['name']."-".$stoppageName[0]['name'];
            $data_arr[$key]['routes'] = $routesdata;
        }
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );
        return ($response);
    }
    /**
     * Get All bus Schedule
     *
     * @param $id
     * @return mixed
     */
    public function getAll()
    {
        return $this->busSchedule->get();
    }

    public function removeOldBusScheduleCronjob()
    {
        $today = date('Y-m-d');
        $checkdate = date('Y-m-d', strtotime($today. '-35 days'));

        $dltData = $this->busScheduleDate->where('entry_date', '<', $checkdate)->delete();

        $msg = $dltData." Record deleted from ".$checkdate." of bus schedule" ;
        log::info($msg);

        return $msg;
    }


    public function scheduleCronJob()
    {

        ini_set('memory_limit','2048M');
        ini_set('max_execution_time','300');

        $msg = [];
        $count = 0;
        // $today='2022-09-21';
        $today = date('Y-m-d');
        $checkdate = date('Y-m-d', strtotime($today. ' + 15 days'));
        $data = $this->busSchedule->where('status', 1)->with(['busScheduleDate' => function ($a) {
            $a->orderBy('id', 'DESC')
            ;
        }])->get();


        foreach ($data as $v) {
            if (isset($v->busScheduleDate[0])) {
                if ($checkdate == $v->busScheduleDate[0]->entry_date) {
                    $request['bus_schedule_id'] = $v->busScheduleDate[0]->bus_schedule_id ;
                    $request['running_cycle'] = $v->running_cycle;
                    $request['created_by'] = 'server';
                    $request['entry_date'] = $checkdate;
                    $this->serverSave($request);
                    $count++;

                }
            }

        }

        Log::info($count.' bus scheduled today');

        return $count.' bus scheduled today';
    }

    public function serverSave($request)
    {

        $entdate = date('Y-m-d', strtotime($request['entry_date']. ' + '.$request['running_cycle'].' days'));
        $this->busSchedule = $this->busSchedule->find($request['bus_schedule_id']);
        $busScheduleDateModels = [];
        $bus_seat_count=[];
        $entryDate = $entdate;
        $busScheduleDate = new BusScheduleDate();
        $busScheduleDate->bus_schedule_id = $this->busSchedule->id;
        $busScheduleDate->entry_date = $entryDate;
        for ($dateCount = 0;$dateCount < 30;$dateCount++) {
            $busScheduleDate = new BusScheduleDate();
            $busScheduleDate->bus_schedule_id = $this->busSchedule->id;
            if ($dateCount != 0) {
                $entryDate = strtotime("+".$request['running_cycle']."day", strtotime($entryDate));
            } else {
                $entryDate = strtotime($entryDate);
            }
            $entryDate = date("Y-m-d", $entryDate);
            $busScheduleDate->entry_date = $entryDate;
            $busScheduleDate->created_by = $request['created_by'];
            $busScheduleDate->status = 1;

            $dbl_check = $this->busScheduleDate->where('bus_schedule_id', $this->busSchedule->id)->where('entry_date', $entryDate)->get();

            if (count($dbl_check) == 0) {
                $busScheduledateModels[] =  $busScheduleDate;
            }
        }

        $this->busSchedule->busScheduleDate()->saveMany($busScheduledateModels);

            ///insert to bus_seat_count table 

            $insertData = [];

            $busId = $this->busSchedule->bus_id;

            $totalSeats = DB::table('bus_seats')
                ->where('bus_id', $busId)
                ->where('status', 1)

                ->where(function ($q) {

                    $q->where(function ($qq) {

                        // Normal seats
                        $qq->whereNull('type')
                            ->whereNull('operation_date')
                            ->where('duration', 0);

                    })->orWhere(function ($qq) {

                        // Extra permanent open seats
                        $qq->whereNull('type')
                            ->whereNull('operation_date')
                            ->where('duration', '>', 0);

                    });

                })

                ->distinct('seats_id')
                ->count('seats_id');


            $ticketPrices = DB::table('ticket_price')
                ->where('bus_id', $busId)
                ->where('status', 1)
                ->get();

            foreach ($busScheduledateModels as $scheduleDate) {

                foreach ($ticketPrices as $tp) {

                    $insertData[] = [

                        'bus_id'           => $busId,

                        'ticket_price_id'  => $tp->id,

                        'journey_date'     => $scheduleDate->entry_date,

                        'total_seat'       => $totalSeats,

                        'available_seat'   => $totalSeats,

                        'booked_seat'      => 0,
                        'blocked_seat'     => 0,
                        'hold_seat'        => 0,

                        'created_at'       => now(),
                        'updated_at'       => now(),

                        'updated_by'       => 'server'

                    ];

                }

            }


            if (!empty($insertData)) {

                DB::table('bus_seat_count')->insert($insertData);

            }


        return $busScheduledateModels;
    }

    public function busScheduleById($id)
    {
        $data = $this->busSchedule->with(["busScheduleDate" => function ($b) {
            $b->orderBy('id', 'ASC')
              ->where('entry_date', '>=', date('Y-m-d'))->limit(16);
        }])
                                  ->where('bus_id', $id)
                                  ->where('status', 1)
                                  ->get();
        return $data;
    }


    public function busSchedulerData($request)
    {

        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;
        $source_id = $request['source_id'];
        $destination_id = $request['destination_id'];
        $bus_operator_id = $request['bus_operator_id'];
        $bus_id = $request['bus_id'];

        $data = $this->busSchedule->with(["busScheduleDate" => function ($b) {
            $b->orderBy('id', 'DESC')
              ->where('entry_date', '>=', date('Y-m-d'));
        }])
                                ->with('bus.busOperator', 'bus.ticketPrice')
                                 ->whereNotIn('status', [2])
                                 ->orderBy('id', 'DESC');

        if ($request['USER_BUS_OPERATOR_ID'] != "") {
            $data = $data->whereHas('bus', function ($query) use ($request) {
                $query->where('bus_operator_id', $request['USER_BUS_OPERATOR_ID']);
            });
        }

        if ($paginate == 'all') {
            $paginate = Config::get('constants.ALL_RECORDS');
        } elseif ($paginate == null) {
            $paginate = 10 ;
        }

        if ($name != null) {
            $data = $this->busSchedule->with('busScheduleDate', 'bus.busOperator', 'bus.ticketPrice')
                ->whereHas('bus.busOperator', function ($query) use ($name) {
                    $query->where('bus_number', $name);
                })
                ->where('status', '!=', '2')
                ->orWhere('created_by', 'like', '%' .$name . '%')
                ->orderBy('id', 'DESC');

        }
        if ($bus_operator_id != null) {
            $data = $data->whereHas('bus', function ($query) use ($bus_operator_id) {
                $query->where('bus_operator_id', $bus_operator_id);
            });
        }

        if ($bus_id != null) {
            $data = $data->where('bus_id', $bus_id);
        }

        if ($source_id != null && $destination_id != null) {
            $loc = [];
            $loc[1] = $source_id;
            $loc[2] = $destination_id;

            $data = $data->whereHas('bus.ticketPrice', function ($query) use ($loc) {
                $query->where('source_id', $loc[1])
                       ->where('destination_id', $loc[2]);
            });

        }

        $data = $data->paginate($paginate);

        if (count($data) > 0) {
            foreach ($data as $key => $v) {
                if (count($v->bus['ticketPrice']) > 0) {
                    $v['from_location'] = $this->location->where('id', $v->bus['ticketPrice'][0]['source_id'])->get();
                    $v['to_location'] = $this->location->where('id', $v->bus['ticketPrice'][0]['destination_id'])->get();
                }
            }
        }
        $response = array(
             "count" => $data->count(),
             "total" => $data->total(),
            "data" => $data
           );
        return $response;
    }
    /**
     * Get bus Schedule by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->busSchedule ->where('id', $id)->get();
    }

    /**
     * Save Schedule
     *
     * @param $data
     * @return busschedule
     */
    public function save($data)
    {
        // log::info($data);
        // log::info(date("Y-m-d"));
        // exit;
        if ($data['entry_date'] >= date("Y-m-d")) {
            $duplicate_data = $this->busSchedule
                               ->where('bus_id', $data['bus_id'])
                               ->where('status', '!=', 2)
                               ->get();
            if (count($duplicate_data) == 0) {
                $this->bus = $this->bus->find($data['bus_id']);
                $this->bus->running_cycle = $data['running_cycle'];
                $this->bus->update();
                $this->busSchedule->bus_id = $data['bus_id'];
                $this->busSchedule->running_cycle = $data['running_cycle'];
                $this->busSchedule->created_by = $data['created_by'];
                $this->busSchedule->status = 0;
                $this->busSchedule->save();
                $busScheduleDateModels = [];
                $entryDate = $data['entry_date'];
                $busScheduleDate = new BusScheduleDate();
                $busScheduleDate->bus_schedule_id = $this->busSchedule->id;
                for ($dateCount = 0;$dateCount < 30;$dateCount++) {

                    $busScheduleDate = new BusScheduleDate();
                    $busScheduleDate->bus_schedule_id = $this->busSchedule->id;
                    if ($dateCount != 0) {
                        $entryDate = strtotime("+".$data['running_cycle']."day", strtotime($entryDate));
                    } else {
                        $entryDate = strtotime($entryDate);
                    }
                    $entryDate = date("Y-m-d", $entryDate);
                    $busScheduleDate->entry_date = $entryDate;
                    $busScheduleDate->created_by = $data['created_by'];
                    $busScheduleDate->status = 1;
                    $busScheduledateModels[] =  $busScheduleDate;
                }
                $this->busSchedule->busScheduleDate()->saveMany($busScheduledateModels);
                return $busScheduledateModels;
            } else {
                return 'Bus Schedule Already Exist';
            }

        } else {
            return 'Can Not Add Old Date';
        }


    }

    /**
     * Update Schedule
     *
     * @param $data
     * @return busschedule
     */
    public function update($data, $id)
    {

        if ($data['entry_date'] >= date("Y-m-d")) {
            $this->bus = $this->bus->find($data['bus_id']);
            $this->bus->running_cycle = $data['running_cycle'];
            $this->bus->update();
            $this->busSchedule = $this->busSchedule->find($id);
            $this->busSchedule->running_cycle = $data['running_cycle'];
            $this->busSchedule->update();
            //TOD Latter,Write Enhanced Query
            $this->busSchedule->BusScheduleDate()->delete();
            $busScheduleDateModels = [];
            $entryDate = $data['entry_date'];
            $busScheduleDate = new BusScheduleDate();
            $busScheduleDate->bus_schedule_id = $this->busSchedule->id;
            $busScheduleDate->entry_date = $entryDate;
            for ($dateCount = 0;$dateCount < 30;$dateCount++) {
                $busScheduleDate = new BusScheduleDate();
                $busScheduleDate->bus_schedule_id = $this->busSchedule->id;
                if ($dateCount != 0) {
                    $entryDate = strtotime("+".$data['running_cycle']."day", strtotime($entryDate));
                } else {
                    $entryDate = strtotime($entryDate);
                }
                $entryDate = date("Y-m-d", $entryDate);
                $busScheduleDate->entry_date = $entryDate;
                $busScheduleDate->created_by = $data['created_by'];
                $busScheduleDate->status = 1;
                $busScheduledateModels[] =  $busScheduleDate;
            }
            $this->busSchedule->busScheduleDate()->saveMany($busScheduledateModels);
            return $busScheduledateModels;
        } else {
            return 'Can Not Add Old Date';
        }

    }

    public function delete($id)
    {
        if ($this->busSchedule->where('id', $id)->exists()) {
            $this->busScheduleDate->where("bus_schedule_id", $id)->delete();
            $busschedule = $this->busSchedule->find($id);
            $busschedule->status = 2;
            $busschedule->update();
            return $busschedule;
        }
    }
    public function changeStatus($id)
    {
        $post = $this->busSchedule->find($id);
        if ($post->status == 0) {
            $post->status = 1;
        } elseif ($post->status == 1) {
            $post->status = 0;
        }
        $post->update();
        return $post;
    }

    public function unscheduledbuslist()
    {
        $data = $this->bus
                ->with('busOperator')
                ->where("status", 1)
                ->whereNotIn('id', $this->busSchedule->select('bus_id'))->get();

        return $data;
    }


    //////////// sync bus seat count every 5 min cron job ////////////

    public function syncBusSeatCount()
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 600);

        try {

            $startDate = date('Y-m-d');
            $endDate   = date('Y-m-d', strtotime('+30 days'));

            $busIds = DB::table('ticket_price as tp')

                ->join('bus as b', 'b.id', '=', 'tp.bus_id')

                ->where('tp.status', 1)

                ->where('b.status', 1)

                ->distinct()

                ->pluck('tp.bus_id');


            foreach ($busIds as $busId) {

               
                $baseTotalSeat = DB::table('bus_seats')

                    ->where('bus_id', $busId)

                    ->where('status', 1)

                    ->where(function ($q) {

                        $q->where(function ($qq) {

                            // Normal Seat
                            $qq->whereNull('type')
                                ->whereNull('operation_date')
                                ->where('duration', 0);

                        })

                        ->orWhere(function ($qq) {

                            // Extra Permanent Open Seat
                            $qq->whereNull('type')
                                ->whereNull('operation_date')
                                ->where('duration', '>', 0);

                        });

                    })

                    ->distinct('seats_id')

                    ->count('seats_id');

                $tempOpenSeats = DB::table('bus_seats')

                    ->select(
                        'operation_date',
                        DB::raw('COUNT(DISTINCT seats_id) as open_seat')
                    )

                    ->where('bus_id', $busId)

                    ->where('status', 1)

                    ->where('type', 1)

                    ->whereNotNull('operation_date')

                    ->groupBy('operation_date')

                    ->get()

                    ->keyBy('operation_date');


                $blockedSeats = DB::table('bus_seats')

                    ->select(
                        'operation_date',
                        DB::raw('COUNT(DISTINCT seats_id) as blocked_seat')
                    )

                    ->where('bus_id', $busId)

                    ->where('status', 1)

                    ->where(function ($q) {

                        $q->where(function ($qq) {

                            // Temporary Seat Block
                            $qq->where('type', 2)
                                ->whereNotNull('operation_date');

                        })

                        ->orWhere(function ($qq) {

                            // Extra Seat Block
                            $qq->whereNull('type')
                                ->where('duration', 0)
                                ->whereNotNull('operation_date');

                        });

                    })

                    ->groupBy('operation_date')

                    ->get()

                    ->keyBy('operation_date');



                $seatCounts = DB::table('bus_seat_count as bsc')

                    ->join('ticket_price as tp', 'tp.id', '=', 'bsc.ticket_price_id')

                    ->join('bus as b', 'b.id', '=', 'tp.bus_id')

                    ->select(
                        'bsc.id',
                        'bsc.ticket_price_id',
                        'bsc.journey_date'
                    )

                    ->where('b.status', 1)

                    ->where('tp.status', 1)

                    ->where('tp.bus_id', $busId)

                    ->whereBetween('bsc.journey_date', [
                        $startDate,
                        $endDate
                    ])

                    ->get();


                foreach ($seatCounts as $row) {

                    $journeyDate = $row->journey_date;


                    $totalSeat = $baseTotalSeat;

                    if (isset($tempOpenSeats[$journeyDate])) {

                        $totalSeat += $tempOpenSeats[$journeyDate]->open_seat;
                    }



                    $blockedSeat = 0;

                    if (isset($blockedSeats[$journeyDate])) {

                        $blockedSeat = $blockedSeats[$journeyDate]->blocked_seat;
                    }



                    $bookedSeat = DB::table('booking as b')

                        ->join('bus as bus_main', 'bus_main.id', '=', 'b.bus_id')

                        ->join('booking_detail as bd', function ($join) {

                            $join->on('bd.booking_id', '=', 'b.id')
                                ->where('bd.status', 1);

                        })

                        ->join('bus_location_sequence as req_start', function ($join) {

                            $join->on('req_start.bus_id', '=', 'b.bus_id')
                                ->on('req_start.location_id', '=', 'b.source_id');

                        })

                        ->join('bus_location_sequence as req_end', function ($join) {

                            $join->on('req_end.bus_id', '=', 'b.bus_id')
                                ->on('req_end.location_id', '=', 'b.destination_id');

                        })

                        ->join('ticket_price as tp2', function ($join) use ($row) {

                            $join->on('tp2.bus_id', '=', 'b.bus_id')
                                ->where('tp2.id', '=', $row->ticket_price_id);

                        })

                        ->join('bus_location_sequence as seg_start', function ($join) {

                            $join->on('seg_start.bus_id', '=', 'tp2.bus_id')
                                ->on('seg_start.location_id', '=', 'tp2.source_id');

                        })

                        ->join('bus_location_sequence as seg_end', function ($join) {

                            $join->on('seg_end.bus_id', '=', 'tp2.bus_id')
                                ->on('seg_end.location_id', '=', 'tp2.destination_id');

                        })

                        ->where('bus_main.status', 1)

                        ->where('b.status', 1)

                        ->whereDate('b.journey_dt', $journeyDate)

                        ->where('seg_start.sequence', '<', DB::raw('req_end.sequence'))

                        ->where('req_start.sequence', '<', DB::raw('seg_end.sequence'))

                        ->count();



                    $holdSeat = DB::table('booking as b')

                        ->join('bus as bus_main', 'bus_main.id', '=', 'b.bus_id')

                        ->join('booking_detail as bd', 'bd.booking_id', '=', 'b.id')

                        ->join('bus_location_sequence as req_start', function ($join) {

                            $join->on('req_start.bus_id', '=', 'b.bus_id')
                                ->on('req_start.location_id', '=', 'b.source_id');

                        })

                        ->join('bus_location_sequence as req_end', function ($join) {

                            $join->on('req_end.bus_id', '=', 'b.bus_id')
                                ->on('req_end.location_id', '=', 'b.destination_id');

                        })

                        ->join('ticket_price as tp2', function ($join) use ($row) {

                            $join->on('tp2.bus_id', '=', 'b.bus_id')
                                ->where('tp2.id', '=', $row->ticket_price_id);

                        })

                        ->join('bus_location_sequence as seg_start', function ($join) {

                            $join->on('seg_start.bus_id', '=', 'tp2.bus_id')
                                ->on('seg_start.location_id', '=', 'tp2.source_id');

                        })

                        ->join('bus_location_sequence as seg_end', function ($join) {

                            $join->on('seg_end.bus_id', '=', 'tp2.bus_id')
                                ->on('seg_end.location_id', '=', 'tp2.destination_id');

                        })

                        ->where('bus_main.status', 1)

                        ->where('b.status', 4)

                        ->whereDate('b.journey_dt', $journeyDate)

                        ->where('seg_start.sequence', '<', DB::raw('req_end.sequence'))

                        ->where('req_start.sequence', '<', DB::raw('seg_end.sequence'))

                        ->count();


                    $availableSeat = max(
                        $totalSeat
                        - $bookedSeat
                        - $blockedSeat
                        - $holdSeat,
                        0
                    );



                    DB::table('bus_seat_count')

                        ->where('id', $row->id)

                        ->update([

                            'total_seat'     => $totalSeat,

                            'booked_seat'    => $bookedSeat,

                            'blocked_seat'   => $blockedSeat,

                            'hold_seat'      => $holdSeat,

                            'available_seat' => $availableSeat,

                            'updated_at'   => now(),

                            'updated_by'     => 'cron'

                        ]);

                }

            }

            Log::info('Bus Seat Count Sync Completed');

            return true;

        } catch (\Exception $e) {

            Log::error('Bus Seat Count Sync Failed : '.$e->getMessage());

            return false;
        }
    }
}
