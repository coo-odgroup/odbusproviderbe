<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\Booking;
use App\Models\Location;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

/*Priyadarshi to Review*/

class AgentCancelTicketReportRepository
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
        $bus_operator_id = $request->bus_operator_id;
        $payment_id = $request->payment_id;
        $date_type = $request->date_type;
        $source_id = $request->source_id;
        $destination_id = $request->destination_id;

        $start_date = $request->rangeFromDate;
        $end_date = $request->rangeToDate;
        $user_id = $request->user_id;

        // Default current month
        if (empty($start_date) && empty($end_date)) {
            $start_date = now()->startOfMonth()->format('Y-m-d');
            $end_date = now()->endOfMonth()->format('Y-m-d');
        }



        $data = $this->booking->with(
            'BookingDetail.BusSeats.seats',
            'BookingDetail.BusSeats.ticketPrice',
            'Bus'
        )
            ->with('bus.busstoppage')
            ->where('status', 2)
            ->where('user_id', $user_id);
        if ($paginate == 'all') {
            $paginate = Config::get('constants.ALL_RECORDS');
        } elseif ($paginate == null) {
            $paginate = 10;
        }

        if (!empty($bus_operator_id)) {
            $data = $data->whereHas('bus.busOperator', function ($query) use ($bus_operator_id) {
                $query->where('id', $bus_operator_id);
            });
        }

        if (!empty($payment_id)) {
            $data = $data->whereHas('CustomerPayment', function ($query) use ($payment_id) {
                $query->where('razorpay_id', $payment_id);
            });
        }

        if (!empty($source_id) && !empty($destination_id)) {
            $data = $data->where('source_id', $source_id)->where('destination_id', $destination_id);
        }



        // Default month filter already set above if dates are empty

        if ($date_type == 'journey') {

            if ($start_date == $end_date) {

                $data = $data
                    ->whereDate('journey_dt', $start_date)
                    ->orderByDesc('journey_dt');
            } else {

                $data = $data
                    ->whereBetween('journey_dt', [
                        $start_date,
                        $end_date
                    ])
                    ->orderByDesc('journey_dt');
            }
        } else {

            // booking date (default)

            if ($start_date == $end_date) {

                $data = $data
                    ->whereDate('created_at', $start_date)
                    ->orderByDesc('created_at');
            } else {

                $data = $data
                    ->whereBetween('created_at', [
                        $start_date . ' 00:00:00',
                        $end_date . ' 23:59:59'
                    ])
                    ->orderByDesc('created_at');
            }
        }

        $data = $data->paginate($paginate);

        if ($data) {
            foreach ($data as $key => $v) {

                $v['from_location'] = $this->location->where('id', $v->source_id)->get();
                $v['to_location'] = $this->location->where('id', $v->destination_id)->get();

                $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get();
                $stoppages['source'] = [];
                $stoppages['destination'] = [];
                if (count($stoppage) > 0) {
                    foreach ($stoppage[0]['ticketPrice'] as $k => $a) {
                        $stoppages['source'][$k] = $this->location->select('name')->where('id', $a->source_id)->get();
                        $stoppages['destination'][$k] = $this->location->select('name')->where('id', $a->destination_id)->get();
                    }
                }
                // foreach ($stoppage[0]['ticketPrice'] as $k => $a)
                //  {
                //      $stoppages['source'][$k]=$this->location->where('id', $a->source_id)->get();
                //      $stoppages['destination'][$k]=$this->location->where('id', $a->destination_id)->get();
                //  }
                $v['source'] = $stoppages['source'];
                $v['destination'] = $stoppages['destination'];
            }
        }


        return array("count" => $data->count(), "total" => $data->total(), "data" => $data);
    }
}
