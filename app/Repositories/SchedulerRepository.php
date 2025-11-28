<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\Booking;
use App\Models\Location;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class SchedulerRepository
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

    public function scheduleRecords($request) {
        // Log:: info($request);

        $paginate = $request->rows_number;
        $bus_operator_id = $request->bus_operator_id;
        $pnr = $request->pnr;
        $date_range = $request->date_range;
        $payment_id = $request->payment_id;
        $date_type = $request->date_type;
        $source_id = $request->source_id;
        $destination_id = $request->destination_id;
        $apiUser = $request->apiUser;

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
                             ->with('ClientWallet')
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

        if (!empty($apiUser)) {
            $data = $data->where('origin', $apiUser);
        }

        if (!empty($bus_operator_id)) {
            $data = $data->whereHas('bus.busOperator', function ($query) use ($bus_operator_id) {$query->where('id', $bus_operator_id);});
        }


        if (!empty($payment_id)) {
            $data = $data->whereHas('CustomerPayment', function ($query) use ($payment_id) {$query->where('order_id', $payment_id);});

            // ->whereHas('CustomerPayment', function ($query) use ($payment_id)        {$query->where('razorpay_id', $payment_id );})
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
                // dd($end_dt);
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

        return $data;

        if ($data) {
            foreach ($data as $key => $v) {

                $v['from_location'] = $this->location->where('id', $v->source_id)->get();
                $v['to_location'] = $this->location->where('id', $v->destination_id)->get();

                $stoppages['source'] = [];
                $stoppages['destination'] = [];

                $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get();
                if (count($stoppage) > 0) {
                    foreach ($stoppage[0]['ticketPrice'] as $k => $a) {
                        $stoppages['source'][$k] = $this->location->where('id', $a->source_id)->get();
                        $stoppages['destination'][$k] = $this->location->where('id', $a->destination_id)->get();
                    }
                }

                $v['source'] = $stoppages['source'];
                $v['destination'] = $stoppages['destination'];
            }
        }
        $response = array(
             "count" => $data->count(),
             "total" => $data->total(),
            "data" => $data
           );

        return $response;
    }

    function scheduleRefund($request) {
        $paginate = $request->rows_number;
        $bus_operator_id = $request->bus_operator_id;
        $pnr = $request->pnr;
        $date_range = $request->date_range;
        $payment_id = $request->payment_id;
        $date_type = $request->date_type;
        $source_id = $request->source_id;
        $destination_id = $request->destination_id;
        $apiUser = $request->apiUser;
        $start_date  =  $request->rangeFromDate;
        $end_date  =  $request->rangeToDate;

        $data = $this->booking->with(
            'BookingDetail.BusSeats.seats',
            'BookingDetail.BusSeats.ticketPrice',
            'Bus',
            'Users',
            'User',
            'CustomerPayment'
        )->with('bus.busstoppage')->with('ClientWallet')->where('status', 2);
        return $request;
    }
}
