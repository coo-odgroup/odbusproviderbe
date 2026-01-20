<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\Booking;
use App\Models\Location;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;  


/*Priyadarshi to Review*/
class AgentReportRepository
{
    protected $booking;
    protected $location;
    protected $bus;

    public function __construct(Booking $booking, Location $location, Bus $bus)
    {
        $this->booking = $booking;
        $this->location = $location;
        $this->bus = $bus;
    }

    public function getData($request)
    {

        $paginate = $request->rows_number;
        $pnr = $request->pnr;
        $date_type = $request->date_type;
        $start_date  =  $request->rangeFromDate;
        $end_date  =  $request->rangeToDate;
        $user_id  =  $request->user_id;


        $data = $this->booking->with(
            'BookingDetail.BusSeats.seats',
            'BookingDetail.BusSeats.ticketPrice',
            'Bus',
            'User',
            'Users'
        )
                             ->with('bus.busstoppage')
                             ->where('status', 1)
                             ->where('user_id', '!=', 0)->where('app_type', 'AGENT')
                             ->orderBy('id', 'DESC');
        if ($paginate == 'all') {
            $paginate = Config::get('constants.ALL_RECORDS');
        } elseif ($paginate == null) {
            $paginate = 10 ;
        }

        if (!empty($user_id)) {
            $data = $data->where('user_id', $user_id);
        }


        if (!empty($pnr)) {
            $data = $data->whereHas('User', function ($query) use ($pnr) {$query->where('name', $pnr);});
        }


        if ($date_type == 'booking' && $start_date == null && $end_date == null) {
            $data = $data->orderBy('created_at', 'DESC');
        } elseif ($date_type == 'booking' && $start_date != null && $end_date != null) {
            if ($start_date == $end_date) {
                $data = $data->where('created_at', 'like', '%'.$start_date.'%')
                        ->orderBy('created_at', 'DESC');

            } else {
                $data = $data->whereBetween('created_at', [$start_date, $end_date])
                        ->orderBy('created_at', 'DESC');
            }

        } elseif ($date_type == 'journey' && $start_date == null && $end_date == null) {
            $data = $data->orderBy('journey_dt', 'DESC');
        } elseif ($date_type == 'journey' && $start_date != null && $end_date != null) {
            if ($start_date == $end_date) {
                $data = $data->where('journey_dt', 'like', '%'.$start_date.'%')
                        ->orderBy('journey_dt', 'DESC');
            } else {
                $data = $data-> whereBetween('journey_dt', [$start_date, $end_date])
                       ->orderBy('journey_dt', 'DESC');
            }
        }

        $data = $data->paginate($paginate);


        if ($data) {
            foreach ($data as $v) {

                $v['from_location'] = $this->location->where('id', $v->source_id)->get();
                $v['to_location'] = $this->location->where('id', $v->destination_id)->get();

                $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get();

                $stoppages['source'] = [];
                $stoppages['destination'] = [];
                if (!empty($stoppage)) {
                    foreach ($stoppage[0]['ticketPrice'] as $k => $a) {
                        $stoppages['source'][$k] = $this->location->where('id', $a->source_id)->get();
                        $stoppages['destination'][$k] = $this->location->where('id', $a->destination_id)->get();
                    }
                }
                $v['source'] = $stoppages['source'];
                $v['destination'] = $stoppages['destination'];
            }
        }


        return array(
             "count" => $data->count(),
             "total" => $data->total(),
             "data" => $data
           );

    }

