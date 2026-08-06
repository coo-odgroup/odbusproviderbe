<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use ApiResponser;
use App\Models\User;
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
            'campaign_name'    => 'required|max:150',
            'title'            => 'required|max:255',
            'message'          => 'required',
            'type'             => 'required|in:PROMOTIONAL,TRANSACTIONAL,REMINDER,CUSTOM',
            'target_type'      => 'required|in:ALL,ACTIVE,INACTIVE,VERIFIED,CUSTOM',
            'schedule_type'    => 'required|in:IMMEDIATE,SCHEDULED,BEFORE_EVENT,AFTER_EVENT',
            'schedule_minutes' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $data = $request->only([
                'campaign_name',
                'title',
                'message',
                'type',
                'target_type',
                'schedule_type',
                'schedule_minutes'
            ]);

            if ($request->hasFile('image')) {

                $file = $request->file('image');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/campaign_notifications'), $fileName);
                $data['image'] = 'uploads/campaign_notifications/' . $fileName;
            }

            $data['active_status'] = 1;
            $data['created_by'] = $request->created_by;

            $response = CampaignNotification::create($data);

            return response()->json([
                'status' => 1,
                'message' => 'Success',
                'data' => $response
            ], Response::HTTP_OK);
        } catch (Exception $e) {

            Log::error($e);

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

    public function updateCampaignNotification(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'campaign_name'    => 'required|max:150',
            'title'            => 'required|max:255',
            'message'          => 'required',
            'type'             => 'required|in:PROMOTIONAL,TRANSACTIONAL,REMINDER,CUSTOM',
            'target_type'      => 'required|in:ALL,ACTIVE,INACTIVE,VERIFIED,CUSTOM',
            'schedule_type'    => 'required|in:IMMEDIATE,SCHEDULED,BEFORE_EVENT,AFTER_EVENT',
            'schedule_minutes' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $campaign = CampaignNotification::find($id);

            if (!$campaign) {
                return response()->json([
                    'status' => 0,
                    'message' => "Campaign Notification Not Found"
                ], Response::HTTP_NOT_FOUND);
            }

            $campaign->campaign_name = $request->campaign_name;
            $campaign->title = $request->title;
            $campaign->message = $request->message;
            $campaign->type = $request->type;
            $campaign->target_type = $request->target_type;
            $campaign->schedule_type = $request->schedule_type;
            $campaign->schedule_minutes = $request->schedule_minutes;
            if (!User::where('id', $request->created_by)->exists()) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Invalid User'
                ], Response::HTTP_BAD_REQUEST);
            }

            $campaign->updated_by = $request->created_by;

            if ($request->hasFile('image')) {

                if (!empty($campaign->image) && file_exists(public_path($campaign->image))) {
                    unlink(public_path($campaign->image));
                }

                $file = $request->file('image');

                $fileName = time() . '_' . $file->getClientOriginalName();

                $file->move(public_path('uploads/campaign_notifications'), $fileName);

                $campaign->image = 'uploads/campaign_notifications/' . $fileName;
            }

            $campaign->save();

            return response()->json([
                'status' => 1,
                'message' => 'Campaign Notification Updated',
                'data' => $campaign
            ], Response::HTTP_OK);
        } catch (Exception $e) {

            Log::error($e);
            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
}
