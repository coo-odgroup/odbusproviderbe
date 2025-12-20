<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ApiLogReportController extends Controller
{
    public function __construct() {}

    public function apiLogReport(Request $request)
    {
        $date = $request['date'] ?? today();
        $user = $request['user'] ?? [559, 486];
        $paginate = $request['rows_number'] ?? 100;
        $status = $request['status'] ?? null;

        // return $status;

        $api_log_data = DB::table('api_log')
            ->whereIn('user_id', (array) $user)
            ->where('url', 'LIKE', '%TicketConfirmation%')
            ->whereDate('created_at', $date);

        if (isset($status) && (int)$status === 1) {
            $api_log_data->where('response', 'LIKE', '%\\"status\\\":\\\"1\\\"%');
        } elseif (isset($status) && (int)$status === 0) {
            $api_log_data->where('response', 'LIKE', '%\\"status\\\":\\\"0\\\"%');
        }

        $api_log_data = $api_log_data->paginate($paginate);

        // return "working";

        // return $api_log_data;

        // New array for processed items
        $result = [];

        foreach ($api_log_data->items() as $item) {

            // return $item;

            $req = json_decode($item->request_body, true);
            $transactionId = $req["transaction_id"] ?? null;

            // Fetch PNR
            // $pnr = null;
            // if ($transactionId) {
            //     $pnr = DB::table('booking')
            //         ->where('transaction_id', $transactionId)
            //         ->value('pnr');
            // }

            // Clean response
            $resp = stripslashes($item->response);
            $clean = trim($resp, '"');
            $array = json_decode($clean, true);

            // return $array;

            $status = $array["status"] ?? "";

            if ($status == "") {
                continue;
            }

            $pnr = $array["data"]["final_pnr"] ?? "";

            // Push formatted data
            $result[] = [
                "transaction_id" => $transactionId,
                "pnr" => $pnr,
                "response_status" => $status,
                "response_status_arr" => $clean,
                "created_at" => $item->created_at,
                "user_name" => $item->user_name,
            ];
        }

        // Return pagination + custom formatted data
        // return response()->json([
        //     "status"       => 1,
        //     "message"      => "Records Fetch Successfully",
        //     "current_page" => $api_log_data->currentPage(),
        //     "from"         => $api_log_data->firstItem(),
        //     "to"           => $api_log_data->lastItem(),
        //     "per_page"     => $api_log_data->perPage(),
        //     "last_page"    => $api_log_data->lastPage(),
        //     "next_page_url" => $api_log_data->nextPageUrl(),
        //     "prev_page_url" => $api_log_data->previousPageUrl(),
        //     "first_page_url" => $api_log_data->url(1),
        //     "last_page_url"  => $api_log_data->url($api_log_data->lastPage()),
        //     "total"        => $api_log_data->total(),
        //     "links"        => $api_log_data->linkCollection(),
        //     "data"         => $result
        // ]);

        $pagination = $api_log_data->toArray();

        return response()->json([
            "status"        => 1,
            "message"       => "Records Fetch Successfully",
            "current_page"   => $pagination['current_page'],
            "from"           => $pagination['from'],
            "to"             => $pagination['to'],
            "per_page"       => $pagination['per_page'],
            "last_page"      => $pagination['last_page'],
            "next_page_url"  => $pagination['next_page_url'],
            "prev_page_url"  => $pagination['prev_page_url'],
            "first_page_url" => $api_log_data->url(1),
            "last_page_url"  => $api_log_data->url($api_log_data->lastPage()),
            "total"          => $pagination['total'],
            "links"          => $pagination['links'],
            "data"           => $result
        ]);
    }
}