    public function agentcancelreport($request)
    {
        $paginate = $request->rows_number;
        $user_id = $request->user_id;
        $pnr = $request->pnr;
        $date_type = $request->date_type;
        $start_date  =  $request->rangeFromDate;
        $end_date  =  $request->rangeToDate;



        $data = $this->booking->with(
            'BookingDetail.BusSeats.seats',
            'BookingDetail.BusSeats.ticketPrice',
            'Bus',
            'User',
            'Users'
        )
                             ->with('bus.busstoppage')
                             ->where('status', 2)
                             ->where('user_id', '!=', 0)->where('app_type', 'AGENT')
                             ->orderBy('id', 'DESC');
        if ($paginate == 'all') {
            $paginate = Config::get('constants.ALL_RECORDS');
        } elseif ($paginate == null) {
            $paginate = 10 ;
        }

        if (!empty($user_id)) {
            $data = $data->where('user_id', $user_id);
        }

        if (!empty($pnr)) {
            $data = $data->whereHas('User', function ($query) use ($pnr) {$query->where('name', $pnr);});
        }

        if ($date_type == 'booking' && $start_date == null && $end_date == null) {
            $data = $data->orderBy('created_at', 'DESC');
        } elseif ($date_type == 'booking' && $start_date != null && $end_date != null) {
            if ($start_date == $end_date) {
                $data = $data->where('created_at', 'like', '%'.$start_date.'%')
                        ->orderBy('created_at', 'DESC');

            } else {
                $start_dt = Carbon::parse($start_date)->startOfDay()->toDateTimeString();
                $end_dt = Carbon::parse($end_date)->endOfDay()->toDateTimeString();
                $data = $data->whereBetween('created_at', [$start_dt, $end_dt])
                        ->orderBy('created_at', 'DESC');
            }

        } elseif ($date_type == 'journey' && $start_date == null && $end_date == null) {
            $data = $data->orderBy('journey_dt', 'DESC');
        } elseif ($date_type == 'journey' && $start_date != null && $end_date != null) {
            if ($start_date == $end_date) {
                $data = $data->where('journey_dt', 'like', '%'.$start_date.'%')
                        ->orderBy('journey_dt', 'DESC');
            } else {
                $start_dt = Carbon::parse($start_date)->startOfDay()->toDateTimeString();
                $start_dt = date('Y-m-d', strtotime($start_dt));
                $end_dt = Carbon::parse($end_date)->endOfDay()->toDateTimeString();
                $end_dt = date('Y-m-d', strtotime($end_dt));
                $data = $data-> whereBetween('journey_dt', [$start_dt, $end_dt])
                       ->orderBy('journey_dt', 'DESC');
            }
        } elseif ($date_type == 'cancel' && $start_date == null && $end_date == null) {
            $data = $data->where('updated_at', date('Y-m-d'))->orderBy('updated_at', 'DESC');
        } elseif ($date_type == 'cancel' && $start_date != null && $end_date != null) {
            if ($start_date == $end_date) {
                $data = $data->where('updated_at', 'like', '%'.$start_date.'%')
                             ->orderBy('updated_at', 'DESC');
            } else {
                $start_dt = Carbon::parse($start_date)->startOfDay()->toDateTimeString();
                $start_dt = date('Y-m-d', strtotime($start_dt));
                $end_dt = Carbon::parse($end_date)->endOfDay()->toDateTimeString();
                $end_dt = date('Y-m-d', strtotime($end_dt));
                $data = $data->whereBetween('updated_at', [$start_dt, $end_dt])
                             ->orderBy('updated_at', 'DESC');
            }
        }


        $data = $data->paginate($paginate);


        if ($data) {
            foreach ($data as $v) {

                $v['from_location'] = $this->location->where('id', $v->source_id)->get();
                $v['to_location'] = $this->location->where('id', $v->destination_id)->get();

                $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get();
                $stoppages['source'] = [];
                $stoppages['destination'] = [];
                if (!empty($stoppage)) {
                    foreach ($stoppage[0]['ticketPrice'] as $k => $a) {
                        $stoppages['source'][$k] = $this->location->where('id', $a->source_id)->get();
                        $stoppages['destination'][$k] = $this->location->where('id', $a->destination_id)->get();
                    }
                }
                $v['source'] = $stoppages['source'];
                $v['destination'] = $stoppages['destination'];
            }
        }


        return array(
             "count" => $data->count(),
             "total" => $data->total(),
            "data" => $data
           );

    }

