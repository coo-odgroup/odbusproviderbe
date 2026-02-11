<?php

namespace App\Http\Controllers;

use App\Models\CampaignMaster;
use App\Models\Campaign;
use App\Models\CampaignRoutes;
use App\Models\CampaignServices;
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
            $search = $request->search ?? null;

            $result = CampaignMaster::orderBy('created_at', $order)->limit($limit)->get();

            if (!empty($result)) {
                $response = $result;
                $message  = Config::get('constants.RECORD_FETCHED');
            } else {
                $message = Config::get('constants.RECORD_NOT_FOUND');
            }
        } catch (\Throwable $th) {
            $status     = false;
            $statusCode = 500;
            $message    = Config::get('constants.EXCEPTION_ERROR');
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
        $response   = [];
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
                    'errors'     => $validator->errors(),
                ], 422);
            }

            CampaignMaster::create([
                'campaign_name' => $request->campaign_name,
                'short_desc' => $request->short_desc,
                'full_desc' => $request->full_desc,
                'start' => $request->start,
                'stop' => $request->stop
            ]);

            $message  = Config::get('constants.RECORD_ADDED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status     = false;
            $statusCode = 500;
            $message    = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status'     => $status,
            'statusCode' => $statusCode,
            'message'    => $message,
        ], $statusCode);
    }

    public function campaignmasterUpdate(Request $request, $id)
    {
        $status     = true;
        $statusCode = 200;
        $response   = [];
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
                    'errors'     => $validator->errors(),
                ], 422);
            }

            // Update
            $record->update([
                'campaign_name' => $request->campaign_name,
                'short_desc'    => $request->short_desc,
                'full_desc'     => $request->full_desc,
                'start'         => $request->start,
                'stop'          => $request->stop
            ]);

            $message = Config::get('constants.RECORD_UPDATED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status     = false;
            $statusCode = 500;
            $message    = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status'     => $status,
            'statusCode' => $statusCode,
            'message'    => $message,
        ], $statusCode);
    }

    public function campaignmasterDelete($id)
    {
        $status     = true;
        $statusCode = 200;
        $response   = [];
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
                'deleted_at' => now(),
            ]);

            $message = Config::get('constants.RECORD_REMOVED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status     = false;
            $statusCode = 500;
            $message    = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status'     => $status,
            'statusCode' => $statusCode,
            'message'    => $message,
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
            $search = $request->search ?? null;

            // $result = Campaign::orderBy('created_at', $order)->limit($limit)->get();
            // $result = Campaign::join('campaign_master', 'campaign.campaign_master_id', '=', 'campaign_master.id')
            //       ->join('bus_operator', 'campaign.operator_id', '=', 'bus_operator.id')
            //       ->select(
            //           'campaign.*',
            //           'campaign_master.campaign_name',
            //           'bus_operator.operator_name'
            //       )
            //       ->orderBy('campaign.created_at', $order)
            //       ->limit($limit)
            //       ->get();

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
            $status     = false;
            $statusCode = 500;
            $message    = Config::get('constants.EXCEPTION_ERROR');
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
        $response   = [];
        $message    = '';

        try {
            $validator = Validator::make($request->all(), [
                'operator_id' => 'required|numeric',
                'campaign_master_id' => 'required|numeric',
                'offer_type' => 'required|numeric',
                'offer_value' => 'required|numeric',
                'min_ticket_value' => 'required|numeric',
                'services' => 'required|numeric',
                'auto_renewwal' => 'required|boolean',
                'validity_type' => 'required|string|in:DATE_RANGE,DURATION',
                'start_date' => 'required_if:validity_type,DATE_RANGE',
                'end_date'   => 'required_if:validity_type,DATE_RANGE',
                'duration_value' => 'required_if:validity_type,DURATION|numeric',
                'duration_unit'  => 'required_if:validity_type,DURATION|in:DAY,WEEK,MONTH'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 422,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors(),
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
                'duration_unit' => $request->duration_unit,
            ]);

            $message  = Config::get('constants.RECORD_ADDED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status     = false;
            $statusCode = 500;
            $message    = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status'     => $status,
            'statusCode' => $statusCode,
            'message'    => $message,
        ], $statusCode);
    }

    public function campaignUpdate(Request $request, $id)
    {
        $status     = true;
        $statusCode = 200;
        $response   = [];
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
                'operator_id' => 'required|numeric',
                'campaign_master_id' => 'required|numeric',
                'offer_type' => 'required|numeric',
                'offer_value' => 'required|numeric',
                'min_ticket_value' => 'required|numeric',
                'services' => 'required|numeric',
                'auto_renewwal' => 'required|boolean',
                'validity_type' => 'required|string|in:DATE_RANGE,DURATION',
                'start_date' => 'required_if:validity_type,DATE_RANGE',
                'end_date'   => 'required_if:validity_type,DATE_RANGE',
                'duration_value' => 'required_if:validity_type,DURATION|numeric',
                'duration_unit'  => 'required_if:validity_type,DURATION|in:DAY,WEEK,MONTH'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 422,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors(),
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
                'duration_unit' => $request->duration_unit,
            ]);

            $message = Config::get('constants.RECORD_UPDATED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status     = false;
            $statusCode = 500;
            $message    = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status'     => $status,
            'statusCode' => $statusCode,
            'message'    => $message,
        ], $statusCode);
    }

    public function campaignDelete($id)
    {
        $status     = true;
        $statusCode = 200;
        $response   = [];
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
                'deleted_at' => now(),
            ]);

            $message = Config::get('constants.RECORD_REMOVED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status     = false;
            $statusCode = 500;
            $message    = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status'     => $status,
            'statusCode' => $statusCode,
            'message'    => $message,
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
            $search = $request->search ?? null;

            // $result = CampaignRoutes::orderBy('created_at', $order)->limit($limit)->get();
            // $result = CampaignRoutes::join('campaign', 'campaign_routes.campaign_id', '=', 'campaign.id')
            //     ->join('location as src', 'campaign_routes.src_id', '=', 'src.id')
            //     ->join('location as dest', 'campaign_routes.dest_id', '=', 'dest.id')
            //     ->select(
            //         'campaign_routes.*',
            //         'src.name as source_location',
            //         'dest.name as destination_location'
            //     )
            //     ->orderBy('campaign_routes.created_at', $order)
            //     ->limit($limit)
            //     ->get();

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
            $status     = false;
            $statusCode = 500;
            $message    = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status'         => $status,
            'statusCode'     => $statusCode,
            'message'        => $message,
            'data'       => $response,
        ], $statusCode);
    }

    public function campaignroutescreate(Request $request)
    {
        $status     = true;
        $statusCode = 200;
        $response   = [];
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
                    'errors'     => $validator->errors(),
                ], 422);
            }

            CampaignRoutes::create([
                'campaign_id' => $request->campaign_id,
                'src_id' => $request->src_id,
                'dest_id' => $request->dest_id,
            ]);

            $message  = Config::get('constants.RECORD_ADDED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status     = false;
            $statusCode = 500;
            $message    = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status'     => $status,
            'statusCode' => $statusCode,
            'message'    => $message,
        ], $statusCode);
    }

    public function campaignRoutesUpdate(Request $request, $id)
    {
        $status     = true;
        $statusCode = 200;
        $response   = [];
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

            // Validation (UNCHANGED)
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
                    'errors'     => $validator->errors(),
                ], 422);
            }

            // Update
            $record->update([
                'campaign_id' => $request->campaign_id,
                'src_id'      => $request->src_id,
                'dest_id'    => $request->dest_id,
            ]);

            $message  = Config::get('constants.RECORD_UPDATED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status     = false;
            $statusCode = 500;
            $message    = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status'     => $status,
            'statusCode' => $statusCode,
            'message'    => $message,
        ], $statusCode);
    }

    public function campaignRoutesDelete($id)
    {
        $status     = true;
        $statusCode = 200;
        $response   = [];
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
                'deleted_at' => now(),
            ]);

            $message = Config::get('constants.RECORD_REMOVED');
        } catch (\Throwable $th) {

            Log::error($th);

            $status     = false;
            $statusCode = 500;
            $message    = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status'     => $status,
            'statusCode' => $statusCode,
            'message'    => $message,
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
            $search = $request->search ?? null;

            $result = CampaignServices::orderBy('created_at', $order)->limit($limit)->get();

            if (!empty($result)) {
                $response = $result;
                $message  = Config::get('constants.RECORD_FETCHED');
            } else {
                $message = Config::get('constants.RECORD_NOT_FOUND');
            }
        } catch (\Throwable $th) {
            $status     = false;
            $statusCode = 500;
            $message    = Config::get('constants.EXCEPTION_ERROR');
        }

        return response()->json([
            'status'         => $status,
            'statusCode'     => $statusCode,
            'message'        => $message,
            'data'       => $response,
        ], $statusCode);
    }
}
