<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ManageUserAccessController extends Controller
{
    public function getOperators()
    {
        try {
            $operators = DB::table('bus_operator')->select('id', 'organisation_name')
                ->where('status', 1)->orderBy('organisation_name')->get();
            return response()->json(['status' => true, 'message' => 'Operators fetched successfully', 'data' => $operators]);
        } catch (\Throwable $e) {
            Log::error('ManageUserAccessController getOperators: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Unable to fetch operators'], 500);
        }
    }

    public function getUsers(Request $request)
    {
        $v = Validator::make($request->all(), ['operator_id' => 'required|integer|min:1', 'user_type' => 'required|in:3,7,8']);
        if ($v->fails()) return response()->json(['status' => false, 'message' => 'Invalid request parameters', 'errors' => $v->errors()], 422);
        try {
            $users = DB::table('user')->select('id', 'name', 'client_id')
                ->where('bus_operator_id', (int)$request->operator_id)->where('role_id', (int)$request->user_type)
                ->orderBy('name')->get();
            return response()->json(['status' => true, 'message' => 'Users fetched successfully', 'data' => $users]);
        } catch (\Throwable $e) {
            Log::error('ManageUserAccessController getUsers: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Unable to fetch users'], 500);
        }
    }

    public function getBuses(Request $request)
    {
        $v = Validator::make($request->all(), ['operator_id' => 'required|integer|min:1']);
        if ($v->fails()) return response()->json(['status' => false, 'message' => 'Invalid operator ID', 'errors' => $v->errors()], 422);
        try {
            $buses = DB::table('bus')->select('id', 'name', 'bus_number')
                ->where('bus_operator_id', (int)$request->operator_id)->orderBy('name')->get();
            return response()->json(['status' => true, 'message' => 'Buses fetched successfully', 'data' => $buses]);
        } catch (\Throwable $e) {
            Log::error('ManageUserAccessController getBuses: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Unable to fetch buses'], 500);
        }
    }

    public function getBusRoutes(Request $request)
    {
        $v = Validator::make($request->all(), ['bus_id' => 'required|integer|min:1']);
        if ($v->fails()) return response()->json(['status' => false, 'message' => 'Invalid bus ID', 'errors' => $v->errors()], 422);
        try {
            $routes = DB::table('ticket_price as tp')
                ->join('location as src', 'src.id', '=', 'tp.source_id')
                ->join('location as dest', 'dest.id', '=', 'tp.destination_id')
                ->where('tp.bus_id', (int)$request->bus_id)
                ->select('tp.id', 'tp.source_id', 'src.name as source_name', 'tp.destination_id', 'dest.name as destination_name')
                ->orderBy('src.name')->orderBy('dest.name')->get()
                ->map(function ($r) {
                    $r->name = $r->source_name . ' → ' . $r->destination_name;
                    return $r;
                });
            return response()->json(['status' => true, 'message' => 'Bus routes fetched successfully', 'data' => $routes]);
        } catch (\Throwable $e) {
            Log::error('ManageUserAccessController getBusRoutes: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Unable to fetch bus routes'], 500);
        }
    }

    /**
     * Saves multiple user assignments for one selected operator + bus.
     * Existing assignment IDs are updated; assignments without IDs are inserted.
     * Route and location access rows are replaced per assignment in the same transaction.
     */
    public function saveUserBusAccess(Request $request)
    {
        $v = Validator::make($request->all(), [
            'operator_id' => 'required|integer|min:1',
            'bus_id' => 'required|integer|min:1',
            'assignments' => 'required|array|min:1',
            'assignments.*.id' => 'nullable|integer|min:1',
            'assignments.*.user_type' => 'required|in:3,7,8',
            'assignments.*.user_id' => 'required|integer|min:1',
            'assignments.*.from_date' => 'required|date_format:Y-m-d',
            'assignments.*.to_date' => 'required|date_format:Y-m-d',
            'assignments.*.route_ids' => 'required|array|min:1',
            'assignments.*.route_ids.*' => 'required|integer|min:1',
            'assignments.*.route_statuses' => 'sometimes|array',
            'assignments.*.route_statuses.*' => 'sometimes|in:Active,Inactive',
            'removed_assignment_ids' => 'sometimes|array',
            'removed_assignment_ids.*' => 'integer|min:1',
        ]);
        if ($v->fails()) return response()->json(['status' => false, 'message' => 'Please correct the submitted data.', 'errors' => $v->errors()], 422);

        try {
            $operatorId = (int)$request->operator_id;
            $busId = (int)$request->bus_id;
            $rows = $request->input('assignments', []);
            $removedIds = array_values(array_unique(array_map('intval', $request->input('removed_assignment_ids', []))));
            $roleUserKeys = [];
            foreach ($rows as $row) {
                if (empty($row['from_date']) || empty($row['to_date']) || $row['to_date'] < $row['from_date']) {
                    return response()->json(['status' => false, 'message' => 'Each assignment must have a valid date range.'], 422);
                }
                $key = (int)$row['user_type'] . ':' . (int)$row['user_id'];
                if (in_array($key, $roleUserKeys, true)) {
                    return response()->json(['status' => false, 'message' => 'The same user and role cannot be added twice to one bus.'], 422);
                }
                $roleUserKeys[] = $key;
            }

            $result = DB::transaction(function () use ($operatorId, $busId, $rows, $removedIds) {
                $busExists = DB::table('bus')->where('id', $busId)->where('bus_operator_id', $operatorId)->exists();
                if (!$busExists) throw new \InvalidArgumentException('The selected bus does not belong to this operator.');

                // Removed saved blocks are soft-disabled to preserve assignment history.
                if (!empty($removedIds)) {
                    DB::table('operator_bus_assignments')
                        ->where('bus_operator_id', $operatorId)
                        ->where('bus_id', $busId)
                        ->whereIn('id', $removedIds)
                        ->update(['status' => 0, 'updated_at' => now(), 'updated_by' => auth()->id() ?? null]);
                }

                $savedIds = [];
                foreach ($rows as $row) {
                    $userId = (int)$row['user_id'];
                    $roleType = (int)$row['user_type'];
                    $assignmentId = !empty($row['id']) ? (int)$row['id'] : null;

                    $userExists = DB::table('user')->where('id', $userId)
                        ->where('bus_operator_id', $operatorId)->where('role_id', $roleType)->exists();
                    if (!$userExists) throw new \InvalidArgumentException('A selected user does not belong to this operator and role.');

                    if (in_array($roleType, [7, 8], true)) {
                        $duplicate = DB::table('operator_bus_assignments')
                            ->where('bus_id', $busId)->where('bus_operator_id', $operatorId)
                            ->where('role_type', $roleType)->where('status', 1)
                            ->when($assignmentId, function ($q) use ($assignmentId) {
                                $q->where('id', '<>', $assignmentId);
                            })
                            ->exists();
                        if ($duplicate) throw new \InvalidArgumentException($roleType === 7
                            ? 'This bus already has an active conductor.'
                            : 'This bus already has an active manager.');
                    }

                    $routeIds = array_values(array_unique(array_map('intval', $row['route_ids'])));
                    $routes = DB::table('ticket_price')->where('bus_id', $busId)->whereIn('id', $routeIds)
                        ->select('id', 'source_id', 'destination_id')->get();
                    if ($routes->count() !== count($routeIds)) throw new \InvalidArgumentException('One or more selected routes are invalid for this bus.');

                    $totalRoutes = DB::table('ticket_price')->where('bus_id', $busId)->count();
                    $accessMode = ($totalRoutes > 0 && count($routeIds) === $totalRoutes) ? 1 : 2;
                    $now = now();
                    $values = [
                        'bus_operator_id' => $operatorId,
                        'role_type' => $roleType,
                        'user_id' => $userId,
                        'bus_id' => $busId,
                        'route_access' => $accessMode,
                        'access_mode' => $accessMode,
                        'valid_from' => $row['from_date'],
                        'valid_until' => $row['to_date'],
                        'status' => 1,
                        'updated_at' => $now,
                        'updated_by' => auth()->id() ?? null,
                    ];
                    if ($assignmentId) {
                        $updated = DB::table('operator_bus_assignments')->where('id', $assignmentId)
                            ->where('bus_operator_id', $operatorId)->where('bus_id', $busId)->update($values);
                        if (!$updated && !DB::table('operator_bus_assignments')->where('id', $assignmentId)->where('bus_operator_id', $operatorId)->where('bus_id', $busId)->exists()) {
                            throw new \InvalidArgumentException('An assignment being edited does not belong to this bus.');
                        }
                        $id = $assignmentId;
                    } else {
                        $values['created_at'] = $now;
                        $values['created_by'] = auth()->id() ?? null;
                        $id = DB::table('operator_bus_assignments')->insertGetId($values);
                    }
                    $savedIds[] = $id;

                    DB::table('operator_user_assigned_routes')->where('operator_id', $operatorId)
                        ->where('operator_user_id', $userId)->where('bus_id', $busId)->delete();
                    DB::table('operator_locations_ids')->where('operator_id', $operatorId)
                        ->where('operator_user_id', $userId)->delete();

                    $routeRows = [];
                    $locationIds = [];
                    foreach ($routes as $route) {
                        $routeRows[] = [
                            'operator_id' => $operatorId,
                            'operator_user_id' => $userId,
                            'bus_id' => $busId,
                            'ticket_price_id' => (int)$route->id,
                            'status' => (($row['route_statuses'][$route->id] ?? 'Active') === 'Inactive') ? 0 : 1,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                        if ((int)$route->source_id > 0) $locationIds[] = (int)$route->source_id;
                        if ((int)$route->destination_id > 0) $locationIds[] = (int)$route->destination_id;
                    }
                    foreach (array_chunk($routeRows, 500) as $chunk) DB::table('operator_user_assigned_routes')->insert($chunk);
                    $locationRows = [];
                    foreach (array_values(array_unique($locationIds)) as $locationId) {
                        $locationRows[] = [
                            'operator_id' => $operatorId,
                            'operator_user_id' => $userId,
                            'location_id' => $locationId,
                            'active_status' => 1,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                    foreach (array_chunk($locationRows, 500) as $chunk) DB::table('operator_locations_ids')->insert($chunk);
                }
                return ['assignment_ids' => $savedIds, 'saved_count' => count($savedIds), 'disabled_removed_count' => count($removedIds)];
            });
            return response()->json(['status' => true, 'message' => 'Bus user access saved successfully.', 'data' => $result], 200);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            Log::error('ManageUserAccessController saveUserBusAccess failed', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Unable to save bus user access.'], 500);
        }
    }

    public function getUserBusAccessList(Request $request)
    {
        try {
            $query = DB::table('operator_bus_assignments as a')
                ->join('bus_operator as o', 'o.id', '=', 'a.bus_operator_id')
                ->join('user as u', 'u.id', '=', 'a.user_id')
                ->join('bus as b', 'b.id', '=', 'a.bus_id')
                ->select(
                    'a.id',
                    'a.bus_operator_id',
                    'o.organisation_name as operator_name',
                    'a.role_type',
                    'a.user_id',
                    'u.name as user_name',
                    'u.client_id',
                    'a.bus_id',
                    'b.name as bus_name',
                    'b.bus_number',
                    'a.route_access',
                    'a.access_mode',
                    'a.valid_from',
                    'a.valid_until',
                    'a.status',
                    'a.created_at',
                    'a.updated_at'
                );
            if ($request->filled('search')) {
                $search = trim($request->search);
                $query->where(function ($q) use ($search) {
                    $q->where('o.organisation_name', 'like', "%{$search}%")->orWhere('u.name', 'like', "%{$search}%")
                        ->orWhere('u.client_id', 'like', "%{$search}%")->orWhere('b.name', 'like', "%{$search}%")
                        ->orWhere('b.bus_number', 'like', "%{$search}%");
                });
            }
            if ($request->filled('status')) $query->where('a.status', (int)$request->status);
            if ($request->filled('user_id')) $query->where('a.user_id', (int)$request->user_id);
            if ($request->filled('operator_id')) $query->where('a.bus_operator_id', (int)$request->operator_id);
            if ($request->filled('user_type')) $query->where('a.role_type', (int)$request->user_type);
            if ($request->filled('from_date')) $query->whereDate('a.valid_from', '>=', $request->from_date);
            if ($request->filled('to_date')) $query->whereDate('a.valid_until', '<=', $request->to_date);
            $assignments = $query->orderBy('b.name')->orderByDesc('a.id')->get();

            $routeRows = DB::table('operator_user_assigned_routes as ar')
                ->join('ticket_price as tp', 'tp.id', '=', 'ar.ticket_price_id')
                ->join('location as src', 'src.id', '=', 'tp.source_id')
                ->join('location as dest', 'dest.id', '=', 'tp.destination_id')
                ->whereIn('ar.operator_id', $assignments->pluck('bus_operator_id')->unique()->values())
                ->whereIn('ar.operator_user_id', $assignments->pluck('user_id')->unique()->values())
                ->whereIn('ar.bus_id', $assignments->pluck('bus_id')->unique()->values())
                ->select(
                    'ar.operator_id',
                    'ar.operator_user_id',
                    'ar.bus_id',
                    'ar.ticket_price_id',
                    'ar.status as route_status',
                    'tp.source_id',
                    'src.name as source_name',
                    'tp.destination_id',
                    'dest.name as destination_name'
                )->get();

            $data = $assignments->map(function ($a) use ($routeRows) {
                $routes = $routeRows->filter(function ($r) use ($a) {
                    return (int)$r->operator_id === (int)$a->bus_operator_id &&
                        (int)$r->operator_user_id === (int)$a->user_id && (int)$r->bus_id === (int)$a->bus_id;
                })->values();
                $a->role_name = [3 => 'Agent', 7 => 'Conductor', 8 => 'Manager'][$a->role_type] ?? 'Unknown';
                $a->access_mode_label = (int)$a->route_access === 1 ? 'All Routes' : 'Specific Routes';
                $a->status_label = (int)$a->status === 1 ? 'Active' : 'Inactive';
                $a->routes = $routes;
                $a->route_count = $routes->count();
                return $a;
            });
            return response()->json(['status' => true, 'message' => 'Assignments fetched successfully.', 'data' => $data]);
        } catch (\Throwable $e) {
            Log::error('getUserBusAccessList failed', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Unable to fetch saved assignments.'], 500);
        }
    }

    public function updateUserBusAssignmentStatus(Request $request)
    {
        $v = Validator::make($request->all(), ['id' => 'required|integer|min:1', 'status' => 'required|in:0,1']);
        if ($v->fails()) return response()->json(['status' => false, 'message' => 'Invalid assignment status request.', 'errors' => $v->errors()], 422);
        $updated = DB::table('operator_bus_assignments')->where('id', (int)$request->id)
            ->update(['status' => (int)$request->status, 'updated_at' => now(), 'updated_by' => auth()->id() ?? null]);
        if (!$updated && !DB::table('operator_bus_assignments')->where('id', (int)$request->id)->exists())
            return response()->json(['status' => false, 'message' => 'Assignment not found.'], 404);
        return response()->json(['status' => true, 'message' => 'Assignment status updated successfully.']);
    }

    public function updateBusAssignmentsStatus(Request $request)
    {
        $v = Validator::make($request->all(), [
            'bus_id' => 'required|integer|min:1',
            'operator_id' => 'required|integer|min:1',
            'status' => 'required|in:0,1'
        ]);
        if ($v->fails()) return response()->json(['status' => false, 'message' => 'Invalid bus status request.', 'errors' => $v->errors()], 422);
        $updated = DB::table('operator_bus_assignments')->where('bus_id', (int)$request->bus_id)
            ->where('bus_operator_id', (int)$request->operator_id)
            ->update(['status' => (int)$request->status, 'updated_at' => now(), 'updated_by' => auth()->id() ?? null]);
        return response()->json(['status' => true, 'message' => 'Bus assignments status updated.', 'data' => ['updated' => $updated]]);
    }
}
