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

        $bookingTable = $year . '_booking';
        $detailTable  = $year . '_booking_detail';
        $paymentTable = $year . '_customer_payment';

        $query = DB::table($bookingTable . ' as b')
            ->leftJoin($paymentTable . ' as cp', 'b.booking_id', '=', 'cp.booking_id')
            ->leftJoin('bus as bus', 'bus.id', '=', 'b.bus_id')
            ->leftJoin('users as u', 'u.id', '=', 'b.users_id')
            ->leftJoin('location as src', 'src.id', '=', 'b.source_id')
            ->leftJoin('location as dest', 'dest.id', '=', 'b.destination_id')

            ->select(
                'b.*',
                'cp.order_id as od_id',
                'cp.razorpay_id as rz_id',
                'bus.name as bus_name',
                'bus.bus_number',
                'src.name as source_name',
                'dest.name as destination_name',

                'u.*',

                // Seat Count Subquery
                DB::raw("(SELECT COUNT(*)
                  FROM {$detailTable} bd
                  WHERE bd.booking_id = b.booking_id) as total_seats"),

                // Seat Numbers Subquery
                DB::raw("(SELECT GROUP_CONCAT(s.seatText)
                  FROM {$detailTable} bd
                  LEFT JOIN bus_seats bs ON bs.id = bd.bus_seats_id
                  LEFT JOIN seats s ON s.id = bs.seats_id
                  WHERE bd.booking_id = b.booking_id) as seat_numbers")
            )
            ->where('b.status', 1)
            ->orderBy('b.journey_dt', 'DESC');


        // Filters

        if (!empty($request->pnr)) {
            $query->where('b.pnr', $request->pnr);
        }

        if (!empty($request->apiUser)) {
            $query->where('b.origin', $request->apiUser);
        }

        if (!empty($request->device_type)) {
            $query->where('b.app_type', $request->device_type);
        }

        if (!empty($request->hasGst)) {
            $query->where('b.customer_gst_status', 1)
                ->whereNotNull('b.customer_gst_number');
        }

        if (!empty($request->bus_id)) {
            $query->where('b.bus_id', $request->bus_id);
        }

        if (!empty($request->bus_operator_id)) {
            $query->leftJoin('bus_operator as bo', 'bo.id', '=', 'bus.bus_operator_id')
                ->where('bo.id', $request->bus_operator_id);
        }

        if (!empty($request->payment_id)) {
            $query->where('cp.order_id', $request->payment_id)
                ->where('cp.payment_done', 1);
        }

        if (!empty($request->source_id) && !empty($request->destination_id)) {
            $query->where('b.source_id', $request->source_id)
                ->where('b.destination_id', $request->destination_id);
        }

        if ($request->date_type === 'booking') {
            if ($request->rangeFromDate && $request->rangeToDate) {
                $query->whereBetween('b.created_at', [
                    $request->rangeFromDate,
                    $request->rangeToDate
                ]);
            }
            $query->orderBy('b.created_at', 'DESC');
        }

        if ($request->date_type === 'journey') {
            if ($request->rangeFromDate && $request->rangeToDate) {
                $query->whereBetween('b.journey_dt', [
                    $request->rangeFromDate,
                    $request->rangeToDate
                ]);
            }
            $query->orderBy('b.journey_dt', 'DESC');
        }

        $data = $query->paginate($paginate);

        // ===== Totals Calculation (Correct Way) =====

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

            $totalSeats += $row->total_seats;
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

        $bookingTable = $year . '_booking';
        $detailTable  = $year . '_booking_detail';
        $paymentTable = $year . '_customer_payment';

        $query = DB::table($bookingTable . ' as b')
            ->leftJoin($paymentTable . ' as cp', 'b.booking_id', '=', 'cp.booking_id')
            ->leftJoin('bus as bus', 'bus.id', '=', 'b.bus_id')
            ->leftJoin('users as u', 'u.id', '=', 'b.users_id')
            ->leftJoin('location as src', 'src.id', '=', 'b.source_id')
            ->leftJoin('location as dest', 'dest.id', '=', 'b.destination_id')

            ->select(
                'b.*',
                'cp.order_id as od_id',
                'cp.razorpay_id as rz_id',
                'bus.name as bus_name',
                'bus.bus_number',
                'src.name as source_name',
                'dest.name as destination_name',

                'u.*',

                // Seat Count Subquery
                DB::raw("(SELECT COUNT(*)
                  FROM {$detailTable} bd
                  WHERE bd.booking_id = b.booking_id) as total_seats"),

                // Seat Numbers Subquery
                DB::raw("(SELECT GROUP_CONCAT(s.seatText)
                  FROM {$detailTable} bd
                  LEFT JOIN bus_seats bs ON bs.id = bd.bus_seats_id
                  LEFT JOIN seats s ON s.id = bs.seats_id
                  WHERE bd.booking_id = b.booking_id) as seat_numbers")
            )
            ->where('b.status', 2)
            ->orderBy('b.journey_dt', 'DESC');


        // Filters

        if (!empty($request->pnr)) {
            $query->where('b.pnr', $request->pnr);
        }

        if (!empty($request->apiUser)) {
            $query->where('b.origin', $request->apiUser);
        }

        if (!empty($request->device_type)) {
            $query->where('b.app_type', $request->device_type);
        }

        if (!empty($request->hasGst)) {
            $query->where('b.customer_gst_status', 1)
                ->whereNotNull('b.customer_gst_number');
        }

        if (!empty($request->bus_id)) {
            $query->where('b.bus_id', $request->bus_id);
        }

        if (!empty($request->bus_operator_id)) {
            $query->leftJoin('bus_operator as bo', 'bo.id', '=', 'bus.bus_operator_id')
                ->where('bo.id', $request->bus_operator_id);
        }

        if (!empty($request->payment_id)) {
            $query->where('cp.order_id', $request->payment_id)
                ->where('cp.payment_done', 1);
        }

        if (!empty($request->source_id) && !empty($request->destination_id)) {
            $query->where('b.source_id', $request->source_id)
                ->where('b.destination_id', $request->destination_id);
        }

        if ($request->date_type === 'booking') {
            if ($request->rangeFromDate && $request->rangeToDate) {
                $query->whereBetween('b.created_at', [
                    $request->rangeFromDate,
                    $request->rangeToDate
                ]);
            }
            $query->orderBy('b.created_at', 'DESC');
        }

        if ($request->date_type === 'journey') {
            if ($request->rangeFromDate && $request->rangeToDate) {
                $query->whereBetween('b.journey_dt', [
                    $request->rangeFromDate,
                    $request->rangeToDate
                ]);
            }
            $query->orderBy('b.journey_dt', 'DESC');
        }

        $data = $query->paginate($paginate);

        // ===== Totals Calculation (Correct Way) =====

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

            $totalSeats += $row->total_seats;
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
