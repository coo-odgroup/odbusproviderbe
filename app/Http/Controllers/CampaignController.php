<?php

namespace App\Http\Controllers;

use App\Models\CampaignMaster;
use App\Models\Campaign;
use App\Models\CampaignRoutes;
use App\Models\CampaignServices;
use App\Models\CampaignActiveDays;
use App\Models\CampaignExcludedDates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CampaignController extends Controller
{
    // Campaign Master Start
    public function campaignmasterList(Request $request)
    {
        $status         = true;
        $statusCode     = 200;
        $response       = [];
        $message        = '';

        try {
            $order = $request->order ?? 'DESC';
            $limit = $request->limit ?? 10;

            $result = CampaignMaster::orderBy('created_at', $order)->limit($limit)->get();

            if (!empty($result)) {
                $response = $result;
                $message  = Config::get('constants.RECORD_FETCHED');
            } else {
                $message = Config::get('constants.RECORD_NOT_FOUND');
            }
        } catch (\Throwable $th) {
            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status'         => $status,
            'statusCode'     => $statusCode,
            'message'        => $message,
            'data'       => $response,
        ], $statusCode);
    }

    public function campaignmasterCreate(Request $request)
    {
        $status     = true;
        $statusCode = 200;
        $message    = '';

        try {
            $validator = Validator::make($request->all(), [
                'campaign_name' => 'required|string|max:255|unique:campaign_master,campaign_name',
                'short_desc' => 'required|string',
                'full_desc' => 'required|string',
                'start' => 'required|string',
                'stop' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 422,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors()
                ], 422);
            }

            CampaignMaster::create([
                'campaign_name' => $request->campaign_name,
                'short_desc' => $request->short_desc,
                'full_desc' => $request->full_desc,
                'start' => $request->start,
                'stop' => $request->stop,
                'created_by' => 1
            ]);

            $message = Config::get('constants.RECORD_ADDED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }

    public function campaignmasterUpdate(Request $request, $id)
    {
        $status     = true;
        $statusCode = 200;
        $message    = '';

        try {
            // Check record exists
            $record = CampaignMaster::find($id);

            if (!$record) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 404,
                    'message'    => Config::get('constants.RECORD_NOT_FOUND')
                ], 404);
            }

            // Validation
            $validator = Validator::make($request->all(), [
                'campaign_name' => 'required|string|max:255|unique:campaign_master,campaign_name,' . $id,
                'short_desc'    => 'required|string',
                'full_desc'     => 'required|string',
                'start'         => 'required|string',
                'stop'          => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 422,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Update
            $record->update([
                'campaign_name' => $request->campaign_name,
                'short_desc' => $request->short_desc,
                'full_desc' => $request->full_desc,
                'start' => $request->start,
                'stop' => $request->stop,
                'updated_by' => 1
            ]);

            DB::commit();

            $message = Config::get('constants.RECORD_UPDATED');
        } catch (\Throwable $th) {

            DB::rollBack();

            Log::error($th);

            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }

    public function campaignmasterDelete($id)
    {
        $status     = true;
        $statusCode = 200;
        $message    = '';

        try {
            // Check record exists
            $record = CampaignMaster::find($id);

            if (!$record) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 404,
                    'message'    => Config::get('constants.RECORD_NOT_FOUND')
                ], 404);
            }

            // Soft delete
            $record->update([
                'deleted_at' => now()
            ]);

            $message = Config::get('constants.RECORD_REMOVED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }
    // Campaign Master End

    // Campaign Start
    public function campaignList(Request $request)
    {
        $status         = true;
        $statusCode     = 200;
        $response       = [];
        $message        = '';

        try {
            $order = $request->order ?? 'DESC';
            $limit = $request->limit ?? 10;

            $result = Campaign::with([
                'campaignMaster:id,campaign_name',
                'operator:id,operator_name'
            ])
                ->orderBy('created_at', $order)
                ->limit($limit)
                ->get();

            if (!empty($result)) {
                $response = $result;
                $message  = Config::get('constants.RECORD_FETCHED');
            } else {
                $message = Config::get('constants.RECORD_NOT_FOUND');
            }
        } catch (\Throwable $th) {
            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status'         => $status,
            'statusCode'     => $statusCode,
            'message'        => $message,
            'data'       => $response,
        ], $statusCode);
    }

    public function campaignCreate(Request $request)
    {
        $status     = true;
        $statusCode = 200;
        $message    = '';

        try {
            $validator = Validator::make($request->all(), [
                'operator_id' => 'required|integer|exists:user,id',
                'campaign_master_id' => 'required|integer|exists:campaign_master,id',
                'offer_type' => 'required|integer',
                'offer_value' => 'required|integer',
                'min_ticket_value' => 'required|integer',
                'services' => 'required|integer',
                'auto_renewwal' => 'required|boolean',
                'validity_type' => 'required|string|in:DATE_RANGE,DURATION',
                'start_date' => 'required_if:validity_type,DATE_RANGE',
                'end_date'   => 'required_if:validity_type,DATE_RANGE',
                'duration_value' => 'required_if:validity_type,DURATION|integer',
                'duration_unit'  => 'required_if:validity_type,DURATION|in:DAY,WEEK,MONTH'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 422,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors()
                ], 422);
            }

            Campaign::create([
                'operator_id' => $request->operator_id,
                'campaign_master_id' => $request->campaign_master_id,
                'offer_type' => $request->offer_type,
                'offer_value' => $request->offer_value,
                'min_ticket_value' => $request->min_ticket_value,
                'services' => $request->services,
                'auto_renewwal' => $request->auto_renewwal,
                'validity_type' => $request->validity_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'duration_value' => $request->duration_value,
                'duration_unit' => $request->duration_unit
            ]);

            $message = Config::get('constants.RECORD_ADDED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }

    public function campaignUpdate(Request $request, $id)
    {
        $status     = true;
        $statusCode = 200;
        $message    = '';

        try {
            // Check record exists
            $record = Campaign::find($id);

            if (!$record) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 404,
                    'message'    => Config::get('constants.RECORD_NOT_FOUND')
                ], 404);
            }

            // Validation
            $validator = Validator::make($request->all(), [
                'operator_id' => 'required|integer|exists:user,id',
                'campaign_master_id' => 'required|integer|exists:campaign_master,id',
                'offer_type' => 'required|integer',
                'offer_value' => 'required|integer',
                'min_ticket_value' => 'required|integer',
                'services' => 'required|integer',
                'auto_renewwal' => 'required|boolean',
                'validity_type' => 'required|string|in:DATE_RANGE,DURATION',
                'start_date' => 'required_if:validity_type,DATE_RANGE',
                'end_date'   => 'required_if:validity_type,DATE_RANGE',
                'duration_value' => 'required_if:validity_type,DURATION|integer',
                'duration_unit'  => 'required_if:validity_type,DURATION|in:DAY,WEEK,MONTH'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 422,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors()
                ], 422);
            }

            // Update
            $record->update([
                'operator_id' => $request->operator_id,
                'campaign_master_id' => $request->campaign_master_id,
                'offer_type' => $request->offer_type,
                'offer_value' => $request->offer_value,
                'min_ticket_value' => $request->min_ticket_value,
                'services' => $request->services,
                'auto_renewwal' => $request->auto_renewwal,
                'validity_type' => $request->validity_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'duration_value' => $request->duration_value,
                'duration_unit' => $request->duration_unit
            ]);

            $message = Config::get('constants.RECORD_UPDATED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }

    public function campaignDelete($id)
    {
        $status     = true;
        $statusCode = 200;
        $message    = '';

        try {
            // Check record exists
            $record = Campaign::find($id);

            if (!$record) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 404,
                    'message'    => Config::get('constants.RECORD_NOT_FOUND')
                ], 404);
            }

            // Soft delete
            $record->update([
                'deleted_at' => now()
            ]);

            $message = Config::get('constants.RECORD_REMOVED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }
    // Campaign End

    // Campaign Routes Start
    public function campaignRoutesList(Request $request)
    {
        $status         = true;
        $statusCode     = 200;
        $response       = [];
        $message        = '';

        try {
            $order = $request->order ?? 'DESC';
            $limit = $request->limit ?? 10;

            $result = CampaignRoutes::with([
                'source:id,name',
                'destination:id,name'
            ])
                ->orderBy('created_at', $order)
                ->limit($limit)
                ->get();

            if (!empty($result)) {
                $response = $result;
                $message  = Config::get('constants.RECORD_FETCHED');
            } else {
                $message = Config::get('constants.RECORD_NOT_FOUND');
            }
        } catch (\Throwable $th) {
            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status'         => $status,
            'statusCode'     => $statusCode,
            'message'        => $message,
            'data'       => $response,
        ], $statusCode);
    }

    public function campaignRoutesCreate(Request $request)
    {
        $status     = true;
        $statusCode = 200;
        $message    = '';

        try {
            $validator = Validator::make($request->all(), [
                'campaign_id' => 'required|integer|exists:campaign,id',
                'src_id'      => 'required|integer',
                'dest_id'     => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 422,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors()
                ], 422);
            }

            CampaignRoutes::create([
                'campaign_id' => $request->campaign_id,
                'src_id' => $request->src_id,
                'dest_id' => $request->dest_id
            ]);

            $message = Config::get('constants.RECORD_ADDED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }

    public function campaignRoutesUpdate(Request $request, $id)
    {
        $status     = true;
        $statusCode = 200;
        $message    = '';

        try {
            // Check record exists
            $record = CampaignRoutes::find($id);

            if (!$record) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 404,
                    'message'    => Config::get('constants.RECORD_NOT_FOUND')
                ], 404);
            }

            // Validation
            $validator = Validator::make($request->all(), [
                'campaign_id' => 'required|integer|exists:campaign,id',
                'src_id'      => 'required|integer',
                'dest_id'     => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 422,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors()
                ], 422);
            }

            // Update
            $record->update([
                'campaign_id' => $request->campaign_id,
                'src_id'      => $request->src_id,
                'dest_id'    => $request->dest_id
            ]);

            $message  = Config::get('constants.RECORD_UPDATED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }

    public function campaignRoutesDelete($id)
    {
        $status     = true;
        $statusCode = 200;
        $message    = '';

        try {
            // Check record exists
            $record = CampaignRoutes::find($id);

            if (!$record) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 404,
                    'message'    => Config::get('constants.RECORD_NOT_FOUND')
                ], 404);
            }

            // Soft delete
            $record->update([
                'deleted_at' => now()
            ]);

            $message = Config::get('constants.RECORD_REMOVED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }
    // Campaign Routes End

    // Campaign Services Start
    public function campaignServicesList(Request $request)
    {
        $status         = true;
        $statusCode     = 200;
        $response       = [];
        $message        = '';

        try {
            $order = $request->order ?? 'DESC';
            $limit = $request->limit ?? 10;

            $result = CampaignServices::with(['campaign', 'route', 'bus'])
                ->orderBy('created_at', $order)
                ->limit($limit)
                ->get();

            if (!empty($result)) {
                $response = $result;
                $message  = Config::get('constants.RECORD_FETCHED');
            } else {
                $message = Config::get('constants.RECORD_NOT_FOUND');
            }
        } catch (\Throwable $th) {
            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status'         => $status,
            'statusCode'     => $statusCode,
            'message'        => $message,
            'data'       => $response,
        ], $statusCode);
    }

    public function campaignServicesCreate(Request $request)
    {
        $status     = true;
        $statusCode = 200;
        $message    = '';

        try {
            $validator = Validator::make($request->all(), [
                'campaign_id' => 'required|integer|exists:campaign,id',
                'campaign_routes_id' => 'required|integer|exists:campaign_routes,id',
                'bus_id' => 'required|integer|exists:bus,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 422,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors()
                ], 422);
            }

            CampaignServices::create([
                'campaign_id' => $request->campaign_id,
                'campaign_routes_id' => $request->campaign_routes_id,
                'bus_id' => $request->bus_id
            ]);

            $message = Config::get('constants.RECORD_ADDED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }

    public function campaignServicesUpdate(Request $request, $id)
    {
        $status     = true;
        $statusCode = 200;
        $message    = '';

        try {
            // Check record exists
            $record = CampaignServices::find($id);

            if (!$record) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 404,
                    'message'    => Config::get('constants.RECORD_NOT_FOUND')
                ], 404);
            }

            // Validation
            $validator = Validator::make($request->all(), [
                'campaign_id' => 'required|integer|exists:campaign,id',
                'campaign_routes_id' => 'required|integer|exists:campaign_routes,id',
                'bus_id' => 'required|integer|exists:bus,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 422,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors()
                ], 422);
            }

            // Update
            $record->update([
                'campaign_id' => $request->campaign_id,
                'campaign_routes_id' => $request->campaign_routes_id,
                'bus_id' => $request->bus_id
            ]);

            $message  = Config::get('constants.RECORD_UPDATED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }

    public function campaignServicesDelete($id)
    {
        $status     = true;
        $statusCode = 200;
        $message    = '';

        try {
            // Check record exists
            $record = CampaignServices::find($id);

            if (!$record) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 404,
                    'message'    => Config::get('constants.RECORD_NOT_FOUND')
                ], 404);
            }

            // Soft delete
            $record->update([
                'deleted_at' => now()
            ]);

            $message = Config::get('constants.RECORD_REMOVED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }
    // Campaign Services End

    // Campaign Active Days Start
    public function campaignActiveDaysList(Request $request)
    {
        $status         = true;
        $statusCode     = 200;
        $response       = [];
        $message        = '';

        try {
            $order = $request->order ?? 'DESC';
            $limit = $request->limit ?? 10;

            $result = CampaignActiveDays::with(['campaign'])
                ->orderBy('created_at', $order)
                ->limit($limit)
                ->get();

            if (!empty($result)) {
                $response = $result;
                $message  = Config::get('constants.RECORD_FETCHED');
            } else {
                $message = Config::get('constants.RECORD_NOT_FOUND');
            }
        } catch (\Throwable $th) {
            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status'         => $status,
            'statusCode'     => $statusCode,
            'message'        => $message,
            'data'       => $response,
        ], $statusCode);
    }

    public function campaignActiveDaysCreate(Request $request)
    {
        $status     = true;
        $statusCode = 200;
        $message    = '';

        try {
            $validator = Validator::make($request->all(), [
                'campaign_id' => 'required|integer|exists:campaign,id',
                'day_of_week' => 'required|integer|between:1,7'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 422,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors()
                ], 422);
            }

            $exists = CampaignActiveDays::where('campaign_id', $request->campaign_id)
                ->where('day_of_week', $request->day_of_week)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 422,
                    'message'    => 'This day is already added for this campaign'
                ], 422);
            }

            CampaignActiveDays::create([
                'campaign_id' => $request->campaign_id,
                'day_of_week' => $request->day_of_week
            ]);

            $message = Config::get('constants.RECORD_ADDED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status' => $status,
            'statusCode' => $statusCode,
            'message'    => $message
        ], $statusCode);
    }

    public function campaignActiveDaysUpdate(Request $request, $id)
    {
        $status     = true;
        $statusCode = 200;
        $message    = '';

        try {
            // Check record exists
            $record = CampaignActiveDays::find($id);

            if (!$record) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 404,
                    'message'    => Config::get('constants.RECORD_NOT_FOUND')
                ], 404);
            }

            // Validation
            $validator = Validator::make($request->all(), [
                'campaign_id' => 'required|integer|exists:campaign,id',
                'day_of_week' => 'required|integer|between:1,7'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 422,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors()
                ], 422);
            }

            $exists = CampaignActiveDays::where('campaign_id', $request->campaign_id)
                ->where('day_of_week', $request->day_of_week)
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 422,
                    'message'    => 'This day is already added for this campaign'
                ], 422);
            }

            // Update
            $record->update([
                'campaign_id' => $request->campaign_id,
                'day_of_week' => $request->day_of_week
            ]);

            $message = Config::get('constants.RECORD_UPDATED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status' => $status,
            'statusCode' => $statusCode,
            'message'    => $message
        ], $statusCode);
    }

    public function campaignActiveDaysDelete($id)
    {
        $status     = true;
        $statusCode = 200;
        $message    = '';

        try {
            // Check record exists
            $record = CampaignActiveDays::find($id);

            if (!$record) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 404,
                    'message'    => Config::get('constants.RECORD_NOT_FOUND')
                ], 404);
            }

            // Soft delete
            $record->update([
                'deleted_at' => now()
            ]);

            $message = Config::get('constants.RECORD_REMOVED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }
    // Campaign Active Days End

    // Campaign Excluded Dates Start
    public function campaignExcludedDatesList(Request $request)
    {
        $status         = true;
        $statusCode     = 200;
        $response       = [];
        $message        = '';

        try {
            $order = $request->order ?? 'DESC';
            $limit = $request->limit ?? 10;

            $result = CampaignExcludedDates::with(['campaign'])
                ->orderBy('created_at', $order)
                ->limit($limit)
                ->get();

            if (!empty($result)) {
                $response = $result;
                $message  = Config::get('constants.RECORD_FETCHED');
            } else {
                $message = Config::get('constants.RECORD_NOT_FOUND');
            }
        } catch (\Throwable $th) {
            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status'         => $status,
            'statusCode'     => $statusCode,
            'message'        => $message,
            'data'       => $response,
        ], $statusCode);
    }

    public function campaignExcludedDatesCreate(Request $request)
    {
        $status     = true;
        $statusCode = 200;
        $message    = '';

        try {
            $validator = Validator::make($request->all(), [
                'campaign_id' => 'required|integer|exists:campaign,id',
                'excluded_date' => 'required|date|distinct'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 422,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors()
                ], 422);
            }

            $exists = CampaignExcludedDates::where('campaign_id', $request->campaign_id)
                ->where('excluded_date', $request->excluded_date)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 422,
                    'message'    => 'This Date is already added for this campaign'
                ], 422);
            }

            CampaignExcludedDates::create([
                'campaign_id' => $request->campaign_id,
                'excluded_date' => $request->excluded_date
            ]);

            $message = Config::get('constants.RECORD_ADDED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status' => $status,
            'statusCode' => $statusCode,
            'message'    => $message
        ], $statusCode);
    }

    public function campaignExcludedDatesUpdate(Request $request, $id)
    {
        $status     = true;
        $statusCode = 200;
        $message    = '';

        try {
            // Check record exists
            $record = CampaignExcludedDates::find($id);

            if (!$record) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 404,
                    'message'    => Config::get('constants.RECORD_NOT_FOUND')
                ], 404);
            }

            // Validation
            $validator = Validator::make($request->all(), [
                'campaign_id' => 'required|integer|exists:campaign,id',
                'excluded_date' => 'required|date|distinct'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 422,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors()
                ], 422);
            }

            $exists = CampaignExcludedDates::where('campaign_id', $request->campaign_id)
                ->where('excluded_date', $request->excluded_date)
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 422,
                    'message'    => 'This Date is already added for this campaign'
                ], 422);
            }

            // Update
            $record->update([
                'campaign_id' => $request->campaign_id,
                'excluded_date' => $request->excluded_date
            ]);

            $message = Config::get('constants.RECORD_UPDATED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status' => $status,
            'statusCode' => $statusCode,
            'message'    => $message
        ], $statusCode);
    }

    public function campaignExcludedDatesDelete($id)
    {
        $status     = true;
        $statusCode = 200;
        $message    = '';

        try {
            // Check record exists
            $record = CampaignExcludedDates::find($id);

            if (!$record) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 404,
                    'message'    => Config::get('constants.RECORD_NOT_FOUND')
                ], 404);
            }

            // Soft delete
            $record->update([
                'deleted_at' => now()
            ]);

            $message = Config::get('constants.RECORD_REMOVED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }
    // Campaign Excluded Dates End

    // Campaign Discount Start
    public function campaignDiscountCreate(Request $request)
    {
        $status     = true;
        $statusCode = 200;
        $message    = '';

        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'operator_id' => ['required', 'integer', 'exists:user,id'],
                    'campaign_master_id' => ['required', 'integer', 'exists:campaign_master,id'],
                    'offer_type' => ['required', 'integer'],
                    'offer_value' => ['required', 'integer', 'lt:min_ticket_value'],
                    'min_ticket_value' => ['required', 'integer', 'gt:offer_value'],
                    'services' => ['required', 'integer'],
                    'auto_renewwal' => ['required', 'boolean'],

                    'validity_type' => ['required', 'in:DATE_RANGE,DURATION'],

                    'start_date' => ['required_if:validity_type,DATE_RANGE', 'date'],
                    'end_date' => ['required_if:validity_type,DATE_RANGE', 'date', 'after_or_equal:start_date'],

                    'duration_value' => ['required_if:validity_type,DURATION', 'integer', 'min:1'],
                    'duration_unit' => ['required_if:validity_type,DURATION', 'in:DAY,WEEK,MONTH'],

                    'src_id' => ['nullable', 'integer', 'exists:location,id', 'different:dest_id'],
                    'dest_id' => ['nullable', 'integer', 'exists:location,id', 'different:src_id'],

                    'bus_id' => ['nullable', 'array', 'required_if:services,1'],
                    'bus_id.*' => ['integer', 'exists:bus,id'],

                    'day_of_week' => ['nullable', 'array'],
                    'day_of_week.*' => ['integer', 'between:1,7', 'distinct'],

                    'excluded_date' => ['nullable', 'array'],
                    'excluded_date.*' => ['date', 'distinct']
                ],
                [
                    'operator_id.required' => 'Operator is required.',
                    'operator_id.exists' => 'Invalid operator selected.',

                    'campaign_master_id.required' => 'Campaign master is required.',
                    'campaign_master_id.exists' => 'Invalid campaign master.',

                    'offer_value.lt' => 'Offer value must be less than minimum ticket value.',
                    'min_ticket_value.gt' => 'Minimum ticket value must be greater than offer value.',

                    'validity_type.in' => 'Validity type must be DATE_RANGE or DURATION.',

                    'start_date.required_if' => 'Start date is required when validity type is DATE_RANGE.',
                    'end_date.required_if' => 'End date is required when validity type is DATE_RANGE.',

                    'duration_value.required_if' => 'Duration value is required when validity type is DURATION.',
                    'duration_unit.required_if' => 'Duration unit is required when validity type is DURATION.',

                    'src_id.different' => 'Source and destination cannot be same.',
                    'dest_id.different' => 'Destination and source cannot be same.',

                    'bus_id.required_if' => 'Bus must be selected when service type is Bus.',

                    'day_of_week.*.distinct' => 'Duplicate days are not allowed.',
                    'day_of_week.*.between' => 'Day of week must be between 1 and 7.',

                    'excluded_date.*.distinct' => 'Duplicate excluded dates are not allowed.'
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'statusCode' => 422,
                    'message' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            DB::transaction(function () use ($request) {
                $campaign = Campaign::create([
                    'operator_id' => $request->operator_id,
                    'campaign_master_id' => $request->campaign_master_id,
                    'offer_type' => $request->offer_type,
                    'offer_value' => $request->offer_value,
                    'min_ticket_value' => $request->min_ticket_value,
                    'services' => $request->services,
                    'auto_renewwal' => $request->auto_renewwal,
                    'validity_type' => $request->validity_type,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'duration_value' => $request->duration_value,
                    'duration_unit' => $request->duration_unit,
                    'created_by' => 1
                ]);

                if ($request->filled('bus_id') && ($request->filled('src_id') || $request->filled('dest_id'))) {

                    $routes = [];

                    foreach ($request->bus_id as $busId) {
                        $routes[] = [
                            'campaign_id' => $campaign->id,
                            'src_id' => $request->src_id,
                            'dest_id' => $request->dest_id,
                            'bus_id' => $busId,
                            'created_at' => now(),
                            'created_by' => 1
                        ];
                    }

                    CampaignRoutes::insert($routes);
                }

                if ($request->filled('day_of_week')) {

                    $days = [];

                    foreach ($request->day_of_week as $dayOfWeek) {
                        $days[] = [
                            'campaign_id' => $campaign->id,
                            'day_of_week' => $dayOfWeek,
                            'created_at' => now(),
                            'created_by' => 1
                        ];
                    }

                    CampaignActiveDays::insert($days);
                }

                if ($request->filled('excluded_date')) {

                    $dates = [];

                    foreach ($request->excluded_date as $excludedDate) {
                        $dates[] = [
                            'campaign_id' => $campaign->id,
                            'excluded_date' => $excludedDate,
                            'created_at' => now(),
                            'created_by' => 1
                        ];
                    }

                    CampaignExcludedDates::insert($dates);
                }
            });

            DB::commit();
            DB::disconnect();

            $message = Config::get('constants.RECORD_ADDED');
        } catch (\Throwable $th) {

            DB::rollBack();

            Log::error($th);

            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }

    public function campaignDiscountUpdate(Request $request, $id)
    {
        $status     = true;
        $statusCode = 200;
        $message    = '';

        try {

            $validator = Validator::make(
                $request->all(),
                [
                    'operator_id' => ['required', 'integer', 'exists:user,id'],
                    'campaign_master_id' => ['required', 'integer', 'exists:campaign_master,id'],
                    'offer_type' => ['required', 'integer'],
                    'offer_value' => ['required', 'integer', 'lt:min_ticket_value'],
                    'min_ticket_value' => ['required', 'integer', 'gt:offer_value'],
                    'services' => ['required', 'integer'],
                    'auto_renewwal' => ['required', 'boolean'],

                    'validity_type' => ['required', 'in:DATE_RANGE,DURATION'],

                    'start_date' => ['required_if:validity_type,DATE_RANGE', 'date'],
                    'end_date' => ['required_if:validity_type,DATE_RANGE', 'date', 'after_or_equal:start_date'],

                    'duration_value' => ['required_if:validity_type,DURATION', 'integer', 'min:1'],
                    'duration_unit' => ['required_if:validity_type,DURATION', 'in:DAY,WEEK,MONTH'],

                    'src_id' => ['nullable', 'integer', 'exists:location,id', 'different:dest_id'],
                    'dest_id' => ['nullable', 'integer', 'exists:location,id', 'different:src_id'],

                    'bus_id' => ['nullable', 'array', 'required_if:services,1'],
                    'bus_id.*' => ['integer', 'exists:bus,id'],

                    'day_of_week' => ['nullable', 'array'],
                    'day_of_week.*' => ['integer', 'between:1,7', 'distinct'],

                    'excluded_date' => ['nullable', 'array'],
                    'excluded_date.*' => ['date', 'distinct']
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'statusCode' => 422,
                    'message' => $validator->errors()
                ], 422);
            }

            DB::transaction(function () use ($request, $id) {

                $campaign = Campaign::lockForUpdate()->find($id);

                if (!$campaign) {
                    return response()->json([
                        'status'     => false,
                        'statusCode' => 404,
                        'message'    => Config::get('constants.RECORD_NOT_FOUND')
                    ], 404);
                }

                // Update main record
                $campaign->update([
                    'operator_id' => $request->operator_id,
                    'campaign_master_id' => $request->campaign_master_id,
                    'offer_type' => $request->offer_type,
                    'offer_value' => $request->offer_value,
                    'min_ticket_value' => $request->min_ticket_value,
                    'services' => $request->services,
                    'auto_renewwal' => $request->auto_renewwal,
                    'validity_type' => $request->validity_type,
                    'start_date' => $request->validity_type === 'DATE_RANGE' ? $request->start_date : null,
                    'end_date' => $request->validity_type === 'DATE_RANGE' ? $request->end_date : null,
                    'duration_value' => $request->validity_type === 'DURATION' ? $request->duration_value : null,
                    'duration_unit' => $request->validity_type === 'DURATION' ? $request->duration_unit : null,
                    'updated_by' => 1
                ]);

                // Clear old mappings
                DB::transaction(function () use ($id) {

                    DB::table('campaign_routes')
                        ->where('campaign_id', $id)
                        ->delete();

                    DB::table('campaign_active_days')
                        ->where('campaign_id', $id)
                        ->delete();

                    DB::table('campaign_excluded_dates')
                        ->where('campaign_id', $id)
                        ->delete();
                });

                // Insert routes
                if ($request->filled('bus_id') && ($request->filled('src_id') || $request->filled('dest_id'))) {

                    $routes = [];
                    foreach ($request->bus_id as $busId) {
                        $routes[] = [
                            'campaign_id' => $id,
                            'src_id' => $request->src_id,
                            'dest_id' => $request->dest_id,
                            'bus_id' => $busId,
                            'created_at' => now(),
                            'created_by' => 1
                        ];
                    }
                    CampaignRoutes::insert($routes);
                }

                // Insert active days
                if ($request->filled('day_of_week')) {
                    $days = [];
                    foreach ($request->day_of_week as $day) {
                        $days[] = [
                            'campaign_id' => $id,
                            'day_of_week' => $day,
                            'created_at' => now(),
                            'created_by' => 1
                        ];
                    }
                    CampaignActiveDays::insert($days);
                }

                // Insert excluded dates
                if ($request->filled('excluded_date')) {
                    $dates = [];
                    foreach ($request->excluded_date as $date) {
                        $dates[] = [
                            'campaign_id' => $id,
                            'excluded_date' => $date,
                            'created_at' => now(),
                            'created_by' => 1
                        ];
                    }
                    CampaignExcludedDates::insert($dates);
                }
            });

            $message = Config::get('constants.RECORD_UPDATED');
        } catch (\Throwable $th) {
            Log::error($th);
            $status = false;
            $statusCode = 500;
            $message = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message
        ], $statusCode);
    }
}
