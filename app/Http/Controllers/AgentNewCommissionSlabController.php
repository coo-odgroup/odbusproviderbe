<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class AgentNewCommissionSlabController extends Controller
{
    /**
     * GET
     * View all Agent Commission Slabs
     */
    public function index(Request $request)
    {
        try {

            $perPage = $request->get('per_page', 10);

            /*
         * =====================================================
         * BASE QUERY
         * =====================================================
         */

            $query = DB::table('agent_comm_slab_name as acsn')
                ->leftJoin(
                    'agent_commission_slab as acs',
                    'acs.slab_id',
                    '=',
                    'acsn.id'
                )
                ->select(
                    'acsn.id as slab_id',
                    'acsn.slab_name',
                    'acsn.is_default',
                    'acsn.status as slab_status',

                    'acsn.created_at',
                    'acsn.created_by',
                    'acsn.updated_at',
                    'acsn.updated_by',

                    'acs.id as commission_id',
                    'acs.range_from',
                    'acs.range_to',
                    'acs.comission_per_seat',
                    'acs.agent_comm',
                    'acs.odbus_comm',
                    'acs.total_comm',
                    'acs.status as commission_status'
                );


            /*
         * =====================================================
         * 1. SLAB NAME FILTER
         * =====================================================
         */

            if ($request->filled('slab_name')) {

                $query->where(
                    'acsn.slab_name',
                    'like',
                    '%' . $request->slab_name . '%'
                );
            }


            /*
         * =====================================================
         * 2. DEFAULT / NOT DEFAULT FILTER
         *
         * is_default = 1  -> Default
         * is_default = 0  -> Not Default
         * empty            -> All
         * =====================================================
         */

            if (
                $request->has('is_default') &&
                $request->is_default !== null &&
                $request->is_default !== ''
            ) {

                $query->where(
                    'acsn.is_default',
                    (int) $request->is_default
                );
            }


            /*
         * =====================================================
         * 3. STATUS FILTER
         *
         * 1 = Active
         * 0 = Block
         * =====================================================
         */

            if (
                $request->has('status') &&
                $request->status !== null &&
                $request->status !== ''
            ) {

                $query->where(
                    'acsn.status',
                    (int) $request->status
                );
            }


            /*
         * =====================================================
         * 4. FROM DATE / TO DATE FILTER
         *
         * Dates are stored in:
         *
         * assigned_comm_slab_agent
         *
         * =====================================================
         */

            if ($request->filled('from_date')) {

                $fromDate = $request->from_date;

                $query->whereExists(function ($subQuery) use ($fromDate) {

                    $subQuery->select(DB::raw(1))
                        ->from('assigned_comm_slab_agent as asa')
                        ->whereColumn(
                            'asa.agent_comm_id',
                            'acsn.id'
                        )
                        ->whereDate(
                            'asa.from_date',
                            '>=',
                            $fromDate
                        );
                });
            }


            if ($request->filled('to_date')) {

                $toDate = $request->to_date;

                $query->whereExists(function ($subQuery) use ($toDate) {

                    $subQuery->select(DB::raw(1))
                        ->from('assigned_comm_slab_agent as asa')
                        ->whereColumn(
                            'asa.agent_comm_id',
                            'acsn.id'
                        )
                        ->whereDate(
                            'asa.to_date',
                            '<=',
                            $toDate
                        );
                });
            }


            /*
         * =====================================================
         * ORDER
         * =====================================================
         */

            $query->orderBy(
                'acsn.id',
                'desc'
            );


            /*
         * =====================================================
         * PAGINATION
         * =====================================================
         */

            $data = $query->paginate($perPage);


            /*
         * =====================================================
         * ADD AGENTS + COMMON DATES
         * =====================================================
         */

            foreach ($data->items() as $item) {

                /*
             * Get assigned agents
             */

                $agents = DB::table(
                    'assigned_comm_slab_agent as asa'
                )
                    ->leftJoin(
                        'users as u',
                        'u.id',
                        '=',
                        'asa.agent_id'
                    )
                    ->where(
                        'asa.agent_comm_id',
                        $item->slab_id
                    )
                    ->select(
                        'asa.id',
                        'asa.agent_id',
                        'u.name as agent_name',
                        'asa.from_date',
                        'asa.to_date',
                        'asa.status'
                    )
                    ->orderBy(
                        'asa.id',
                        'asc'
                    )
                    ->get();


                /*
             * Agent IDs
             */

                $item->agent_ids = $agents
                    ->pluck('agent_id')
                    ->map(function ($id) {
                        return (int) $id;
                    })
                    ->values()
                    ->toArray();


                /*
             * Agent assigned
             */

                $item->agent_assigned =
                    $agents->count() > 0 ? 1 : 0;


                /*
             * Keep agents
             */

                $item->agents = $agents;


                /*
             * Common FROM DATE
             */

                $firstAgent = $agents->first();

                $item->from_date =
                    $firstAgent
                    ? $firstAgent->from_date
                    : null;


                /*
             * Common TO DATE
             */

                $item->to_date =
                    $firstAgent
                    ? $firstAgent->to_date
                    : null;


                /*
             * Frontend expects status
             */

                $item->status =
                    (int) $item->slab_status;
            }


            /*
         * =====================================================
         * RESPONSE
         * =====================================================
         */

            return response()->json([
                'status' => true,
                'data' => $data
            ], 200);
        } catch (\Exception $e) {

            Log::error(
                'Agent Commission Slab index error: ' .
                    $e->getMessage(),
                [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'request' => $request->all()
                ]
            );

            return response()->json([
                'status' => false,
                'message' => 'Unable to get agent commission slabs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {

            /*
         * 1. Get slab name/details
         */
            $slab = DB::table('agent_comm_slab_name')
                ->where('id', $id)
                ->first();

            if (!$slab) {
                return response()->json([
                    'status' => false,
                    'message' => 'Agent Commission Slab not found'
                ], 404);
            }


            /*
         * 2. Get ALL commission rows
         */
            $commission = DB::table('agent_commission_slab')
                ->where('slab_id', $id)
                ->orderBy('id', 'asc')
                ->get();


            /*
         * 3. Get assigned agents
         */
            $agents = DB::table('assigned_comm_slab_agent as asa')
                ->leftJoin(
                    'users as u',
                    'u.id',
                    '=',
                    'asa.agent_id'
                )
                ->where(
                    'asa.agent_comm_id',
                    $id
                )
                ->select(
                    'asa.id',
                    'asa.agent_id',
                    'u.name as agent_name',
                    'asa.status',
                    'asa.from_date',
                    'asa.to_date'
                )
                ->orderBy('asa.id', 'asc')
                ->get();


            /*
         * 4. Agent IDs
         */
            $agentIds = $agents
                ->pluck('agent_id')
                ->map(function ($id) {
                    return (int) $id;
                })
                ->values()
                ->toArray();


            /*
         * 5. Determine whether agents are assigned
         * from the actual assigned_comm_slab_agent table.
         */
            $agentAssigned = count($agentIds) > 0 ? 1 : 0;


            /*
         * 6. Get dates from assigned agents
         *
         * Assuming all selected agents use the same
         * from/to dates.
         */
            $firstAgent = $agents->first();

            $fromDate = $firstAgent
                ? $firstAgent->from_date
                : null;

            $toDate = $firstAgent
                ? $firstAgent->to_date
                : null;


            /*
         * 7. Format commission rows for frontend
         *
         * This makes the response names match the Angular
         * commission_rows structure.
         */
            $commissionRows = $commission->map(function ($row) {

                return [
                    'id' => $row->id,
                    'min_fare' => $row->range_from,
                    'max_fare' => $row->range_to,
                    'total_comm' => $row->total_comm,
                    'odbus_comm' => $row->odbus_comm,
                    'agent_comm' => $row->agent_comm
                ];
            })->values()->toArray();


            /*
         * 8. Build complete slab response
         */
            $slabData = [
                'id' => $slab->id,
                'slab_name' => $slab->slab_name,

                'is_default' => (int) $slab->is_default,

                /*
             * IMPORTANT:
             * Do NOT use the incorrect value stored in
             * agent_comm_slab_name.
             *
             * Determine it from assigned agents.
             */
                'agent_assigned' => $agentAssigned,

                'agent_ids' => $agentIds,

                'from_date' => $fromDate,
                'to_date' => $toDate,

                'status' => (int) $slab->status,

                'created_at' => $slab->created_at,
                'created_by' => $slab->created_by,

                'updated_at' => $slab->updated_at,
                'updated_by' => $slab->updated_by
            ];


            /*
         * 9. Return response
         */
            return response()->json([
                'status' => true,

                'data' => [
                    'slab' => $slabData,

                    'commission' => $commission,

                    'commission_rows' => $commissionRows,

                    'agents' => $agents,

                    'agent_ids' => $agentIds,

                    'agent_assigned' => $agentAssigned,

                    'from_date' => $fromDate,

                    'to_date' => $toDate
                ]

            ], 200);
        } catch (\Exception $e) {

            Log::error(
                'Agent Commission Slab show error: ' . $e->getMessage(),
                [
                    'slab_id' => $id,
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            );

            return response()->json([
                'status' => false,
                'message' => 'Unable to get agent commission slab',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'slab_name' => 'required|string|max:64',
            'is_default' => 'nullable|boolean',
            'agent_assigned' => 'nullable|boolean',

            'commission_rows' => 'required|array|min:1',

            'commission_rows.*.min_fare' => 'required|numeric|min:0',
            'commission_rows.*.max_fare' => 'nullable|numeric|min:0',
            'commission_rows.*.total_comm' => 'required|numeric|min:0',
            'commission_rows.*.odbus_comm' => 'required|numeric|min:0',
            'commission_rows.*.agent_comm' => 'required|numeric|min:0',

            'agent_ids' => 'nullable|array',
            'agent_ids.*' => 'integer',

            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',

            // IMPORTANT
            'created_by' => 'required|integer|exists:users,id',
        ]);

        $isDefault = $request->boolean('is_default');
        $agentAssigned = $request->boolean('agent_assigned');

        /*
     * If slab is default, agents are not assigned.
     */
        if ($isDefault) {
            $agentAssigned = false;
        }

        /*
     * Agent assignment requires at least one agent.
     */
        if ($agentAssigned && empty($request->agent_ids)) {
            return response()->json([
                'status' => false,
                'message' => 'Please select at least one agent'
            ], 422);
        }

        DB::beginTransaction();

        try {

            /*
         * Get creator from Angular request.
         */
            $createdBy = (int) $request->created_by;

            /*
         * Current timestamp
         */
            $now = Carbon::now();

            /*
         * 1. If this slab is default,
         *    remove default status from existing slab.
         */
            if ($isDefault) {

                DB::table('agent_comm_slab_name')
                    ->where('is_default', 1)
                    ->update([
                        'is_default' => 0,
                        'updated_at' => $now,
                        'updated_by' => $createdBy
                    ]);
            }

            /*
         * 2. Insert slab name
         */
            $slabId = DB::table('agent_comm_slab_name')
                ->insertGetId([
                    'slab_name' => $request->slab_name,
                    'is_default' => $isDefault ? 1 : 0,
                    'agent_assigned' => $agentAssigned ? 1 : 0,
                    'status' => 1,
                    'created_at' => Carbon::now(),
                    'created_by' => $createdBy,
                    'updated_at' => Carbon::now(),
                    'updated_by' => $createdBy
                ]);

            /*
         * 3. Insert ALL commission rows
         */
            foreach ($request->commission_rows as $row) {

                DB::table('agent_commission_slab')
                    ->insert([

                        'slab_id' => $slabId,

                        'user_id' => $createdBy,

                        'range_from' => $row['min_fare'],

                        'range_to' => isset($row['max_fare'])
                            ? $row['max_fare']
                            : null,

                        'comission_per_seat' => 0,

                        'agent_comm' => $row['agent_comm'],

                        'odbus_comm' => $row['odbus_comm'],

                        'total_comm' => $row['total_comm'],

                        'created_at' => $now,

                        'updated_at' => $now,

                        'created_by' => $createdBy,

                        'status' => 1
                    ]);
            }

            /*
         * 4. Insert every selected agent
         */
            if ($agentAssigned && !empty($request->agent_ids)) {

                foreach ($request->agent_ids as $agentId) {

                    DB::table('assigned_comm_slab_agent')
                        ->insert([

                            'agent_comm_id' => $slabId,

                            'agent_id' => $agentId,

                            'status' => 1,

                            'from_date' => $request->from_date,

                            'to_date' => $request->to_date,

                            'created_at' => $now,

                            'created_by' => $createdBy,

                            'updated_at' => $now,

                            'updated_by' => $createdBy
                        ]);
                }
            }

            /*
         * 5. Commit transaction
         */
            DB::commit();

            return response()->json([

                'status' => true,

                'message' => 'Agent Commission Slab added successfully',

                'id' => $slabId

            ], 200);
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error(
                'Agent Commission Slab store error: ' .
                    $e->getMessage(),
                [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'request' => $request->all()
                ]
            );

            return response()->json([

                'status' => false,

                'message' => 'Unable to add Agent Commission Slab',

                'error' => $e->getMessage()

            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'slab_name' => 'required|string|max:64',
            'is_default' => 'nullable|boolean',
            'agent_assigned' => 'nullable|boolean',

            'commission_rows' => 'required|array|min:1',

            'commission_rows.*.min_fare' => 'required|numeric|min:0',
            'commission_rows.*.max_fare' => 'nullable|numeric|min:0',
            'commission_rows.*.total_comm' => 'required|numeric|min:0',
            'commission_rows.*.odbus_comm' => 'required|numeric|min:0',
            'commission_rows.*.agent_comm' => 'required|numeric|min:0',

            'agent_ids' => 'nullable|array',
            'agent_ids.*' => 'integer',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',

            'created_by' => 'required|integer|exists:users,id',
        ]);

        $isDefault = $request->boolean('is_default');
        $agentAssigned = $request->boolean('agent_assigned');

        /*
     * Default slab cannot have agents assigned
     */
        if ($isDefault) {
            $agentAssigned = false;
        }

        /*
     * If agent assignment is enabled,
     * at least one agent must be selected.
     */
        if ($agentAssigned && empty($request->agent_ids)) {
            return response()->json([
                'status' => false,
                'message' => 'Please select at least one agent'
            ], 422);
        }

        DB::beginTransaction();

        try {

            /*
         * User who is updating the slab.
         *
         * Using created_by because your Angular request
         * is already sending this value.
         */
            $updatedBy = $request->created_by;

            /*
         * 1. Check slab exists
         */
            $slab = DB::table('agent_comm_slab_name')
                ->where('id', $id)
                ->first();

            if (!$slab) {

                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'message' => 'Agent Commission Slab not found'
                ], 404);
            }


            /*
         * 2. If this slab becomes default,
         *    remove default status from all other slabs.
         */
            if ($isDefault) {

                DB::table('agent_comm_slab_name')
                    ->where('id', '!=', $id)
                    ->where('is_default', 1)
                    ->update([
                        'is_default' => 0,
                        'updated_at' => Carbon::now(),
                        'updated_by' => $updatedBy
                    ]);
            }


            /*
         * 3. Update slab name
         */
            DB::table('agent_comm_slab_name')
                ->where('id', $id)
                ->update([
                    'slab_name' => $request->slab_name,
                    'is_default' => $isDefault ? 1 : 0,
                    'updated_at' => Carbon::now(),
                    'updated_by' => $updatedBy
                ]);


            /*
         * 4. Delete existing commission rows
         *
         * Store() now supports multiple commission rows,
         * so during update we remove the old rows and
         * insert the current rows from Angular.
         */
            DB::table('agent_commission_slab')
                ->where('slab_id', $id)
                ->delete();


            /*
         * 5. Insert ALL commission rows
         */
            foreach ($request->commission_rows as $row) {

                DB::table('agent_commission_slab')
                    ->insert([

                        'slab_id' => $id,

                        'user_id' => $slab->created_by,

                        'range_from' => $row['min_fare'],

                        'range_to' => $row['max_fare'] ?? null,

                        'comission_per_seat' => 0,

                        'agent_comm' => $row['agent_comm'],

                        'odbus_comm' => $row['odbus_comm'],

                        'total_comm' => $row['total_comm'],

                        'created_at' => Carbon::now(),

                        'updated_at' => Carbon::now(),

                        'created_by' => $slab->created_by,

                        'status' => 1
                    ]);
            }


            /*
         * 6. Delete existing assigned agents
         */
            DB::table('assigned_comm_slab_agent')
                ->where('agent_comm_id', $id)
                ->delete();


            /*
         * 7. Insert currently selected agents
         */
            if ($agentAssigned && !empty($request->agent_ids)) {

                foreach ($request->agent_ids as $agentId) {

                    DB::table('assigned_comm_slab_agent')
                        ->insert([

                            'agent_comm_id' => $id,

                            'agent_id' => $agentId,

                            'status' => 1,

                            'from_date' => $request->from_date,

                            'to_date' => $request->to_date,

                            'created_at' => Carbon::now(),

                            'created_by' => $slab->created_by,

                            'updated_at' => Carbon::now(),

                            'updated_by' => $updatedBy
                        ]);
                }
            }


            /*
         * 8. Commit
         */
            DB::commit();


            return response()->json([
                'status' => true,
                'message' => 'Agent Commission Slab updated successfully',
                'id' => $id
            ], 200);
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error(
                'Agent Commission Slab update error: ' .
                    $e->getMessage(),
                [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'request' => $request->all(),
                    'slab_id' => $id
                ]
            );

            return response()->json([
                'status' => false,
                'message' => 'Unable to update Agent Commission Slab',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAgents()
    {
        try {

            $agents = DB::table('user')
                ->where(function ($query) {
                    $query->where('role_id', 3)
                        ->where('is_mobile_verified', 1);
                })
                ->where('status', 1)
                ->select(
                    'id',
                    'name'
                )
                ->get();

            return response()->json([
                'status' => true,
                'data' => $agents
            ], 200);
        } catch (Exception $e) {

            Log::error(
                'Get Agent dropdown error: ' . $e->getMessage()
            );

            return response()->json([
                'status' => false,
                'message' => 'Unable to get agents',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
