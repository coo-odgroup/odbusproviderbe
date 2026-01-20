<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Cron;
use App\Models\CronFrequency;

class AutoCronController extends Controller
{
    public function cronList(Request $request)
    {
        $status         = true;
        $statusCode     = 200;
        $response       = [];
        $message        = '';

        try {
            $order = 'DESC';
            $limit = $request->rows_number ?? 10;
            $frequency_id = $request->frequency_id ?? null;
            $run_type = $request->run_type ?? null;
            $search = $request->search ?? null;

            $result = Cron::with('frequency')
                ->orderBy('created_at', $order)
                ->when($frequency_id, function ($q) use ($frequency_id) {
                    $q->where('frequency_id', $frequency_id);
                })
                ->when($run_type, function ($q) use ($run_type) {
                    $q->where('run_type', $run_type);
                })
                ->when($search, function ($q) use ($search) {
                    $q->where(function ($sub) use ($search) {
                        $sub->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('command', 'LIKE', "%{$search}%");
                    });
                })
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

    public function cronCreate(Request $request)
    {
        $status     = true;
        $statusCode = 200;
        $response   = [];
        $message    = '';

        try {
            $validator = Validator::make($request->all(), [
                'name'          => 'required|string|max:255',
                'command'       => 'required|string',
                'frequency_id'  => 'required|exists:mysql_scheduler.cron_frequencies,id',
                'run_type'      => 'required|in:auto,manual',
                'is_active'     => 'required|boolean',
                'last_run_at'   => 'nullable|date',
                'next_run_at'   => 'nullable|date|after_or_equal:last_run_at',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 422,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors(),
                ], 422);
            }

            $cron = Cron::create([
                'name'          => $request->name,
                'command'       => $request->command,
                'frequency_id'  => $request->frequency_id,
                'run_type'      => $request->run_type,
                'is_active'     => $request->is_active,
                'last_run_at'   => $request->last_run_at,
                'next_run_at'   => $request->next_run_at,
            ]);

            $response = $cron;
            $message  = Config::get('constants.RECORD_ADDED', 'Cron created successfully');

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
            'data'       => $response,
        ], $statusCode);
    }

    public function cronUpdate(Request $request, $id)
    {
        $status     = true;
        $statusCode = 200;
        $response   = [];
        $message    = '';

        try {
            $validator = Validator::make($request->all(), [
                'name'          => 'required|string|max:255',
                'command'       => 'required|string',
                'frequency_id'  => 'required|exists:mysql_scheduler.cron_frequencies,id',
                'run_type'      => 'required|in:auto,manual',
                'is_active'     => 'required|boolean',
                'last_run_at'   => 'nullable|date',
                'next_run_at'   => 'nullable|date|after_or_equal:last_run_at',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 422,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors(),
                ], 422);
            }

            $cron = Cron::find($id);

            if (!$cron) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 404,
                    'message'    => Config::get('constants.RECORD_NOT_FOUND'),
                    'data'       => [],
                ], 404);
            }

            $cron->update([
                'name'          => $request->name,
                'command'       => $request->command,
                'frequency_id'  => $request->frequency_id,
                'run_type'      => $request->run_type,
                'is_active'     => $request->is_active,
                'last_run_at'   => $request->last_run_at,
                'next_run_at'   => $request->next_run_at,
            ]);

            $response = $cron;
            $message  = Config::get('constants.RECORD_UPDATED', 'Cron updated successfully');

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
            'data'       => $response,
        ], $statusCode);
    }

    public function cronDelete($id)
    {
        $status     = true;
        $statusCode = 200;
        $response   = [];
        $message    = '';

        try {
            $cron = Cron::find($id);

            if (!$cron) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 404,
                    'message'    => Config::get('constants.RECORD_NOT_FOUND'),
                    'data'       => [],
                ], 404);
            }

            $cron->delete();

            $response = [];
            $message  = Config::get('constants.RECORD_REMOVED', 'Cron deleted successfully');

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
            'data'       => $response,
        ], $statusCode);
    }

    public function getCronFrequenciesList()
    {
        $status         = true;
        $statusCode     = 200;
        $response       = [];
        $message        = '';

        try {

            $result = CronFrequency::get();

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