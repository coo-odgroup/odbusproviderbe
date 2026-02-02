<?php

namespace App\Http\Controllers;

use App\Models\BookingArchive;
use App\Models\BookingDetailArchive;
use App\Models\Bus;
use App\Models\CustomerPaymentArchive;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ArchiveReportController extends Controller
{

    public function archiveCompleteReport(Request $request)
    {
        $year = $request->year ?? "2022";

        if (!empty($request->rangeFromDate)) {
            $year = date('Y', strtotime($request->rangeFromDate));
        } elseif (!empty($request->rangeToDate)) {
            $year = date('Y', strtotime($request->rangeToDate));
        }

        $paginate = $request->rows_number;

        if ($paginate === 'all') {
            $paginate = Config::get('constants.ALL_RECORDS');
        } elseif (empty($paginate)) {
            $paginate = 10;
        }

        BookingDetailArchive::setYear($year);
        CustomerPaymentArchive::setYear($year);

        $booking = (new BookingArchive())->setYear($year);

        $query = $booking->select(
            'id',
            'pnr',
            'transaction_id',
            'user_id',
            'users_id',
            'bus_id',
            'source_id',
            'destination_id',
            'journey_dt',
            'boarding_point',
            'dropping_point',
            'boarding_time',
            'dropping_time',
            'origin',
            'app_type',
            'total_fare',
            'owner_fare',
            'odbus_gst_charges',
            'odbus_gst_amount',
            'odbus_charges',
            'customer_gst_status',
            'gst_invoice_no',
            'customer_gst_percent',
            'customer_gst_number',
            'customer_gst_business_name',
            'customer_gst_business_email',
            'customer_gst_business_address',
            'customer_gst_amount',
            'coupon_code',
            'coupon_discount',
            'payable_amount',
            'transactionFee',
            'additional_owner_fare',
            'additional_special_fare',
            'additional_festival_fare',
            'agent_commission',
            'created_at',
            'api_pnr',
            'bus_name',
            'bus_number'
        )
            ->with([
                'BookingDetail.BusSeats.ticketPrice',
                'BookingDetail.BusSeats.seats',
                'bus.busstoppage',
                'Users',
                'CustomerPayment'
            ])
            ->where('status', 1)
            ->orderBy('journey_dt', 'DESC');

        if (!empty($request->pnr)) {
            $query->where('pnr', $request->pnr);
        }

        if (!empty($request->apiUser)) {
            $query->where('origin', $request->apiUser);
        }

        if (!empty($request->device_type)) {
            $query->where('app_type', $request->device_type);
        }

        if (!empty($request->hasGst)) {
            $query->where('customer_gst_status', 1)
                ->whereNotNull('customer_gst_number');
        }

        if (!empty($request->bus_id)) {
            $query->where('bus_id', $request->bus_id);
        }

        if (!empty($request->bus_operator_id)) {
            $query->whereHas('bus.busOperator', function ($q) use ($request) {
                $q->where('id', $request->bus_operator_id);
            });
        }

        if (!empty($request->payment_id)) {
            $query->whereHas('CustomerPayment', function ($q) use ($request) {
                $q->where('order_id', $request->payment_id)
                    ->where('payment_done', 1);
            });
        }

        if (!empty($request->source_id) && !empty($request->destination_id)) {
            $query->where('source_id', $request->source_id)
                ->where('destination_id', $request->destination_id);
        }

        if ($request->date_type === 'booking') {
            if ($request->rangeFromDate && $request->rangeToDate) {
                $query->whereBetween('created_at', [
                    $request->rangeFromDate,
                    $request->rangeToDate
                ]);
            }
            $query->orderBy('created_at', 'DESC');
        }

        if ($request->date_type === 'journey') {
            if ($request->rangeFromDate && $request->rangeToDate) {
                $query->whereBetween('journey_dt', [
                    $request->rangeFromDate,
                    $request->rangeToDate
                ]);
            }
            $query->orderBy('journey_dt', 'DESC');
        }

        $data = $query->paginate($paginate);

        $totalFare = 0;
        $totalPayable = 0;
        $totalAgentCommission = 0;
        $ownerFare = 0;
        $additionalOwnerFare = 0;
        $totalSeats = 0;

        $today = date('Y-m-d');
        $now   = date('H:i:s');

        foreach ($data as $row) {

            if ($row->journey_dt == $today) {
                $row->journey = ($row->boarding_time < $now) ? 'Over' : 'Upcoming';
            } elseif ($row->journey_dt > $today) {
                $row->journey = 'Upcoming';
            } else {
                $row->journey = 'Over';
            }

            $totalSeats += $row->BookingDetail->count();
            $totalFare += $row->total_fare;
            $totalAgentCommission += $row->agent_commission;
            $totalPayable += $row->payable_amount;
            $ownerFare += $row->owner_fare;
            $additionalOwnerFare += $row->additional_owner_fare;
        }

        $totalReceived = $totalPayable - $totalAgentCommission;
        $ownerFare += $additionalOwnerFare;

        return response()->json([
            'count' => $data->count(),
            'total' => $data->total(),
            'totalSeats' => $totalSeats,
            'totalfare' => number_format($totalFare, 2, '.', ''),
            'totalPayableAmount' => number_format($totalReceived, 2, '.', ''),
            'owner_fare' => number_format($ownerFare, 2, '.', ''),
            'additional_owner_fare' => number_format($additionalOwnerFare, 2, '.', ''),
            'data' => $data
        ]);
    }


    public function archiveCancelReport(Request $request)
    {
        $year = $request->year ?? "2022";

        if (!empty($request->rangeFromDate)) {
            $year = date('Y', strtotime($request->rangeFromDate));
        } elseif (!empty($request->rangeToDate)) {
            $year = date('Y', strtotime($request->rangeToDate));
        }

        $paginate = $request->rows_number;

        if ($paginate === 'all') {
            $paginate = Config::get('constants.ALL_RECORDS');
        } elseif (empty($paginate)) {
            $paginate = 10;
        }

        BookingDetailArchive::setYear($year);
        CustomerPaymentArchive::setYear($year);

        $booking = (new BookingArchive())->setYear($year);

        $query = $booking->select(
            'id',
            'pnr',
            'transaction_id',
            'user_id',
            'users_id',
            'bus_id',
            'source_id',
            'destination_id',
            'journey_dt',
            'boarding_point',
            'dropping_point',
            'boarding_time',
            'dropping_time',
            'origin',
            'app_type',
            'total_fare',
            'owner_fare',
            'odbus_gst_charges',
            'odbus_gst_amount',
            'odbus_charges',
            'customer_gst_status',
            'gst_invoice_no',
            'customer_gst_percent',
            'customer_gst_number',
            'customer_gst_business_name',
            'customer_gst_business_email',
            'customer_gst_business_address',
            'customer_gst_amount',
            'coupon_code',
            'coupon_discount',
            'payable_amount',
            'transactionFee',
            'additional_owner_fare',
            'additional_special_fare',
            'additional_festival_fare',
            'agent_commission',
            'created_at',
            'api_pnr',
            'bus_name',
            'bus_number'
        )
            ->with([
                'BookingDetail.BusSeats.ticketPrice',
                'BookingDetail.BusSeats.seats',
                'bus.busstoppage',
                'Users',
                'CustomerPayment'
            ])
            ->where('status', 2)
            ->orderBy('journey_dt', 'DESC');

        if (!empty($request->pnr)) {
            $query->where('pnr', $request->pnr);
        }

        if (!empty($request->apiUser)) {
            $query->where('origin', $request->apiUser);
        }

        if (!empty($request->device_type)) {
            $query->where('app_type', $request->device_type);
        }

        if (!empty($request->hasGst)) {
            $query->where('customer_gst_status', 1)
                ->whereNotNull('customer_gst_number');
        }

        if (!empty($request->bus_id)) {
            $query->where('bus_id', $request->bus_id);
        }

        if (!empty($request->bus_operator_id)) {
            $query->whereHas('bus.busOperator', function ($q) use ($request) {
                $q->where('id', $request->bus_operator_id);
            });
        }

        if (!empty($request->payment_id)) {
            $query->whereHas('CustomerPayment', function ($q) use ($request) {
                $q->where('order_id', $request->payment_id)
                    ->where('payment_done', 1);
            });
        }

        if (!empty($request->source_id) && !empty($request->destination_id)) {
            $query->where('source_id', $request->source_id)
                ->where('destination_id', $request->destination_id);
        }

        if ($request->date_type === 'booking') {
            if ($request->rangeFromDate && $request->rangeToDate) {
                $query->whereBetween('created_at', [
                    $request->rangeFromDate,
                    $request->rangeToDate
                ]);
            }
            $query->orderBy('created_at', 'DESC');
        }

        if ($request->date_type === 'journey') {
            if ($request->rangeFromDate && $request->rangeToDate) {
                $query->whereBetween('journey_dt', [
                    $request->rangeFromDate,
                    $request->rangeToDate
                ]);
            }
            $query->orderBy('journey_dt', 'DESC');
        }

        $data = $query->paginate($paginate);

        $totalFare = 0;
        $totalPayable = 0;
        $totalAgentCommission = 0;
        $ownerFare = 0;
        $additionalOwnerFare = 0;
        $totalSeats = 0;

        $today = date('Y-m-d');
        $now   = date('H:i:s');

        foreach ($data as $row) {

            if ($row->journey_dt == $today) {
                $row->journey = ($row->boarding_time < $now) ? 'Over' : 'Upcoming';
            } elseif ($row->journey_dt > $today) {
                $row->journey = 'Upcoming';
            } else {
                $row->journey = 'Over';
            }

            $totalSeats += $row->BookingDetail->count();
            $totalFare += $row->total_fare;
            $totalAgentCommission += $row->agent_commission;
            $totalPayable += $row->payable_amount;
            $ownerFare += $row->owner_fare;
            $additionalOwnerFare += $row->additional_owner_fare;
        }

        $totalReceived = $totalPayable - $totalAgentCommission;
        $ownerFare += $additionalOwnerFare;

        return response()->json([
            'count' => $data->count(),
            'total' => $data->total(),
            'totalSeats' => $totalSeats,
            'totalfare' => number_format($totalFare, 2, '.', ''),
            'totalPayableAmount' => number_format($totalReceived, 2, '.', ''),
            'owner_fare' => number_format($ownerFare, 2, '.', ''),
            'additional_owner_fare' => number_format($additionalOwnerFare, 2, '.', ''),
            'data' => $data
        ]);
    }
}
