<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class NotificationLogController extends Controller
{
    public function notificationLogReport(Request $request)
    {
        try {

            $rowsNumber = (int) $request->input('rows_number', 10);

            if ($rowsNumber <= 0) {
                $rowsNumber = 10;
            }

            $query = DB::table('notification_logs as nl')

                // Campaign
                ->leftJoin(
                    'notification_campaigns as nc',
                    'nc.id',
                    '=',
                    'nl.campaign_id'
                )

                // User
                ->leftJoin(
                    'users as u',
                    'u.id',
                    '=',
                    'nl.user_id'
                )

                ->select(
                    'nl.id',
                    'nl.campaign_id',
                    'nl.queue_id',
                    'nl.notification_type',
                    'nl.user_id',
                    // Get user name
                    'u.name as user_name',

                    'nl.fcm_token',
                    'nl.fcm_message_id',
                    'nl.status',
                    'nl.error_code',
                    'nl.error_message',
                    'nl.firebase_response',
                    'nl.sent_at',
                    'nl.response_time_ms',
                    'nl.created_at',

                    // Campaign information
                    'nc.campaign_name',
                    'nc.title',
                    'nc.message',
                    'nc.type as campaign_type'
                );

            /*
             * Campaign filter
             */
            /*
            * Campaign filter
            */
            if ($request->filled('campaign_id')) {
                $query->where(
                    'nl.campaign_id',
                    $request->campaign_id
                );
            }
            /*
             * User filter
             */
            if ($request->filled('notification_type')) {
                $query->where(
                    'nl.notification_type',
                    $request->notification_type
                );
            }

            /*
             * Status filter
             */
            if ($request->filled('status')) {
                $query->where(
                    'nl.status',
                    $request->status
                );
            }

            /*
             * Date from
             */
            if ($request->filled('date_from')) {
                $query->whereDate(
                    'nl.created_at',
                    '>=',
                    $request->date_from
                );
            }

            /*
             * Date to
             */
            if ($request->filled('date_to')) {
                $query->whereDate(
                    'nl.created_at',
                    '<=',
                    $request->date_to
                );
            }

            $query->orderByDesc('nl.id');

            $logs = $query->paginate($rowsNumber);

            return response()->json([
                'status' => true,
                'message' => 'Notification logs fetched successfully.',

                'data' => $logs->items(),

                'pagination' => [
                    'current_page' => $logs->currentPage(),
                    'last_page' => $logs->lastPage(),
                    'per_page' => $logs->perPage(),
                    'total' => $logs->total(),
                ]
            ]);
        } catch (\Throwable $e) {

            Log::error('Notification Log Report Error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all()
            ]);

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function notificationCampaignList()
    {
        try {

            $campaigns = DB::table('notification_campaigns')
                ->select(
                    'id',
                    'campaign_name'
                )
                ->orderBy('campaign_name', 'asc')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Notification campaigns fetched successfully.',
                'data' => $campaigns
            ]);
        } catch (\Throwable $e) {

            Log::error('Notification Campaign List Error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
