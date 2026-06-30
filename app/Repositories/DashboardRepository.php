<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\Booking;
use App\Models\Location;
use App\Models\BusOperator;
use App\Models\User;
use DB;
// use App\Models\TicketPrice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

/*Priyadarshi to Review*/

class DashboardRepository
{
    protected $seatOpen;
    protected $booking;
    protected $location;
    protected $busoperator;
    protected $users;


    public function __construct(Booking $booking, Bus $bus, BusOperator $busoperator, Location $location, User $users)
    {
        $this->booking = $booking;
        $this->bus = $bus;
        $this->location = $location;
        $this->busoperator = $busoperator;
        $this->users = $users;
    }

    public function getAll($request)
    {
        // log::info($request); exit;
        $dt_month = date('Y-m-d', strtotime('today - 30 days'));
        $dt_week = date('Y-m-d', strtotime('today - 7 days'));
        $current_month = date('Y-m');
        $data_arr = array();
        $current_date = date('Y-m-d');
        $today_data = $this->booking->where('status', '1');
        $web_booking = $this->booking->where('status', '1');
        $app_booking = $this->booking->where('status', '1');
        $mob_booking = $this->booking->where('status', '1');
        $upcoming_data = $this->booking->where('status', '1');
        $bus_data = $this->bus->where('status', '1');
        $operator_data = $this->busoperator->where('status', '1');
        $booking_data = $this->booking->selectRaw('sum(odbus_charges) as odbus_amount')->where('status', '1');
        $sales_data = $this->booking->selectRaw('sum(total_fare) as today_amount')->where('status', '1');

        $calcel_payable_amount = $this->booking->selectRaw('sum(payable_amount) as today_payable_amount')->where('status', '2');
        $calcel_refund_amount = $this->booking->selectRaw('sum(refund_amount) as today_refund_amount')->where('status', '2');

        if ($request['USER_BUS_OPERATOR_ID'] != "") {
            $busOperatorId = $request['USER_BUS_OPERATOR_ID'];

            $today_data = $today_data->whereHas('bus', function ($query) use ($busOperatorId) {
                $query->where('bus_operator_id', $busOperatorId);
            });
            $upcoming_data = $upcoming_data->whereHas('bus', function ($query) use ($busOperatorId) {
                $query->where('bus_operator_id', $busOperatorId);
            });
            $bus_data = $bus_data->where('bus_operator_id', $busOperatorId);
            $operator_data = $operator_data->where('id', $busOperatorId);
            $booking_data = $booking_data->whereHas('bus', function ($query) use ($busOperatorId) {
                $query->where('bus_operator_id', $busOperatorId);
            });
            $sales_data = $sales_data->whereHas('bus', function ($query) use ($busOperatorId) {
                $query->where('bus_operator_id', $busOperatorId);
            });
        }
        switch ($request['rangeFor']) {
            case 'This Month':
                $today_web_booking = $web_booking->where('created_at', 'Like', $current_month . '%')->where('app_type', 'WEB')->get();
                $today_app_booking = $app_booking->where('created_at', 'Like', $current_month . '%')->where('app_type', 'ANDROID')->get();
                $today_mob_booking = $mob_booking->where('created_at', 'Like', $current_month . '%')->where('app_type', 'MOB')->get();


                $today_data = $today_data->where('created_at', 'Like', $current_month . '%')->get();
                $upcoming_data = $upcoming_data->where('journey_dt', '>', $current_date)->get();
                $bus_data = $bus_data->get();
                $operator_data = $operator_data->get();
                $booking_data = $booking_data->where('created_at', 'Like', $current_month . '%');
                $sales_data = $sales_data->where('created_at', 'Like', $current_month . '%');
                $calcel_payable_amount = $calcel_payable_amount->where('created_at', 'Like', $current_month . '%')->get();
                $calcel_refund_amount = $calcel_refund_amount->where('created_at', 'Like', $current_month . '%')->get();
                break;

            case 'This Week':

                $today_web_booking = $web_booking->whereBetween('created_at', [$dt_week, $current_date])->where('app_type', 'WEB')->get();
                $today_app_booking = $app_booking->whereBetween('created_at', [$dt_week, $current_date])->where('app_type', 'ANDROID')->get();
                $today_mob_booking = $mob_booking->whereBetween('created_at', [$dt_week, $current_date])->where('app_type', 'MOB')->get();


                $today_data = $today_data->whereBetween('created_at', [$dt_week, $current_date])->get();
                $upcoming_data = $upcoming_data->where('journey_dt', '>', $current_date)->get();
                $bus_data = $bus_data->get();
                $operator_data = $operator_data->get();
                $booking_data = $booking_data->whereBetween('created_at', [$dt_week, $current_date]);
                $sales_data = $sales_data->whereBetween('created_at', [$dt_week, $current_date]);
                $calcel_payable_amount = $calcel_payable_amount->whereBetween('created_at', [$dt_week, $current_date])->get();
                $calcel_refund_amount = $calcel_refund_amount->whereBetween('created_at', [$dt_week, $current_date])->get();
                break;

            case 'Today':
                $today_web_booking = $web_booking->where('created_at', 'like', $current_date . '%')->where('app_type', 'WEB')->get();
                $today_app_booking = $app_booking->where('created_at', 'like', $current_date . '%')->where('app_type', 'ANDROID')->get();
                $today_mob_booking = $mob_booking->where('created_at', 'like', $current_date . '%')->where('app_type', 'MOB')->get();
                $today_data = $today_data->where('created_at', 'like', $current_date . '%')->get();
                $upcoming_data = $upcoming_data->where('journey_dt', '>', $current_date)->get();
                $bus_data = $bus_data->get();
                $operator_data = $operator_data->get();
                $booking_data = $booking_data->where('created_at', 'like', $current_date . '%');
                $sales_data = $sales_data->where('created_at', 'like', $current_date . '%');
                $calcel_payable_amount = $calcel_payable_amount->where('created_at', 'like', $current_date . '%')->get();
                $calcel_refund_amount = $calcel_refund_amount->where('created_at', 'like', $current_date . '%')->get();
                break;

            case 'Custom Range':

                $today_web_booking = $web_booking->whereBetween('created_at', [$request['rangeFrom'], $request['rangeTo']])->where('app_type', 'WEB')->get();
                $today_app_booking = $app_booking->whereBetween('created_at', [$request['rangeFrom'], $request['rangeTo']])->where('app_type', 'ANDROID')->get();
                $today_mob_booking = $mob_booking->whereBetween('created_at', [$request['rangeFrom'], $request['rangeTo']])->where('app_type', 'MOB')->get();


                $today_data = $today_data->whereBetween('created_at', [$request['rangeFrom'], $request['rangeTo']])->get();
                $upcoming_data = $upcoming_data->whereBetween('journey_dt', [$request['rangeFrom'], $request['rangeTo']])->get();
                $bus_data = $bus_data->where('created_at', [$request['rangeFrom'], $request['rangeTo']])->get();
                $operator_data = $operator_data->where('created_at', [$request['rangeFrom'], $request['rangeTo']])->get();
                $booking_data = $booking_data->whereBetween('created_at', [$request['rangeFrom'], $request['rangeTo']]);
                $sales_data = $sales_data->whereBetween('created_at', [$request['rangeFrom'], $request['rangeTo']]);
                $calcel_payable_amount = $calcel_payable_amount->whereBetween('created_at', [$request['rangeFrom'], $request['rangeTo']])->get();
                $calcel_refund_amount = $calcel_refund_amount->whereBetween('created_at', [$request['rangeFrom'], $request['rangeTo']])->get();
                break;

            default:
                $today_web_booking = $web_booking->where('app_type', 'WEB')->get();
                $today_app_booking = $app_booking->where('app_type', 'ANDROID')->get();
                $today_mob_booking = $mob_booking->where('app_type', 'MOB')->get();

                $today_data = $today_data->get();
                $upcoming_data = $upcoming_data->where('journey_dt', '>', $current_date)->get();
                $bus_data = $bus_data->get();
                $operator_data = $operator_data->get();
                $booking_data = $booking_data;
                $sales_data = $sales_data;
                $calcel_payable_amount = $calcel_payable_amount->get();
                $calcel_refund_amount = $calcel_refund_amount->get();
                break;
        }



        $today_data = count($today_data);
        $upcoming_data = count($upcoming_data);
        $active_bus_data = count($bus_data);
        $active_operator_data = count($operator_data);
        $data_arr['today_pnr'] = $today_data;
        $data_arr['upcoming_pnr'] = $upcoming_data;
        $data_arr['active_bus'] = $active_bus_data;
        $data_arr['active_operator'] = $active_operator_data;
        $data_arr['web_booking'] = $today_web_booking;
        $data_arr['app_booking'] = $today_app_booking;
        $data_arr['mobile_booking'] = $today_mob_booking;
        $data_arr['booking_profit'] = $booking_data->get();
        $data_arr['sales_data'] = $sales_data->get();
        $data_arr['cancellation_profit'] =  @$calcel_payable_amount[0]->today_payable_amount - @$calcel_refund_amount[0]->today_refund_amount;

        return $data_arr;
    }

