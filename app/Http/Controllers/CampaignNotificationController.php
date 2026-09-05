<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use ApiResponser;
use App\Models\User;
use App\Models\Users;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;
use Mews\Purifier\Facades\Purifier;
use App\Models\CampaignNotification;


class CampaignNotificationController extends Controller
{

    public function createCampaignNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'campaign_name' => 'required|max:150',
            'title' => 'required|max:255',
            'message' => 'required',
            'type' => 'required|in:PROMOTIONAL,TRANSACTIONAL,REMINDER,CUSTOM',
            'target_type' => 'required|in:ALL,ACTIVE,SELECTED,VERIFIED,CUSTOM',
            'active_user_duration' => 'required_if:target_type,ACTIVE,CUSTOM,SELECTED|nullable|integer|min:1',
            'selected_user_ids' => 'required_if:target_type,SELECTED|array|min:1',
            'selected_user_ids.*' => 'required|integer|exists:users,id',
            'schedule_type' => 'required|in:IMMEDIATE,SCHEDULED,BEFORE_EVENT,AFTER_EVENT',
            'schedule_minutes' => 'nullable|integer',
            'notification_category_id' => 'required|exists:notification_category,id',
            'schedules' => 'required_if:schedule_type,SCHEDULED|array|min:1',
            'schedules.*.schedule_date' => 'required_if:schedule_type,SCHEDULED|date_format:Y-m-d',
            'schedules.*.start_time' => 'required_if:schedule_type,SCHEDULED|date_format:H:i',
            'schedules.*.end_time' => 'required_if:schedule_type,SCHEDULED|date_format:H:i',
        ]);

        if ($validator->fails()) {

            Log::error('Campaign validation failed', [
                'errors' => $validator->errors()->toArray(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'status' => 0,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        DB::beginTransaction();

        try {

            /*
        |--------------------------------------------------------------------------
        | Create notification_campaigns record
        |--------------------------------------------------------------------------
        */

            $data = $request->only([
                'notification_category_id',
                'campaign_name',
                'title',
                'message',
                'type',
                'active_user_duration',
                'target_type',
                'schedule_type',
                'schedule_minutes'
            ]);


            //Image upload


            if ($request->hasFile('image')) {

                $file = $request->file('image');

                $fileName = time() . '_' . $file->getClientOriginalName();

                $uploadPath = public_path(
                    'uploads/campaign_notifications'
                );

                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $file->move(
                    $uploadPath,
                    $fileName
                );

                $data['image'] =
                    'uploads/campaign_notifications/' . $fileName;
            }


            // Campaign defaults


            $data['active_status'] = 1;
            $data['created_by'] = $request->created_by;

            $campaign = CampaignNotification::create($data);

            if ($request->target_type === 'SELECTED') {

                $selectedUserIds = $request->selected_user_ids ?? [];

                // Get selected users with their FCM ID and name
                $selectedUsers = Users::whereIn('id', $selectedUserIds)
                    ->get(['id', 'name', 'fcm_id']);

                foreach ($selectedUserIds as $userId) {

                    $user = DB::table('users')
                        ->select([
                            'id',
                            'name',
                            'email',
                            'phone',
                            'fcm_id'
                        ])
                        ->where('id', $userId)
                        ->whereNotNull('fcm_id')
                        ->where('fcm_id', '!=', '')
                        ->first();

                    if (!$user) {
                        continue;
                    }

                    DB::table('notification_campaign_selected_users')->insert([

                        'campaign_id' => $campaign->id,
                        'user_name' => $user->name,
                        'time_duration' => $request->active_user_duration,
                        'mobile' => $user->phone,
                        'email' => $user->email,
                        'selected_users' => $user->id,
                        'fcm_id' => $user->fcm_id,
                        'created_at' => now(),
                        'updated_at' => now(),

                    ]);
                }
            }



            // CUSTOM TARGET TYPE

            //  custom_scenario:

            //  ROUTE         = 1
            // NEW_USER      = 2
            // OPERATOR      = 3
            // SPECIAL_OFFER = 4



            if (
                $request->target_type === 'CUSTOM' &&
                !empty($request->custom_scenario)
            ) {

                $customType = null;

                switch ($request->custom_scenario) {

                    case 'ROUTE':
                        $customType = 1;
                        break;

                    case 'NEW_USER':
                        $customType = 2;
                        break;

                    case 'OPERATOR':
                        $customType = 3;
                        break;

                    case 'SPECIAL_OFFER':
                        $customType = 4;
                        break;
                }


                if ($customType !== null) {

                    DB::table('notification_campaign_custom')->insert([

                        'campaign_id' => $campaign->id,

                        'custom_type' => $customType,


                        //Route

                        'source_id' =>
                        $request->custom_scenario === 'ROUTE'
                            ? $request->source
                            : null,

                        'destination_id' =>
                        $request->custom_scenario === 'ROUTE'
                            ? $request->destination
                            : null,


                        // Operator

                        'operator_id' =>
                        $request->custom_scenario === 'OPERATOR'
                            ? $request->operator_id
                            : null,

                        // CUSTOM scenarios don't use coupon_code.
                        'coupon_code' => null,

                        'created_at' => now(),
                        'created_by' => $request->created_by,
                        'updated_at' => null,
                        'updated_by' => null,
                    ]);
                }
            }




            if (
                $request->type === 'PROMOTIONAL' &&
                !empty($request->custom_scenario)
            ) {


                //custom_scenario contains the coupon ID
                //from your Angular dropdown.


                $coupon = DB::table('coupon')
                    ->where('id', $request->custom_scenario)
                    ->first();

                if ($coupon) {

                    DB::table('notification_campaign_custom')->insert([

                        'campaign_id' => $campaign->id,

                        /*
                    | Promotional coupon does not belong to
                    | Route/New User/Operator/Special Offer.
                    |
                    | Keep custom_type NULL.
                    */
                        'custom_type' => null,

                        'source_id' => null,
                        'destination_id' => null,
                        'operator_id' => null,

                        'coupon_code' => $coupon->coupon_code,

                        'created_at' => now(),
                        'created_by' => $request->created_by,
                        'updated_at' => null,
                        'updated_by' => null,
                    ]);
                }
            }


            //Scheduled campaign schedules


            if ($request->schedule_type === 'SCHEDULED') {

                foreach ($request->schedules as $schedule) {

                    DB::table('notification_campaign_schedule')->insert([

                        'notification_campaign_id' => $campaign->id,

                        'schedule_date' =>
                        $schedule['schedule_date'],

                        'start_time' =>
                        $schedule['start_time'],

                        'end_time' =>
                        $schedule['end_time'],

                        'created_at' => now(),

                        'created_by' =>
                        $request->created_by,
                    ]);
                }
            }


            DB::commit();


            return response()->json([

                'status' => 1,

                'message' =>
                'Campaign Notification Created Successfully',

                'data' => $campaign

            ], Response::HTTP_OK);
        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error(
                'Campaign Notification Creation Failed',
                [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]
            );

            return response()->json([

                'status' => 0,

                'message' => $e->getMessage()

            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateCampaignNotification(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'notification_category_id' => 'required|exists:notification_category,id',
            'campaign_name' => 'required|max:150',
            'title' => 'required|max:255',
            'message' => 'required',
            'type' => 'required|in:PROMOTIONAL,TRANSACTIONAL,REMINDER,CUSTOM',
            'target_type' => 'required|in:ALL,ACTIVE,INACTIVE,SELECTED,VERIFIED,CUSTOM',
            'active_user_duration' => 'required_if:target_type,ACTIVE,CUSTOM,SELECTED|nullable|integer|min:1',
            'selected_user_ids' => 'required_if:target_type,SELECTED|array|min:1',
            'selected_user_ids.*' => 'required|integer|exists:users,id',
            'schedule_type' => 'required|in:IMMEDIATE,SCHEDULED,BEFORE_EVENT,AFTER_EVENT',
            'schedule_minutes' => 'nullable|integer',

            // Required only for SCHEDULED
            'schedules' => 'required_if:schedule_type,SCHEDULED|array|min:1',
            'schedules.*.id' => 'nullable|integer',
            'schedules.*.schedule_date' => 'required_if:schedule_type,SCHEDULED|date_format:Y-m-d',
            'schedules.*.start_time' => 'required_if:schedule_type,SCHEDULED|date_format:H:i:s',
            'schedules.*.end_time' => 'required_if:schedule_type,SCHEDULED|date_format:H:i:s',
        ]);

        if ($validator->fails()) {

            Log::error('Campaign update validation failed', [
                'campaign_id' => $id,
                'errors' => $validator->errors()->toArray(),
                'request' => $request->all()
            ]);

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            DB::beginTransaction();

            //Find Campaign


            $campaign = CampaignNotification::find($id);

            if (!$campaign) {

                DB::rollBack();

                return response()->json([
                    'status' => 0,
                    'message' => 'Campaign Notification Not Found'
                ], Response::HTTP_NOT_FOUND);
            }

            // Validate User


            if (!User::where('id', $request->created_by)->exists()) {

                DB::rollBack();

                return response()->json([
                    'status' => 0,
                    'message' => 'Invalid User'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Update Campaign


            $campaign->notification_category_id = $request->notification_category_id;
            $campaign->campaign_name = $request->campaign_name;
            $campaign->title = $request->title;
            $campaign->message = $request->message;
            $campaign->type = $request->type;
            $campaign->target_type = $request->target_type;
            $campaign->active_user_duration = in_array($request->target_type, ['ACTIVE', 'CUSTOM', 'SELECTED']) ? $request->active_user_duration : null;
            $campaign->schedule_type = $request->schedule_type;
            $campaign->schedule_minutes = $request->schedule_minutes;
            $campaign->updated_by = $request->created_by;

            // Image


            if ($request->hasFile('image')) {

                if (
                    !empty($campaign->image) &&
                    file_exists(public_path($campaign->image))
                ) {
                    unlink(public_path($campaign->image));
                }

                $file = $request->file('image');

                $fileName = time() . '_' . $file->getClientOriginalName();

                $file->move(
                    public_path('uploads/campaign_notifications'),
                    $fileName
                );

                $campaign->image =
                    'uploads/campaign_notifications/' . $fileName;
            }

            $campaign->save();


            /*
        |--------------------------------------------------------------------------
        | Update Selected Target Users
        |--------------------------------------------------------------------------
        |
        | Remove the old selected users first.
        |
        | This is done regardless of the new target type so that if the
        | campaign was previously SELECTED and is changed to another
        | target type, its old selected-user records are removed.
        |
        */

            DB::table('notification_campaign_selected_users')
                ->where('campaign_id', $campaign->id)
                ->delete();



            if ($request->target_type === 'SELECTED') {

                $selectedUserIds = $request->selected_user_ids ?? [];

                // Get selected users with their FCM ID and name
                $selectedUsers = Users::whereIn('id', $selectedUserIds)
                    ->get(['id', 'name', 'fcm_id']);

                foreach ($selectedUsers as $user) {

                    DB::table('notification_campaign_selected_users')->insert([
                        'campaign_id' => $campaign->id,
                        'time_duration' => $request->active_user_duration,

                        // User details
                        'selected_users' => $user->id,
                        'user_name'      => $user->name,
                        'mobile'         => $user->phone,
                        'email'          => $user->email,
                        'fcm_id'         => $user->fcm_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }


            // Update Custom Campaign Data

            DB::table('notification_campaign_custom')
                ->where('campaign_id', $campaign->id)
                ->delete();

            // CUSTOM TARGET TYPE


            if (
                $request->target_type === 'CUSTOM' &&
                !empty($request->custom_scenario)
            ) {

                $customType = null;

                switch ($request->custom_scenario) {

                    case 'ROUTE':
                        $customType = 1;
                        break;

                    case 'NEW_USER':
                        $customType = 2;
                        break;

                    case 'OPERATOR':
                        $customType = 3;
                        break;

                    case 'SPECIAL_OFFER':
                        $customType = 4;
                        break;
                }

                if ($customType !== null) {

                    DB::table('notification_campaign_custom')->insert([

                        'campaign_id' => $campaign->id,

                        'custom_type' => $customType,

                        'source_id' =>
                        $request->custom_scenario === 'ROUTE'
                            ? $request->source
                            : null,

                        'destination_id' =>
                        $request->custom_scenario === 'ROUTE'
                            ? $request->destination
                            : null,

                        'operator_id' =>
                        $request->custom_scenario === 'OPERATOR'
                            ? $request->operator_id
                            : null,

                        'coupon_code' => null,

                        'created_at' => now(),
                        'created_by' => $request->created_by,
                        'updated_at' => now(),
                        'updated_by' => $request->created_by,
                    ]);
                }
            }


            // PROMOTIONAL + COUPON
            // PROMOTIONAL + COUPON


            if (
                $request->type === 'PROMOTIONAL' &&
                !empty($request->coupon_code)
            ) {

                $coupon = DB::table('coupon')
                    ->where('id', $request->coupon_code)
                    ->first();

                if ($coupon) {

                    DB::table('notification_campaign_custom')->insert([
                        'campaign_id' => $campaign->id,
                        'custom_type' => null,
                        'source_id' => null,
                        'destination_id' => null,
                        'operator_id' => null,
                        'coupon_code' => $coupon->coupon_code,
                        'created_at' => now(),
                        'created_by' => $request->created_by,
                        'updated_at' => now(),
                        'updated_by' => $request->created_by,
                    ]);
                }
            }

            if ($request->schedule_type === 'SCHEDULED') {

                // Get existing schedule IDs from database
                $existingSchedules = DB::table('notification_campaign_schedule')
                    ->where('notification_campaign_id', $campaign->id)
                    ->get()
                    ->keyBy('id');

                // IDs received from frontend
                $receivedScheduleIds = [];

                foreach ($request->schedules as $schedule) {

                    if (!empty($schedule['id'])) {

                        $scheduleId = (int) $schedule['id'];

                        // Security check: make sure this schedule belongs to this campaign
                        if (!$existingSchedules->has($scheduleId)) {
                            continue;
                        }

                        $receivedScheduleIds[] = $scheduleId;
                        $existing = $existingSchedules->get($scheduleId);
                        $dateChanged = $existing->schedule_date != $schedule['schedule_date'];
                        $startTimeChanged = substr($existing->start_time, 0, 5) != substr($schedule['start_time'], 0, 5);
                        $endTimeChanged = substr($existing->end_time, 0, 5) != substr($schedule['end_time'], 0, 5);

                        if ($dateChanged || $startTimeChanged || $endTimeChanged) {

                            DB::table('notification_campaign_schedule')
                                ->where('id', $scheduleId)
                                ->where('notification_campaign_id', $campaign->id)
                                ->update([
                                    'schedule_date' => $schedule['schedule_date'],
                                    'start_time'    => $schedule['start_time'],
                                    'end_time'      => $schedule['end_time'],
                                ]);

                            Log::info('Campaign schedule updated', [
                                'schedule_id' => $scheduleId,
                                'campaign_id' => $campaign->id,
                            ]);
                        } else {

                            Log::info('Campaign schedule unchanged', [
                                'schedule_id' => $scheduleId,
                                'campaign_id' => $campaign->id,
                            ]);
                        }
                    } else {



                        $newScheduleId = DB::table('notification_campaign_schedule')
                            ->insertGetId([
                                'notification_campaign_id' => $campaign->id,
                                'schedule_date'            => $schedule['schedule_date'],
                                'start_time'               => $schedule['start_time'],
                                'end_time'                 => $schedule['end_time'],
                                'created_at'               => now(),
                                'created_by'               => $request->created_by,
                            ]);

                        $receivedScheduleIds[] = $newScheduleId;

                        Log::info('New campaign schedule created', [
                            'schedule_id' => $newScheduleId,
                            'campaign_id' => $campaign->id,
                        ]);
                    }
                }

                $idsToDelete = $existingSchedules
                    ->keys()
                    ->diff($receivedScheduleIds)
                    ->values()
                    ->toArray();

                if (!empty($idsToDelete)) {

                    DB::table('notification_campaign_schedule')
                        ->where('notification_campaign_id', $campaign->id)
                        ->whereIn('id', $idsToDelete)
                        ->delete();

                    Log::info('Campaign schedules deleted', [
                        'campaign_id' => $campaign->id,
                        'deleted_ids' => $idsToDelete,
                    ]);
                }
            } else {


                DB::table('notification_campaign_schedule')
                    ->where('notification_campaign_id', $campaign->id)
                    ->delete();
            }

            DB::commit();

            $schedules = DB::table('notification_campaign_schedule')
                ->where('notification_campaign_id', $campaign->id)
                ->orderBy('schedule_date')
                ->orderBy('start_time')
                ->get();

            $custom = DB::table('notification_campaign_custom')
                ->where('campaign_id', $campaign->id)
                ->first();

            return response()->json([
                'status' => 1,
                'message' => 'Campaign Notification Updated Successfully',
                'data' => [
                    'campaign' => $campaign,
                    'schedules' => $schedules,
                    'custom' => $custom
                ]
            ], Response::HTTP_OK);
        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Campaign update failed', [
                'campaign_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all()
            ]);

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllCampaignNotificationData(Request $request)
    {
        try {

            $query = DB::table('notification_campaigns as nc')
                ->leftJoin('user as cu', 'cu.id', '=', 'nc.created_by')
                ->leftJoin('user as uu', 'uu.id', '=', 'nc.updated_by')
                ->select(
                    'nc.id',
                    'nc.campaign_name',
                    'nc.title',
                    'nc.message',
                    'nc.image',
                    'nc.type',
                    'nc.active_status',
                    'nc.total_users',
                    'nc.processed_users',
                    'nc.success_users',
                    'nc.failed_users',
                    'nc.target_type',
                    'nc.schedule_type',
                    'nc.schedule_minutes',
                    'nc.schedule_at',
                    'nc.is_completed',
                    'nc.started_at',
                    'nc.completed_at',
                    'nc.created_by',
                    'cu.name as created_by_name',
                    'nc.updated_by',
                    'uu.name as updated_by_name',
                    'nc.created_at',
                    'nc.updated_at'
                );

            if (!empty($request->name)) {
                $query->where(function ($q) use ($request) {
                    $q->where('nc.campaign_name', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('nc.title', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('nc.message', 'LIKE', '%' . $request->name . '%');
                });
            }

            // Status
            if ($request->filled('status')) {
                $query->where('nc.active_status', $request->status);
            }

            // Type
            if ($request->filled('type')) {
                $query->where('nc.type', $request->type);
            }

            // Target Type
            if ($request->filled('target_type')) {
                $query->where('nc.target_type', $request->target_type);
            }

            // Schedule Type
            if ($request->filled('schedule_type')) {
                $query->where('nc.schedule_type', $request->schedule_type);
            }

            $campaigns = $query
                ->orderBy('nc.id', 'DESC')
                ->paginate($request->rows_number ?? 10);

            return response()->json([
                'status' => 1,
                'message' => Config::get('constants.RECORD_FETCHED'),
                'data' => $campaigns
            ]);
        } catch (Exception $e) {

            Log::error($e);

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getCampaignNotification($id)
    {
        $campaign = CampaignNotification::find($id);

        if (!$campaign) {
            return response()->json([
                'status' => 0,
                'message' => 'Campaign Notification Not Found'
            ], 404);
        }

        $schedules = DB::table('notification_campaign_schedule')
            ->where('notification_campaign_id', $campaign->id)
            ->orderBy('schedule_date')
            ->orderBy('start_time')
            ->get();

        $custom = DB::table('notification_campaign_custom')
            ->where('campaign_id', $campaign->id)
            ->first();

        return response()->json([
            'status' => 1,

            'data' => [
                'campaign' => $campaign,
                'schedules' => $schedules,
                'custom' => $custom
            ]
        ]);
    }

    public function changeStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:notification_campaigns,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $campaign = CampaignNotification::find($request->id);

            if (!$campaign) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Campaign Notification Not Found'
                ], Response::HTTP_NOT_FOUND);
            }

            $campaign->active_status = ($campaign->active_status == 1) ? 0 : 1;
            $campaign->updated_by = $request->created_by;
            $campaign->save();
        } catch (Exception $e) {

            Log::error($e);

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => 1,
            'message' => 'Campaign Notification Status Updated'
        ], Response::HTTP_ACCEPTED);
    }

    public function getNotificationCategories(Request $request)
    {
        try {

            $categories = DB::table('notification_category')
                ->select(
                    'id',
                    'category_name',
                    'category_code'
                )
                ->where('status', 1)
                ->orderBy('id', 'ASC')
                ->get();

            return response()->json([
                'status' => 1,
                'message' => 'Notification categories fetched successfully',
                'data' => $categories
            ], Response::HTTP_OK);
        } catch (Exception $e) {

            Log::error($e);

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getOperators()
    {
        $operators = DB::table('bus_operator')
            ->select('id', 'organisation_name')
            ->where('status', 1)
            ->orderBy('organisation_name', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $operators
        ]);
    }

    public function getLocations()
    {
        $locations = DB::table('location')
            ->select('id', 'name')
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $locations
        ]);
    }

    public function getActiveCoupons()
    {
        $coupons = DB::table('slider')
            ->join('coupon', 'slider.coupon_id', '=', 'coupon.id')
            ->where('slider.status', 1)
            ->select(
                'coupon.id',
                'coupon.coupon_type_id',
                'coupon.coupon_code',
                'coupon.coupon_title'
            )
            ->get();

        return response()->json([
            'status' => true,
            'data' => $coupons
        ]);
    }

    public function getSelectedTargetUsers(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'duration' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $duration = (int) $request->duration;

            $fromDate = now()->subDays($duration);

            $users = Users::query()
                ->select(
                    'id',
                    'name',
                    'email',
                    'phone',
                    'fcm_id',
                    'updated_at'
                )
                ->whereNotNull('fcm_id')
                ->where('fcm_id', '!=', '')
                ->whereNotNull('updated_at')
                ->where('updated_at', '>=', $fromDate)
                ->orderBy('updated_at', 'DESC')
                ->get();

            return response()->json([
                'status' => 1,
                'message' => 'Valid users fetched successfully',
                'count' => $users->count(),
                'data' => $users
            ], Response::HTTP_OK);
        } catch (\Exception $e) {

            Log::error('Failed to fetch selected target users', [
                'duration' => $request->duration,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
