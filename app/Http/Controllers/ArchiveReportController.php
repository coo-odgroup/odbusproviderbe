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
use App\Services\Msg91Service;

class ArchiveReportController extends Controller
{
    public function archiveCompleteReport(Request $request)
    {
        // return $request->bus_operator_id;
        $year = $request->year ?? 2022;

        if (!empty($request->rangeFromDate)) {
            $year = date('Y', strtotime($request->rangeFromDate));
        } elseif (!empty($request->rangeToDate)) {
            $year = date('Y', strtotime($request->rangeToDate));
        }

        $paginate = $request->rows_number;

        if ($paginate === 'all') {
            $paginate = config::get('constants.ALL_RECORDS');
        } elseif (empty($paginate)) {
            $paginate = 10;
        }

        BookingDetailArchive::setYear($year);
        CustomerPaymentArchive::setYear($year);

        $bookingModel = (new BookingArchive)->setYear($year);

        $query = $bookingModel->newQuery()
            ->with([
                'bus:id,name,bus_number,bus_operator_id',
                'user:id,name,email,phone',
                'source:id,name',
                'destination:id,name',
                'payment:booking_id,order_id,razorpay_id,payment_done',
                'details.busSeat.seat:id,seatText,berthType'
            ])
            ->withCount('details')
            ->where('status', 1);


        if ($request->pnr) {
            $query->where('pnr', $request->pnr);
        }

        if ($request->bus_operator_id) {
            $query->whereHas('bus', function ($q) use ($request) {
                $q->where('bus_operator_id', $request->bus_operator_id);
            });
        }

        if ($request->apiUser) {
            $query->where('origin', $request->apiUser);
        }

        if ($request->device_type) {
            $query->where('app_type', $request->device_type);
        }

        if ($request->hasGst) {
            $query->where('customer_gst_status', 1)
                ->whereNotNull('customer_gst_number');
        }

        if ($request->bus_id) {
            $query->where('bus_id', $request->bus_id);
        }

        if ($request->payment_id) {
            $query->whereHas('payment', function ($q) use ($request) {
                $q->where('order_id', $request->payment_id)
                    ->where('payment_done', 1);
            });
        }

        if ($request->source_id && $request->destination_id) {
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


        $today = date('Y-m-d');
        $now   = date('H:i:s');

        foreach ($data as $row) {

            // Journey Status
            if ($row->journey_dt == $today) {
                $row->journey = ($row->boarding_time < $now) ? 'Over' : 'Upcoming';
            } elseif ($row->journey_dt > $today) {
                $row->journey = 'Upcoming';
            } else {
                $row->journey = 'Over';
            }

            // Seat Numbers (No GROUP_CONCAT needed)
            $row->seat_numbers = $row->details
                ->pluck('busSeat.seat.seatText')
                ->filter()
                ->implode(',');
        }

        $totalFare            = $data->sum('total_fare');
        $totalSeats           = $data->sum('details_count');
        $totalAgentCommission = $data->sum('agent_commission');
        $totalPayable         = $data->sum('payable_amount');
        $ownerFare            = $data->sum('owner_fare');
        $additionalOwnerFare  = $data->sum('additional_owner_fare');

        $totalReceived = $totalPayable - $totalAgentCommission;
        $ownerFare     = $ownerFare + $additionalOwnerFare;



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

        $data = $query->paginate(10);

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



    public function send(Request $request, Msg91Service $msg91)
    {
        $mobile = 9692066142;

        $data = [
            'var1'  => 'sk sahil',
            'var2'  => 'ODCL1689894',
            'var3'  => 'Bhubaneswar',
            'var4'  => 'Raj Khariar',
            'var5'  => 'NILKANTHESWAR',
            'var6'  => 'OR 02 B 2525',
            'var7'  => '15-12-2020, 20:55',
            'var8'  => 'sk sahil',
            'var9'  => '14,15,16,17 SL 13,14',
            'var10' => '2560.32',
            'var11' => '9348249712',
        ];

        $response = $msg91->sendBookingSms($mobile, $data);

        return response()->json($response);
    }

    // public function sendWhatsappCampaign(Msg91Service $msg91)
    // {
    //     $otp = 123456;

    //     $variables = [

    //         "body_1" => ["type" => "text", "value" => $otp],
    //         "button_1" => ["type" => "text", "value" => $otp],

    //         "var1" => ["type" => "text", "value" => "Sahil"],
    //         "var2" => ["type" => "text", "value" => $otp],

    //     ];

    //     $mobile = [9692066142];

    //     $response = $msg91->sendWhatsappCampaign($mobile, config('msg91.campaigns.otp'), $variables);

    //     return response()->json($response);
    // }

    public function sendWhatsappCampaign(Msg91Service $msg91)
    {

        $variables = [

            // ✅ Header Image
            "header_1" => [
                "type" => "image",
                "value" => "https://provider.odbus.co.in/public/uploads/logo/ODBUS_YELLOW_BG_LOGOWHATSAPP-1.jpg"
            ],

            // ✅ WhatsApp body
            "body_var_1"  => ["type" => "text", "value" => "OD123456"],
            "body_var_2"  => ["type" => "text", "value" => "Nilkantheswar"],
            "body_var_3"  => ["type" => "text", "value" => "OR02B2525"],
            "body_var_4"  => ["type" => "text", "value" => "15-12-2026"],
            "body_var_5"  => ["type" => "text", "value" => "20:55"],
            "body_var_6"  => ["type" => "text", "value" => "Bhubaneswar"],
            "body_var_7"  => ["type" => "text", "value" => "Baramunda"],
            "body_var_8"  => ["type" => "text", "value" => "Cuttack"],
            "body_var_9"  => ["type" => "text", "value" => "Badambadi"],
            "body_var_10" => ["type" => "text", "value" => "Sahil"],
            "body_var_11" => ["type" => "text", "value" => "A1,A2"],
            "body_var_12" => ["type" => "text", "value" => "9692066142"],

            // ✅ SMS
            "var1"  => ["type" => "text", "value" => "OD123456"],
            "var2"  => ["type" => "text", "value" => "Nilkantheswar"],
            "var3"  => ["type" => "text", "value" => "OR02B2525"],
            "var4"  => ["type" => "text", "value" => "15-12-2026"],
            "var5"  => ["type" => "text", "value" => "20:55"],
            "var6"  => ["type" => "text", "value" => "Bhubaneswar"],
            "var7"  => ["type" => "text", "value" => "Cuttack"],
            "var8"  => ["type" => "text", "value" => "Sahil"],
            "var9"  => ["type" => "text", "value" => "A1,A2"],
            "var10" => ["type" => "text", "value" => "9692066142"],
        ];

        $mobile = [9692066142];

        $response = $msg91->sendWhatsappCampaign($mobile, config('msg91.campaigns.otp'), $variables);

        return response()->json($response);
    }
}