    public function agentCommissionreport($request)
    {

        $paginate = $request->rows_number;

        $pnr = $request->pnr;
        $date_type = $request->date_type;
        $start_date  =  $request->rangeFromDate;
        $end_date  =  $request->rangeToDate;

        $data = $this->booking->with(
            'BookingDetail.BusSeats.seats',  
            'BookingDetail.BusSeats.ticketPrice',
            'Bus',
            'User'
        )
                             ->with('bus.busstoppage')
                             ->where('status', 2)
                             ->where('user_id', '!=', 0)->where('app_type', 'AGENT')
                             ->orderBy('id', 'DESC');
        if ($paginate == 'all') {
            $paginate = Config::get('constants.ALL_RECORDS');
        } elseif ($paginate == null) {
            $paginate = 10 ;
        }

        if (!empty($pnr)) {
            $data = $data->whereHas('User', function ($query) use ($pnr) {$query->where('name', $pnr);});
        }

        if ($date_type == 'booking' && $start_date == null && $end_date == null) {
            $data = $data->orderBy('created_at', 'DESC');
        } elseif ($date_type == 'booking' && $start_date != null && $end_date != null) {
            if ($start_date == $end_date) {
                $data = $data->where('created_at', 'like', '%'.$start_date.'%')
                        ->orderBy('created_at', 'DESC');

            } else {
                $data = $data->whereBetween('created_at', [$start_date, $end_date])
                        ->orderBy('created_at', 'DESC');
            }

        } elseif ($date_type == 'journey' && $start_date == null && $end_date == null) {
            $data = $data->orderBy('journey_dt', 'DESC');
        } elseif ($date_type == 'journey' && $start_date != null && $end_date != null) {
            if ($start_date == $end_date) {
                $data = $data->where('journey_dt', 'like', '%'.$start_date.'%')
                        ->orderBy('journey_dt', 'DESC');
            } else {
                $data = $data-> whereBetween('journey_dt', [$start_date, $end_date])
                       ->orderBy('journey_dt', 'DESC');
            }
        }


        $data = $data->paginate($paginate);


        if ($data) {
            foreach ($data as $v) {

                $v['from_location'] = $this->location->where('id', $v->source_id)->get();
                $v['to_location'] = $this->location->where('id', $v->destination_id)->get();

                $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get();
                $stoppages['source'] = [];
                $stoppages['destination'] = [];
                if (!empty($stoppage)) {
                    foreach ($stoppage[0]['ticketPrice'] as $k => $a) {
                        $stoppages['source'][$k] = $this->location->where('id', $a->source_id)->get();
                        $stoppages['destination'][$k] = $this->location->where('id', $a->destination_id)->get();
                    }
                }
                $v['source'] = $stoppages['source'];
                $v['destination'] = $stoppages['destination'];
            }
        }


        return array(
             "count" => $data->count(),
             "total" => $data->total(),
            "data" => $data
           );
    }
  public function redeemableCommissions($request)
{

 Log::info('AGENT COMMISSION FILTER', [
    'user_id' => $request->user_id,
    'start'   => $request->rangeFromDate,
    'end'     => $request->rangeToDate,
]);

    $limit = $request->rows_number ?? 10;
    if ($limit === 'all') {
        $limit = null;
    }

    $query = $this->booking
        ->with(
            'BookingDetail.BusSeats.seats',
            'BookingDetail.BusSeats.ticketPrice',
            'Bus',
            'User'
        )
        ->with('bus.busstoppage')
        ->whereIn('status', [1, 2])
        ->where('app_type', 'AGENT')
        ->where('user_id', $request->user_id)          
        ->where('with_tds_commission', '>', 0)
        ->where('redeem_status', 0);

    $start = $request->rangeFromDate;
    $end   = $request->rangeToDate;

    if (!empty($start) && !empty($end)) {
        if ($start === $end) {
            $query->whereDate('journey_dt', $start);
        } else {
            $query->whereBetween('journey_dt', [$start, $end]);
        }
    } elseif (!empty($start)) {
        $query->whereDate('journey_dt', '>=', $start);
    } elseif (!empty($end)) {
        $query->whereDate('journey_dt', '<=', $end);
    }

    $query->orderBy('journey_dt', 'DESC');

    if ($limit) {
        $data = $query->take($limit)->get();
    } else {
        $data = $query->get();
    }

    foreach ($data as $v) {

        $v['from_location'] = $this->location->where('id', $v->source_id)->get();
        $v['to_location'] = $this->location->where('id', $v->destination_id)->get();

        $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get();
        $stoppages['source'] = [];
        $stoppages['destination'] = [];

        if (!empty($stoppage)) {
            foreach ($stoppage[0]['ticketPrice'] as $k => $a) {
                $stoppages['source'][$k] = $this->location->where('id', $a->source_id)->get();
                $stoppages['destination'][$k] = $this->location->where('id', $a->destination_id)->get();
            }
        }

        $v['source'] = $stoppages['source'];
        $v['destination'] = $stoppages['destination'];
    }

    return [
        'count' => count($data),
        'total' => count($data),
        'data' => $data
    ];
}


}
 