<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class AgentCancelSlabController extends Controller
{

    public function index(Request $request)
    {
        try {

            $query = DB::table('agent_cancel_slab_name as n')
                ->leftJoin(
                    'agent_cancel_slab as s',
                    's.slab_id',
                    '=',
                    'n.id'
                )
                ->leftJoin(
                    'user as creator',
                    'creator.id',
                    '=',
                    'n.created_by'
                )
                ->select(
                    'n.id as slab_id',
                    'n.slab_name',
                    'n.is_default',
                    'n.status as slab_status',
                    'n.created_at',
                    'n.created_by',
                    'creator.name as created_by_name',
                    'n.updated_at',
                    'n.updated_by',

                    's.id as cancellation_id',
                    's.range_from',
                    's.range_to',
                    's.total_deduct',
                    's.odus_deduct',
                    's.agent_deduct',
                    's.from_date',
                    's.to_date',
                    's.status as row_status'
                );

            if ($request->filled('slab_name')) {

                $query->where(
                    'n.slab_name',
                    'like',
                    '%' . $request->slab_name . '%'
                );
            }

            if ($request->filled('is_default')) {

                $query->where(
                    'n.is_default',
                    $request->is_default
                );
            }

            if ($request->filled('from_date')) {

                $query->whereDate(
                    's.from_date',
                    '>=',
                    $request->from_date
                );
            }

            if ($request->filled('to_date')) {

                $query->whereDate(
                    's.to_date',
                    '<=',
                    $request->to_date
                );
            }

            if ($request->filled('status')) {

                $query->where(
                    'n.status',
                    $request->status
                );
            }

            $query->orderBy('n.id', 'desc');
            $perPage = $request->get('per_page', 10);
            $data = $query->paginate($perPage);

            return response()->json([
                'status' => true,
                'data' => $data
            ], 200);
        } catch (\Exception $e) {

            Log::error(
                'Agent Cancel Slab index error: ' .
                    $e->getMessage(),
                [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            );

            return response()->json([
                'status' => false,
                'message' => 'Unable to fetch Agent Cancel Slabs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {

            $slab = DB::table('agent_cancel_slab_name')
                ->where('id', $id)
                ->first();

            if (!$slab) {

                return response()->json([
                    'status' => false,
                    'message' => 'Agent Cancel Slab not found'
                ], 404);
            }

            $cancelRows = DB::table('agent_cancel_slab')
                ->where('slab_id', $id)
                ->orderBy('id', 'asc')
                ->get();

            $rows = $cancelRows->map(function ($row) {

                return [
                    'id' => $row->id,
                    'min_fare' => $row->range_from,
                    'max_fare' => $row->range_to,
                    'total_deduct' => $row->total_deduct,
                    'odus_deduct' => $row->odus_deduct,
                    'agent_deduct' => $row->agent_deduct,
                    'from_date' => $row->from_date,
                    'to_date' => $row->to_date
                ];
            })->values()->toArray();

            return response()->json([
                'status' => true,

                'data' => [

                    'slab' => [
                        'id' => $slab->id,

                        'slab_name' => $slab->slab_name,
                        'is_default' => (int) $slab->is_default,
                        'status' => (int) $slab->status,
                        'created_at' => $slab->created_at,
                        'created_by' => $slab->created_by,
                        'updated_at' => $slab->updated_at,
                        'updated_by' => $slab->updated_by
                    ],

                    'cancel_rows' => $rows
                ]

            ], 200);
        } catch (Exception $e) {

            Log::error(
                'Agent Cancel Slab show error: ' .
                    $e->getMessage(),
                [
                    'slab_id' => $id,
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            );

            return response()->json([
                'status' => false,
                'message' => 'Unable to get agent cancel slab',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'slab_name' => 'required|string|max:128',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'is_default' => 'nullable|boolean',
            'commission_rows' => 'required|array|min:1',
            'commission_rows.*.min_fare' => 'required|numeric|min:0',
            'commission_rows.*.max_fare' => 'nullable|numeric|min:0',
            'commission_rows.*.total_deduct' => 'required|numeric|min:0',
            'commission_rows.*.odus_deduct' => 'required|numeric|min:0',
            'commission_rows.*.agent_deduct' => 'required|numeric|min:0',

            'created_by' => 'required|integer',
        ]);

        DB::beginTransaction();

        try {

            $createdBy = (int) $request->created_by;
            $now = Carbon::now();
            $isDefault =
                $request->boolean('is_default');

            if ($isDefault) {

                DB::table('agent_cancel_slab_name')
                    ->where('is_default', 1)
                    ->update([
                        'is_default' => 0,
                        'updated_at' => $now,
                        'updated_by' => $createdBy
                    ]);
            }

            $slabId =
                DB::table('agent_cancel_slab_name')
                ->insertGetId([

                    'slab_name' => $request->slab_name,
                    'is_default' => $isDefault ? 1 : 0,
                    'status' => 1,
                    'created_at' => $now,
                    'created_by' => $createdBy,
                    'updated_at' => $now,
                    'updated_by' => $createdBy
                ]);

            foreach (
                $request->commission_rows
                as $row
            ) {

                DB::table('agent_cancel_slab')
                    ->insert([

                        'slab_id' => $slabId,
                        'range_from' => $row['min_fare'],
                        'range_to' => $row['max_fare'] ?? null,
                        'total_deduct' => $row['total_deduct'],
                        'odus_deduct' => $row['odus_deduct'],
                        'agent_deduct' => $row['agent_deduct'],
                        'from_date' => $isDefault ? null : $request->from_date,
                        'to_date' => $isDefault ? null : $request->to_date,
                        'created_at' => $now,
                        'updated_at' => $now,
                        'created_by' => $createdBy,
                        'status' => 1
                    ]);
            }


            DB::commit();
            return response()->json([

                'status' => true,
                'message' => 'Agent Cancel Slab added successfully',
                'id' => $slabId

            ], 200);
        } catch (Exception $e) {

            DB::rollBack();
            Log::error(
                'Agent Cancel Slab store error: ' .
                    $e->getMessage(),
                [
                    'request' => $request->all(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            );

            return response()->json([

                'status' => false,
                'message' => 'Unable to add Agent Cancel Slab',
                'error' => $e->getMessage()

            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'slab_name' => 'required|string|max:128',
            'is_default' => 'nullable|boolean',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'commission_rows' => 'required|array|min:1',
            'commission_rows.*.min_fare' => 'required|numeric|min:0',
            'commission_rows.*.max_fare' => 'nullable|numeric|min:0',
            'commission_rows.*.total_deduct' => 'required|numeric|min:0',
            'commission_rows.*.odus_deduct' => 'required|numeric|min:0',
            'commission_rows.*.agent_deduct' => 'required|numeric|min:0',

            'created_by' => 'required|integer',
        ]);


        $isDefault = $request->boolean('is_default');
        if (!$isDefault) {

            $request->validate([
                'from_date' => 'required|date',
                'to_date' => 'required|date|after_or_equal:from_date',
            ]);
        }

        DB::beginTransaction();

        try {

            $updatedBy = (int) $request->created_by;
            $now = Carbon::now();

            $slab = DB::table('agent_cancel_slab_name')
                ->where('id', $id)
                ->first();

            if (!$slab) {

                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'message' => 'Agent Cancel Slab not found'
                ], 404);
            }

            if ($isDefault) {

                DB::table('agent_cancel_slab_name')
                    ->where('id', '!=', $id)
                    ->where('is_default', 1)
                    ->update([
                        'is_default' => 0,
                        'updated_at' => $now,
                        'updated_by' => $updatedBy
                    ]);
            }

            DB::table('agent_cancel_slab_name')
                ->where('id', $id)
                ->update([
                    'slab_name' => $request->slab_name,
                    'is_default' => $isDefault ? 1 : 0,
                    'updated_at' => $now,
                    'updated_by' => $updatedBy
                ]);
            $fromDate = $isDefault? null: $request->from_date;
            $toDate = $isDefault? null: $request->to_date;

            DB::table('agent_cancel_slab')
                ->where('slab_id', $id)
                ->delete();

            foreach ($request->commission_rows as $row) {

                DB::table('agent_cancel_slab')
                    ->insert([
                        'slab_id' => $id,
                        'range_from' => $row['min_fare'],
                        'range_to' => isset($row['max_fare'])? $row['max_fare']: null,
                        'total_deduct' => $row['total_deduct'],
                        'odus_deduct' => $row['odus_deduct'],
                        'agent_deduct' => $row['agent_deduct'],
                        'from_date' => $fromDate,
                        'to_date' => $toDate,
                        'created_at' => $now,
                        'updated_at' => $now,
                        'created_by' => $slab->created_by,
                        'status' => 1
                    ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Agent Cancel Slab updated successfully',
                'id' => $id
            ], 200);
        } catch (Exception $e) {

            DB::rollBack();
            Log::error(
                'Agent Cancel Slab update error: ' . $e->getMessage(),
                [
                    'slab_id' => $id,
                    'request' => $request->all(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            );

            return response()->json([
                'status' => false,
                'message' => 'Unable to update Agent Cancel Slab',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function destroy($id)
    {
        DB::beginTransaction();

        try {

            $slab =
                DB::table('agent_cancel_slab_name')
                ->where('id', $id)
                ->first();

            if (!$slab) {

                DB::rollBack();

                return response()->json([

                    'status' => false,
                    'message' => 'Agent Cancel Slab not found'

                ], 404);
            }


            /*
             * Delete child rows first
             */
            DB::table('agent_cancel_slab')
                ->where('slab_id', $id)
                ->delete();


            /*
             * Delete parent
             */
            DB::table('agent_cancel_slab_name')
                ->where('id', $id)
                ->delete();


            DB::commit();

            return response()->json([

                'status' => true,

                'message' =>
                'Agent Cancel Slab deleted successfully'

            ], 200);
        } catch (Exception $e) {

            DB::rollBack();

            Log::error(
                'Agent Cancel Slab delete error: ' .
                    $e->getMessage()
            );

            return response()->json([

                'status' => false,
                'message' => 'Unable to delete Agent Cancel Slab',
                'error' => $e->getMessage()

            ], 500);
        }
    }

    public function changeStatus(Request $request, $id)
    {
        try {

            $slab = DB::table('agent_cancel_slab_name')
                ->where('id', $id)
                ->first();

            if (!$slab) {
                return response()->json([
                    'status' => false,
                    'message' => 'Agent Cancel Slab not found'
                ], 404);
            }

            $newStatus = $slab->status == 1 ? 0 : 1;

            DB::table('agent_cancel_slab_name')
                ->where('id', $id)
                ->update([
                    'status' => $newStatus,
                    'updated_at' => now(),
                ]);

            return response()->json([
                'status' => true,
                'message' => 'Status changed successfully',
                'data' => [
                    'id' => $id,
                    'status' => $newStatus
                ]
            ]);
        } catch (\Exception $e) {

            Log::error('Agent Cancel Slab status error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Unable to change status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
