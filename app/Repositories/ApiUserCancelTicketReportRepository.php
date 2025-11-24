<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\Booking;
use App\Models\Location;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

/*Priyadarshi to Review*/
class ApiUserCancelTicketReportRepository
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
        $user_id = $request->apiUser;
        $pnr = $request->pnr;
        $date_type = $request->date_type;
        $source_id = $request->source_id;
        $destination_id = $request->destination_id;

        $start_date  =  $request->rangeFromDate;
        $end_date  =  $request->rangeToDate;

        $data = $this->booking->with(
            'BookingDetail.BusSeats.seats',
            'BookingDetail.BusSeats.ticketPrice',
            'Bus',
            'Users',
            'User',
            'CustomerPayment'
        )
                             ->with('bus.busstoppage')
                             ->where('app_type', 'CLNTWEB')
                             ->where('status', 2);
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

        if (!empty($pnr)) {
            $data = $data->where('pnr', $pnr);
        }

        if (!empty($user_id)) {
            $data = $data->where('user_id', $user_id);
        }
     

        if (!empty($source_id) && !empty($destination_id)) {
            $data = $data->where('source_id', $source_id)->where('destination_id', $destination_id);
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
            $data = $data->where('journey_dt', date('Y-m-d'))->orderBy('journey_dt', 'DESC');
        } elseif ($date_type == 'journey' && $start_date != null && $end_date != null) {
            if ($start_date == $end_date) {
                $data = $data->where('journey_dt', 'like', '%'.$start_date.'%')
                        ->orderBy('journey_dt', 'DESC');
            } else {
                $start_dt = Carbon::parse($start_date)->startOfDay()->toDateTimeString();
                $start_dt = date('Y-m-d', strtotime($start_dt));
                $end_dt = Carbon::parse($end_date)->endOfDay()->toDateTimeString();
                $end_dt = date('Y-m-d', strtotime($end_dt));
                $data = $data->whereBetween('journey_dt', [$start_dt, $end_dt])
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
}
