<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AgentActivityService
{
    /**
     * Log agent activity and create notification observation.
     */
    public function logActivity(
        $agentId,
        $activityType,
        $eventCode = null,
        $referenceId = null,
        $metadata = null,
        $dueAt = null
    ) {
        try {

            $currentDateTime = date('Y-m-d H:i:s');

            /*
            |--------------------------------------------------------------------------
            | Agent Activity
            |--------------------------------------------------------------------------
            */

            DB::table('agent_activity')->insert([
                'agent_id'      => $agentId,
                'activity_type' => $activityType,
                'activity_time' => $currentDateTime,
                'reference_id'  => $referenceId,
                'metadata'      => !empty($metadata)
                    ? json_encode($metadata)
                    : null,
                'created_at'    => $currentDateTime,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Notification Observation
            |--------------------------------------------------------------------------
            */

            if (!empty($eventCode)) {

                DB::table('notification_observation')->insert([
                    'agent_id'        => $agentId,
                    'event_code'      => $eventCode,
                    'observed_at'     => $currentDateTime,
                    'due_at'          => $dueAt,
                    'status'          => 'PENDING',
                    'attempt_count'   => 0,
                    'last_checked_at' => null,
                    'sent_at'         => null,
                    'metadata'        => !empty($metadata)
                        ? json_encode($metadata)
                        : null,
                    'created_at'      => $currentDateTime,
                    'updated_at'      => $currentDateTime,
                ]);
            }

            return true;
        } catch (\Exception $e) {

            Log::error('Agent activity logging failed', [
                'agent_id'      => $agentId,
                'activity_type' => $activityType,
                'event_code'    => $eventCode,
                'error'         => $e->getMessage()
            ]);

            return false;
        }
    }
}
