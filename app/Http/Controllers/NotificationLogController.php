<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationLogController extends Controller
{

    public function notificationDashboard(Request $request)
    {
        try {


            $total = DB::table('notification_logs')->count();

            $successful = DB::table('notification_logs')
                ->where('status', 'SUCCESS')
                ->count();
            $invalidToken = DB::table('notification_logs')
                ->where(function ($q) {
                    $q->whereRaw("UPPER(IFNULL(status, '')) = 'INVALID_TOKEN'")
                        ->orWhereRaw("UPPER(IFNULL(error_code, '')) = 'INVALID_TOKEN'")
                        ->orWhereRaw("UPPER(IFNULL(error_code, '')) LIKE '%TOKEN%'")
                        ->orWhereRaw("UPPER(IFNULL(error_code, '')) LIKE '%UNREGISTERED%'")
                        ->orWhereRaw("UPPER(IFNULL(error_message, '')) LIKE '%TOKEN%'")
                        ->orWhereRaw("UPPER(IFNULL(error_message, '')) LIKE '%UNREGISTERED%'")
                        ->orWhereRaw("UPPER(IFNULL(firebase_response, '')) LIKE '%UNREGISTERED%'");
                })
                ->count();

            /*
             * FAILED excluding invalid-token notifications.
             */
            $failed = DB::table('notification_logs')
                ->whereRaw("UPPER(IFNULL(status, '')) = 'FAILED'")
                ->whereRaw("
                    NOT (
                        UPPER(IFNULL(error_code, '')) = 'INVALID_TOKEN'
                        OR UPPER(IFNULL(error_code, '')) LIKE '%TOKEN%'
                        OR UPPER(IFNULL(error_code, '')) LIKE '%UNREGISTERED%'
                        OR UPPER(IFNULL(error_message, '')) LIKE '%TOKEN%'
                        OR UPPER(IFNULL(error_message, '')) LIKE '%UNREGISTERED%'
                        OR UPPER(IFNULL(firebase_response, '')) LIKE '%UNREGISTERED%'
                    )
                ")
                ->count();

            /*
             * Queue states.
             */
            $queued = DB::table('notification_campaign_queue')
                ->whereIn('status', [
                    'QUEUED',
                    'PENDING'
                ])
                ->count();

            $processing = DB::table('notification_campaign_queue')
                ->where('status', 'PROCESSING')
                ->count();

            /*
             * Response time statistics.
             */
            $responseStats = DB::table('notification_logs')
                ->whereNotNull('response_time_ms')
                ->selectRaw('
                    AVG(response_time_ms) as average_response_time,
                    MIN(response_time_ms) as minimum_response_time,
                    MAX(response_time_ms) as maximum_response_time
                ')
                ->first();

            $successRate = $total > 0
                ? round(($successful / $total) * 100, 2)
                : 0;

            /*
             * ----------------------------------------------------
             * CAMPAIGN SUMMARY
             * ----------------------------------------------------
             */

            /*
             * Get latest log for each queue item.
             *
             * This prevents duplicate log records from causing
             * campaign totals to be inflated.
             */
            $latestLogIds = DB::table('notification_logs')
                ->select(
                    'queue_id',
                    DB::raw('MAX(id) as latest_log_id')
                )
                ->whereNotNull('queue_id')
                ->groupBy('queue_id');

            $query = DB::table('notification_campaign_queue as q')

                ->leftJoin(
                    'notification_campaigns as c',
                    'c.id',
                    '=',
                    'q.campaign_id'
                )

                ->leftJoinSub(
                    $latestLogIds,
                    'latest_logs',
                    function ($join) {
                        $join->on(
                            'latest_logs.queue_id',
                            '=',
                            'q.id'
                        );
                    }
                )

                ->leftJoin(
                    'notification_logs as nl',
                    'nl.id',
                    '=',
                    'latest_logs.latest_log_id'
                )

                ->select(
                    'q.campaign_id',
                    'c.campaign_name',
                    'c.title',
                    'c.message',
                    'c.type'
                )

                ->selectRaw('COUNT(q.id) as total')

                /*
                 * SUCCESS
                 */
                ->selectRaw("
                    SUM(
                        CASE
                            WHEN UPPER(IFNULL(nl.status, q.status)) = 'SUCCESS'
                            THEN 1
                            ELSE 0
                        END
                    ) as successful
                ")

                /*
                 * INVALID TOKEN
                 */
                ->selectRaw("
                    SUM(
                        CASE
                            WHEN
                                UPPER(IFNULL(nl.status, '')) = 'INVALID_TOKEN'
                                OR UPPER(IFNULL(nl.error_code, '')) = 'INVALID_TOKEN'
                                OR UPPER(IFNULL(nl.error_code, '')) LIKE '%TOKEN%'
                                OR UPPER(IFNULL(nl.error_code, '')) LIKE '%UNREGISTERED%'
                                OR UPPER(IFNULL(nl.error_message, '')) LIKE '%TOKEN%'
                                OR UPPER(IFNULL(nl.error_message, '')) LIKE '%UNREGISTERED%'
                                OR UPPER(IFNULL(nl.firebase_response, '')) LIKE '%UNREGISTERED%'
                            THEN 1
                            ELSE 0
                        END
                    ) as invalid_token
                ")

                /*
                 * FAILED
                 */
                ->selectRaw("
                    SUM(
                        CASE
                            WHEN UPPER(IFNULL(nl.status, q.status)) = 'FAILED'
                            AND NOT (
                                UPPER(IFNULL(nl.error_code, '')) = 'INVALID_TOKEN'
                                OR UPPER(IFNULL(nl.error_code, '')) LIKE '%TOKEN%'
                                OR UPPER(IFNULL(nl.error_code, '')) LIKE '%UNREGISTERED%'
                                OR UPPER(IFNULL(nl.error_message, '')) LIKE '%TOKEN%'
                                OR UPPER(IFNULL(nl.error_message, '')) LIKE '%UNREGISTERED%'
                                OR UPPER(IFNULL(nl.firebase_response, '')) LIKE '%UNREGISTERED%'
                            )
                            THEN 1
                            ELSE 0
                        END
                    ) as failed
                ")

                /*
                 * QUEUED
                 */
                ->selectRaw("
                    SUM(
                        CASE
                            WHEN UPPER(q.status) = 'QUEUED'
                            THEN 1
                            ELSE 0
                        END
                    ) as queued
                ")

                /*
                 * PENDING
                 */
                ->selectRaw("
                    SUM(
                        CASE
                            WHEN UPPER(q.status) = 'PENDING'
                            THEN 1
                            ELSE 0
                        END
                    ) as pending
                ")

                /*
                 * PROCESSING
                 */
                ->selectRaw("
                    SUM(
                        CASE
                            WHEN UPPER(q.status) = 'PROCESSING'
                            THEN 1
                            ELSE 0
                        END
                    ) as processing
                ")

                /*
                 * Average response time.
                 */
                ->selectRaw('
                    ROUND(AVG(nl.response_time_ms), 2)
                    as average_response_time
                ')

                ->selectRaw('MIN(nl.sent_at) as first_sent_at')
                ->selectRaw('MAX(nl.sent_at) as last_sent_at')

                ->groupBy(
                    'q.campaign_id',
                    'c.campaign_name',
                    'c.title',
                    'c.message',
                    'c.type'
                );

            /*
             * Campaign filter.
             */
            if ($request->filled('campaign_id')) {
                $query->where(
                    'q.campaign_id',
                    $request->campaign_id
                );
            }

            /*
             * Notification type filter.
             */
            if ($request->filled('notification_type')) {
                $query->where(
                    'nl.notification_type',
                    $request->notification_type
                );
            }

            /*
             * Date filter.
             */
            if ($request->filled('date_from')) {
                $query->whereDate(
                    'q.created_at',
                    '>=',
                    $request->date_from
                );
            }

            if ($request->filled('date_to')) {
                $query->whereDate(
                    'q.created_at',
                    '<=',
                    $request->date_to
                );
            }

            $query->orderByDesc('q.campaign_id');

            $rowsNumber = (int) $request->input(
                'rows_number',
                10
            );

            if ($rowsNumber <= 0) {
                $rowsNumber = 10;
            }

            $campaigns = $query->paginate($rowsNumber);

            return response()->json([
                'status' => true,

                'message' =>
                'Notification dashboard fetched successfully.',

                'summary' => [
                    'total' => (int) $total,
                    'successful' => (int) $successful,
                    'failed' => (int) $failed,
                    'invalid_token' => (int) $invalidToken,
                    'queued' => (int) $queued,
                    'processing' => (int) $processing,

                    'success_rate' => $successRate,

                    'average_response_time' =>
                    round(
                        (float) ($responseStats->average_response_time ?? 0),
                        2
                    ),

                    'minimum_response_time' =>
                    (int) ($responseStats->minimum_response_time ?? 0),

                    'maximum_response_time' =>
                    (int) ($responseStats->maximum_response_time ?? 0),
                ],

                'data' => $campaigns->items(),

                'pagination' => [
                    'current_page' =>
                    $campaigns->currentPage(),

                    'last_page' =>
                    $campaigns->lastPage(),

                    'per_page' =>
                    $campaigns->perPage(),

                    'total' =>
                    $campaigns->total(),
                ],
            ]);
        } catch (\Throwable $e) {

            Log::error(
                'Notification Dashboard Error',
                [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'request' => $request->all(),
                ]
            );

            return response()->json([
                'status' => false,
                'message' =>
                'Unable to load notification dashboard.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * ============================================================
     * CAMPAIGN DETAILS
     * ============================================================
     *
     * Called ONLY after user clicks VIEW.
     */
    public function campaignDetails(
        Request $request,
        $campaignId
    ) {
        try {

            $rowsNumber = (int) $request->input(
                'rows_number',
                25
            );

            if ($rowsNumber <= 0) {
                $rowsNumber = 25;
            }

            /*
             * Latest log per queue item.
             */
            $latestLogIds = DB::table('notification_logs')
                ->select(
                    'queue_id',
                    DB::raw('MAX(id) as latest_log_id')
                )
                ->whereNotNull('queue_id')
                ->groupBy('queue_id');

            /*
             * Detail query.
             */
            $query = DB::table(
                'notification_campaign_queue as q'
            )

                ->leftJoin(
                    'users as u',
                    'u.id',
                    '=',
                    'q.user_id'
                )

                ->leftJoinSub(
                    $latestLogIds,
                    'latest_logs',
                    function ($join) {
                        $join->on(
                            'latest_logs.queue_id',
                            '=',
                            'q.id'
                        );
                    }
                )

                ->leftJoin(
                    'notification_logs as nl',
                    'nl.id',
                    '=',
                    'latest_logs.latest_log_id'
                )

                ->where(
                    'q.campaign_id',
                    $campaignId
                )

                ->select(
                    'q.id as queue_id',
                    'q.campaign_id',
                    'q.user_id',

                    'u.name as user_name',

                    'q.email',
                    'q.mobile',
                    'q.fcm_token',

                    'q.title',
                    'q.message',

                    'q.status as queue_status',

                    'q.booking_status',
                    'q.booking_id',

                    'q.retry_count',

                    'q.scheduled_time',
                    'q.queued_at',
                    'q.processing_at',
                    'q.processed_at',

                    'q.error_code as queue_error_code',
                    'q.error_message as queue_error_message',

                    'nl.id as log_id',
                    'nl.notification_type',
                    'nl.fcm_message_id',
                    'nl.status as log_status',
                    'nl.error_code',
                    'nl.error_message',
                    'nl.firebase_response',
                    'nl.sent_at',
                    'nl.response_time_ms',

                    'q.created_at',
                    'q.updated_at'
                )

                /*
                 * Normalized status.
                 */
                ->selectRaw("
                    CASE

                        WHEN
                            UPPER(IFNULL(nl.status, '')) = 'SUCCESS'
                        THEN 'SUCCESS'

                        WHEN
                            UPPER(IFNULL(nl.error_code, '')) = 'INVALID_TOKEN'
                            OR UPPER(IFNULL(nl.error_code, '')) LIKE '%TOKEN%'
                            OR UPPER(IFNULL(nl.error_code, '')) LIKE '%UNREGISTERED%'
                            OR UPPER(IFNULL(nl.error_message, '')) LIKE '%TOKEN%'
                            OR UPPER(IFNULL(nl.error_message, '')) LIKE '%UNREGISTERED%'
                            OR UPPER(IFNULL(nl.firebase_response, '')) LIKE '%UNREGISTERED%'
                        THEN 'INVALID_TOKEN'

                        WHEN
                            UPPER(IFNULL(nl.status, q.status)) = 'FAILED'
                        THEN 'FAILED'

                        WHEN
                            UPPER(q.status) = 'PROCESSING'
                        THEN 'PROCESSING'

                        WHEN
                            UPPER(q.status) = 'QUEUED'
                        THEN 'QUEUED'

                        WHEN
                            UPPER(q.status) = 'PENDING'
                        THEN 'PENDING'

                        WHEN
                            UPPER(q.status) = 'CANCELLED'
                        THEN 'CANCELLED'

                        ELSE
                            UPPER(IFNULL(q.status, 'PENDING'))

                    END AS normalized_status
                ");

            /*
             * Search.
             */
            if ($request->filled('search')) {

                $search = trim(
                    $request->input('search')
                );

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'q.user_id',
                        'LIKE',
                        "%{$search}%"
                    )

                        ->orWhere(
                            'u.name',
                            'LIKE',
                            "%{$search}%"
                        )

                        ->orWhere(
                            'q.mobile',
                            'LIKE',
                            "%{$search}%"
                        )

                        ->orWhere(
                            'q.email',
                            'LIKE',
                            "%{$search}%"
                        );
                });
            }

            /*
             * Status filter.
             */
            if ($request->filled('status')) {

                $status = strtoupper(
                    $request->status
                );

                if ($status === 'INVALID_TOKEN') {

                    $query->where(function ($q) {

                        $q->whereRaw(
                            "UPPER(IFNULL(nl.error_code, '')) = 'INVALID_TOKEN'"
                        )

                            ->orWhereRaw(
                                "UPPER(IFNULL(nl.error_code, '')) LIKE '%TOKEN%'"
                            )

                            ->orWhereRaw(
                                "UPPER(IFNULL(nl.error_code, '')) LIKE '%UNREGISTERED%'"
                            )

                            ->orWhereRaw(
                                "UPPER(IFNULL(nl.error_message, '')) LIKE '%TOKEN%'"
                            )

                            ->orWhereRaw(
                                "UPPER(IFNULL(nl.firebase_response, '')) LIKE '%UNREGISTERED%'"
                            );
                    });
                } elseif (
                    in_array(
                        $status,
                        [
                            'SUCCESS',
                            'FAILED'
                        ]
                    )
                ) {

                    $query->where(
                        'nl.status',
                        $status
                    );
                }
            }

            $query->orderByDesc('q.id');

            $details = $query->paginate(
                $rowsNumber
            );

            /*
             * ----------------------------------------------------
             * CAMPAIGN METRICS
             * ----------------------------------------------------
             */

            $metrics = DB::table(
                'notification_campaign_queue as q'
            )

                ->leftJoinSub(
                    $latestLogIds,
                    'latest_logs',
                    function ($join) {
                        $join->on(
                            'latest_logs.queue_id',
                            '=',
                            'q.id'
                        );
                    }
                )

                ->leftJoin(
                    'notification_logs as nl',
                    'nl.id',
                    '=',
                    'latest_logs.latest_log_id'
                )

                ->where(
                    'q.campaign_id',
                    $campaignId
                )

                ->selectRaw('COUNT(q.id) as total')

                ->selectRaw("
                    SUM(
                        CASE
                            WHEN nl.status = 'SUCCESS'
                            THEN 1 ELSE 0
                        END
                    ) as successful
                ")

                ->selectRaw("
                    SUM(
                        CASE
                            WHEN
                                UPPER(IFNULL(nl.error_code, '')) = 'INVALID_TOKEN'
                                OR UPPER(IFNULL(nl.error_code, '')) LIKE '%TOKEN%'
                                OR UPPER(IFNULL(nl.error_code, '')) LIKE '%UNREGISTERED%'
                                OR UPPER(IFNULL(nl.error_message, '')) LIKE '%TOKEN%'
                                OR UPPER(IFNULL(nl.firebase_response, '')) LIKE '%UNREGISTERED%'
                            THEN 1 ELSE 0
                        END
                    ) as invalid_token
                ")

                ->selectRaw("
                    SUM(
                        CASE
                            WHEN nl.status = 'FAILED'
                            AND NOT (
                                UPPER(IFNULL(nl.error_code, '')) = 'INVALID_TOKEN'
                                OR UPPER(IFNULL(nl.error_code, '')) LIKE '%TOKEN%'
                                OR UPPER(IFNULL(nl.error_code, '')) LIKE '%UNREGISTERED%'
                                OR UPPER(IFNULL(nl.error_message, '')) LIKE '%TOKEN%'
                                OR UPPER(IFNULL(nl.firebase_response, '')) LIKE '%UNREGISTERED%'
                            )
                            THEN 1 ELSE 0
                        END
                    ) as failed
                ")

                /*
                * PENDING
                */
                ->selectRaw("
                    SUM(
                        CASE
                            WHEN q.status = 'PENDING'
                            THEN 1 ELSE 0
                        END
                    ) as pending
                ")

                /*
                * QUEUED
                */
                ->selectRaw("
                    SUM(
                        CASE
                            WHEN q.status = 'QUEUED'
                            THEN 1 ELSE 0
                        END
                    ) as queued
                ")

                ->selectRaw("
                    SUM(
                        CASE
                            WHEN q.status = 'PROCESSING'
                            THEN 1 ELSE 0
                        END
                    ) as processing
                ")

                ->selectRaw(
                    'ROUND(AVG(nl.response_time_ms), 2) as average_response_time'
                )

                ->first();

            /*
             * Hourly performance.
             */
            $hourly = DB::table(
                'notification_logs'
            )
                ->where(
                    'campaign_id',
                    $campaignId
                )
                ->whereNotNull('sent_at')
                ->selectRaw("
                    DATE_FORMAT(sent_at, '%Y-%m-%d %H:00:00')
                    as hour
                ")
                ->selectRaw("
                    COUNT(*) as total
                ")
                ->selectRaw("
                    SUM(
                        CASE
                            WHEN status = 'SUCCESS'
                            THEN 1 ELSE 0
                        END
                    ) as successful
                ")
                ->selectRaw("
                    SUM(
                        CASE
                            WHEN status = 'FAILED'
                            THEN 1 ELSE 0
                        END
                    ) as failed
                ")
                ->groupBy('hour')
                ->orderBy('hour')
                ->get();

            /*
             * Campaign information.
             */
            $campaign = DB::table(
                'notification_campaigns'
            )
                ->where(
                    'id',
                    $campaignId
                )
                ->first();

            return response()->json([
                'status' => true,

                'campaign' => $campaign,

                'metrics' => [
                    'total' =>
                    (int) ($metrics->total ?? 0),

                    'successful' =>
                    (int) ($metrics->successful ?? 0),

                    'failed' =>
                    (int) ($metrics->failed ?? 0),

                    'invalid_token' =>
                    (int) ($metrics->invalid_token ?? 0),

                    'pending' =>
                    (int) ($metrics->pending ?? 0),

                    'queued' =>
                    (int) ($metrics->queued ?? 0),

                    'processing' =>
                    (int) ($metrics->processing ?? 0),

                    'average_response_time' =>
                    round(
                        (float) ($metrics->average_response_time ?? 0),
                        2
                    ),
                ],

                'hourly' => $hourly,

                'data' => $details->items(),

                'pagination' => [

                    'current_page' =>
                    $details->currentPage(),

                    'last_page' =>
                    $details->lastPage(),

                    'per_page' =>
                    $details->perPage(),

                    'total' =>
                    $details->total(),

                    'from' =>
                    $details->firstItem(),

                    'to' =>
                    $details->lastItem(),
                ],
            ]);
        } catch (\Throwable $e) {

            Log::error(
                'Notification Campaign Details Error',
                [
                    'campaign_id' => $campaignId,
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );

            return response()->json([
                'status' => false,
                'message' =>
                'Unable to load campaign details.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * ============================================================
     * CAMPAIGN DROPDOWN
     * ============================================================
     */
    public function notificationCampaignList()
    {
        try {

            $campaigns = DB::table(
                'notification_campaigns'
            )
                ->select(
                    'id',
                    'campaign_name',
                    'title',
                    'type'
                )
                ->orderBy(
                    'campaign_name',
                    'asc'
                )
                ->get();

            return response()->json([
                'status' => true,
                'message' =>
                'Notification campaigns fetched successfully.',
                'data' => $campaigns,
            ]);
        } catch (\Throwable $e) {

            Log::error(
                'Notification Campaign List Error',
                [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );

            return response()->json([
                'status' => false,
                'message' =>
                'Unable to load campaigns.',
            ], 500);
        }
    }
}