    public function getAllAgentData($request)
    {

        $dt_month = date('Y-m-d', strtotime('today - 30 days'));
        $dt_week = date('Y-m-d', strtotime('today - 7 days'));
        $current_month = date('Y-m');
        $data_arr = array();
        $current_date = date('Y-m-d');

        $today_data = $this->booking
            ->where('status', '1')
            ->where('user_id', $request->USERID);

        $upcoming_data = $this->booking
            ->where('status', '1')
            ->where('user_id', $request->USERID);

        $agent_commission = $this->booking
            ->selectRaw('
            IFNULL(SUM(agent_commission),0) as odbus_amount
        ')
            ->where('status', '1')
            ->where('user_id', $request->USERID);

        $customer_commission = $this->booking
            ->selectRaw('
            IFNULL(SUM(customer_comission),0) as customer_amount
        ')
            ->where('status', '1')
            ->where('user_id', $request->USERID);

        $sales_data = $this->booking
            ->selectRaw('
            IFNULL(SUM(total_fare),0) as today_amount
        ')
            ->where('status', '1')
            ->where('user_id', $request->USERID);


        // ALL
        if ($request['rangeFor'] == 'All') {

            $today_data = $today_data->get();

            $upcoming_data = $upcoming_data
                ->where('journey_dt', '>', $current_date)
                ->get();

            $booking_data = $agent_commission;

            $customer_data = $customer_commission;

            $sales_data = $sales_data;
        }


        // TODAY
        if ($request['rangeFor'] == 'Today') {

            $today_data = $today_data
                ->where('created_at', 'LIKE', $current_date . '%')
                ->get();

            $upcoming_data = $upcoming_data
                ->where('journey_dt', '>', $current_date)
                ->get();

            $booking_data = $agent_commission
                ->where('created_at', 'LIKE', $current_date . '%');

            $customer_data = $customer_commission
                ->where('created_at', 'LIKE', $current_date . '%');

            $sales_data = $sales_data
                ->where('created_at', 'LIKE', $current_date . '%');
        }


        // THIS WEEK
        if ($request['rangeFor'] == 'This Week') {

            $today_data = $today_data
                ->whereBetween('created_at', [$dt_week, $current_date])
                ->get();

            $upcoming_data = $upcoming_data
                ->where('journey_dt', '>', $current_date)
                ->get();

            $booking_data = $agent_commission
                ->whereBetween('created_at', [$dt_week, $current_date]);

            $customer_data = $customer_commission
                ->whereBetween('created_at', [$dt_week, $current_date]);

            $sales_data = $sales_data
                ->whereBetween('created_at', [$dt_week, $current_date]);
        }


        // THIS MONTH
        if ($request['rangeFor'] == 'This Month') {

            $today_data = $today_data
                ->where('created_at', 'LIKE', $current_month . '%')
                ->get();

            $upcoming_data = $upcoming_data
                ->where('journey_dt', '>', $current_date)
                ->get();

            $booking_data = $agent_commission
                ->where('created_at', 'LIKE', $current_month . '%');

            $customer_data = $customer_commission
                ->where('created_at', 'LIKE', $current_month . '%');

            $sales_data = $sales_data
                ->where('created_at', 'LIKE', $current_month . '%');
        }

        // CUSTOM
        if ($request['rangeFor'] == 'Custom') {

            $fromDate = $request['rangeFrom'] . ' 00:00:00';
            $toDate   = $request['rangeTo'] . ' 23:59:59';

            $today_data = $today_data
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->get();

            $upcoming_data = $upcoming_data
                ->where('journey_dt', '>', $current_date)
                ->get();
            $booking_data = $agent_commission->whereBetween('created_at', [$fromDate, $toDate]);
            $customer_data = $customer_commission->whereBetween('created_at', [$fromDate, $toDate]);
            $sales_data = $sales_data->whereBetween('created_at', [$fromDate, $toDate]);
        }




        $today_data = count($today_data);
        $upcoming_data = count($upcoming_data);
        $data_arr['today_pnr'] = $today_data;
        $data_arr['upcoming_pnr'] = $upcoming_data;
        $data_arr['booking_profit'] = $booking_data->get();
        $data_arr['customer_profit'] = $customer_data->get();
        $data_arr['cancellation_profit'] = 1641;
        $data_arr['sales_data'] = $sales_data->get();

        return $data_arr;
    }


    public function getBookings($request)
    {
        $currentDate = date('Y-m-d');
        $currentMonth = date('Y-m');
        $weekDate = date('Y-m-d', strtotime('today - 7 days'));

        if ($request->ROLE_ID == 1) {
            $query = $this->booking
                ->where('status', '1');
        } else {

            $query = $this->booking
                ->where('status', '1')
                ->where('user_id', $request->USERID);
        }

        // TODAY
        if ($request->rangeFor == 'Today') {
            $query->whereDate('created_at', $currentDate);
        }

        // THIS WEEK
        if ($request->rangeFor == 'This Week') {

            $query->whereBetween('created_at', [
                $weekDate,
                $currentDate
            ]);
        }

        // THIS MONTH
        if ($request->rangeFor == 'This Month') {
            $query->where('created_at', 'LIKE', $currentMonth . '%');
        }

        // CUSTOM
        if (
            $request->rangeFor == 'Custom' &&
            !empty($request->rangeFrom) &&
            !empty($request->rangeTo)
        ) {

            $query->whereBetween('created_at', [
                $request->rangeFrom . ' 00:00:00',
                $request->rangeTo . ' 23:59:59'
            ]);
        }

        $booking_data = $query
            ->orderBy('id', 'DESC')
            ->limit(100)
            ->get();

        $data_arr = [];

        foreach ($booking_data as $key => $v) {

            $passengerNames = DB::table('booking_detail')
                ->where('booking_id', $v->id)
                ->pluck('passenger_name')
                ->toArray();

            $bus = DB::table('bus')
                ->where('id', $v->bus_id)
                ->select('name', 'bus_number')
                ->first();

            $from = $this->location
                ->where('id', $v->source_id)
                ->first();

            $to = $this->location
                ->where('id', $v->destination_id)
                ->first();

            if (count($passengerNames) > 1) {
                $displayPassenger = $passengerNames[0] . ' +' . (count($passengerNames) - 1);
            } else {
                $displayPassenger = $passengerNames[0] ?? '-';
            }



            $data_arr[$key] = [
                'pnr' => $v->pnr,
                'bus_name' => $bus ? $bus->name : '-',
                'bus_number' => $bus ? $bus->bus_number : '-',
                'transaction_id' => $v->transaction_id,
                'journey_dt' => $v->journey_dt,
                'route' => ($from ? $from->name : '') . ' - ' . ($to ? $to->name : ''),
                'passenger_name' => $displayPassenger,
                'all_passengers' => implode(', ', $passengerNames),
                'agent_commission' => (float) $v->agent_commission,
                'customer_comission' => (float) $v->customer_comission,
                'amount' => $v->total_fare,
                'source_name' => $from ? $from->name : '',
                'booking_date' => $v->created_at,
                'destination_name' => $to ? $to->name : '',
            ];
        }

        return $data_arr;
    }


    public function lastWalletTransactions($request)
    {
        return DB::table('agent_wallet')
            ->where('user_id', $request->user_id)
            ->orderBy('id', 'DESC')
            ->limit(10)
            ->get([
                'transaction_id',
                'payment_via',
                'amount',
                'transaction_type',
                'type',
                'balance',
                'remarks',
                'created_at'
            ]);
    }

    public function getOperatorName($busId)
    {
        $records = $this->bus
            ->with('busOperator')
            ->where('id', $busId)->get();
        return $records;
    }


    public function getOperator()
    {
        $dt = date('Y-m-d', strtotime('today - 30 days'));
        // $operator_data = $this->booking->where('journey_dt','>',$dt)->get();

        $busIds = $this->booking
            ->select('bus_id', (DB::raw('count(*) as count')))
            ->selectRaw('sum(owner_fare) as amount')
            ->whereDate('created_at', '>', $dt)
            ->groupBy('bus_id')
            ->where('status', '1')
            ->orderBy('count', 'DESC')
            ->limit(10)
            ->get();

        if ($busIds->isEmpty()) {
            return "No booking exist to filter top operator";
        } else {
            foreach ($busIds as $busId) {
                $opId = $busId->bus_id;
                $count = $busId->count;
                $opName = $this->getOperatorName($opId);
                $topOperators[] = array(
                    "operatorName" => $opName,
                    "count" => $count,
                    "amount" => $busId->amount
                );
            }
        }
        // log::info($topOperators);
        return $topOperators;
    }

    public function getticketstatics()
    {
        return "WORK IN PROGRESS";
    }


    public function getbookingbydevice()
    {
        return "WORK IN PROGRESS";
    }


    public function getpnrstatics($request)
    {
        if ($request['USER_BUS_OPERATOR_ID'] == "") {
            $pnr_data = $this->booking
                ->select('journey_dt')
                ->selectRaw('count(*) as pnr_count')
                ->groupBy('journey_dt')
                ->orderBy('journey_dt', 'DESC')
                ->where('status', '1')
                ->limit('7');
        } else {
            $Operator_id = $request['USER_BUS_OPERATOR_ID'];
            $pnr_data = $this->booking
                ->select('journey_dt')
                ->selectRaw('count(*) as pnr_count')
                ->groupBy('journey_dt')
                ->orderBy('journey_dt', 'DESC')
                ->where('status', '1')
                ->whereHas('bus', function ($query) use ($Operator_id) {
                    $query->where('bus_operator_id', $Operator_id);
                })
                ->limit('7');
        }
        $pnr_data = $pnr_data->get();
        $data_arr = array();

        $date_arr = array();
        $pnr_count = array();

        foreach ($pnr_data as $v) {
            $date_arr[] = $v->journey_dt;
            $pnr_count[] = $v->pnr_count;
        }
        $data_arr['date'] = $date_arr;
        $data_arr['pnr'] = $pnr_count;

        return $data_arr;
    }

    public function getRoute($request)
    {
        // log::info($request);
        $dt = date('Y-m-d', strtotime('today - 30 days'));
        if ($request->ROLE_ID == 1) {
            $route_data = $this->booking
                ->select(['source_id', 'destination_id'])
                ->selectRaw('count(*) as pnr_count')
                ->selectRaw('sum(total_fare) as amount')
                ->groupBy(['source_id', 'destination_id'])
                ->orderBy('pnr_count', 'DESC')
                ->where('journey_dt', '>', $dt)
                ->where('status', '1')
                ->limit(10)
                ->get();
        } else {
            $route_data = $this->booking
                ->select(['source_id', 'destination_id'])
                ->selectRaw('count(*) as pnr_count')
                ->selectRaw('sum(total_fare) as amount')
                ->groupBy(['source_id', 'destination_id'])
                ->orderBy('pnr_count', 'DESC')->where('user_id', $request->USERID)
                ->where('journey_dt', '>', $dt)
                ->where('status', '1')
                ->limit(10)
                ->get();
        }
        $data_arr = array();
        foreach ($route_data as $key => $v) {
            $data_arr[] = $v->toArray();
            $data_arr[$key]['from_location'] = $this->location->where('id', $v->source_id)->get();
            $data_arr[$key]['to_location'] = $this->location->where('id', $v->destination_id)->get();
        }
        return $data_arr;
    }
}
