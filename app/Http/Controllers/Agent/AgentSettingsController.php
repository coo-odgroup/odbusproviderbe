<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AgentSettingsController extends Controller
{
    private $settingColumns = [
        'agent_comm_on_booking',
        'odbus_comm_on_agent_booking'
    ];

    public function getSettings()
    {
        try {

            // Fetch common settings record
            $charges = DB::table('odbus_charges')
                ->orderBy('id', 'asc')
                ->first($this->settingColumns);

            if (!$charges) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Agent settings record not found.',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'status' => 1,
                'message' => 'Agent settings fetched successfully.',
                'data' => [
                    'agent_comm_on_booking' =>
                    $charges->agent_comm_on_booking,

                    'odbus_comm_on_agent_booking' =>
                    $charges->odbus_comm_on_agent_booking
                ]
            ], 200);
        } catch (\Throwable $e) {

            Log::error('Agent Settings Fetch Error: ' . $e->getMessage());

            return response()->json([
                'status' => 0,
                'message' => 'Unable to fetch agent settings.'
            ], 500);
        }
    }

    public function updateSettings(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [

            'agent_comm_on_booking' => [
                'required',
                'numeric',
                'min:0',
                'max:100'
            ],

            'odbus_comm_on_agent_booking' => [
                'required',
                'numeric',
                'min:0',
                'max:100'
            ]

        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {

            DB::beginTransaction();

            // Find the common settings record
            $charges = DB::table('odbus_charges')
                ->orderBy('id', 'asc')
                ->first(['id']);

            if (!$charges) {

                DB::rollBack();

                return response()->json([
                    'status' => 0,
                    'message' => 'Agent settings record not found.'
                ], 404);
            }

            // Prepare allowed update columns
            $updateData = [];

            foreach ($this->settingColumns as $column) {

                $updateData[$column] = $request->input($column);
            }

            // Update existing record
            DB::table('odbus_charges')
                ->where('id', $charges->id)
                ->update($updateData);

            DB::commit();

            return response()->json([
                'status' => 1,
                'message' => 'Agent settings updated successfully.'
            ], 200);
        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Agent Settings Update Error: ' . $e->getMessage());

            return response()->json([
                'status' => 0,
                'message' => 'Unable to update agent settings.'
            ], 500);
        }
    }
}
