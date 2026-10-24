<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ManageUserAccessController extends Controller
{

    public function getOperators()
    {
        try {
            $operators = DB::table('bus_operator')
                ->select('id', 'organisation_name')
                ->where('status', 1)
                ->orderBy('organisation_name', 'asc')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Operators fetched successfully',
                'data' => $operators
            ], 200);
        } catch (\Exception $e) {
            Log::error('BusUserAccessController getOperators: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Unable to fetch operators'
            ], 500);
        }
    }

    public function saveUserBusAccess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'operator_id' => 'required|integer|min:1',
            'user_type'   => 'required|in:3,7,8',
            'user_id'     => 'required|integer|min:1',
            'bus_id'      => 'required|integer|min:1',
            'from_date'   => 'required|date_format:Y-m-d',
            'to_date'     => 'required|date_format:Y-m-d|after_or_equal:from_date',
            'route_ids'   => 'required|array|min:1',
            'route_ids.*' => 'required|integer|min:1|distinct',
            'route_statuses' => 'sometimes|array',
            'route_statuses.*' => 'sometimes|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Please correct the submitted data.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $operatorId = (int) $request->operator_id;
            $userId = (int) $request->user_id;
            $busId = (int) $request->bus_id;
            $roleType = (int) $request->user_type;

            $routeIds = array_values(array_unique(
                array_map('intval', $request->route_ids)
            ));

            $routeStatuses = $request->input('route_statuses', []);

            $result = DB::transaction(function () use (
                $operatorId,
                $userId,
                $busId,
                $roleType,
                $routeIds,
                $routeStatuses,
                $request
            ) {
                // Confirm the selected user belongs to this operator and role.
                $userExists = DB::table('user')
                    ->where('id', $userId)
                    ->where('bus_operator_id', $operatorId)
                    ->where('role_id', $roleType)
                    ->exists();

                if (!$userExists) {
                    throw new \InvalidArgumentException(
                        'The selected user does not belong to this operator and user type.'
                    );
                }

                // Confirm the bus belongs to the selected operator.
                $busExists = DB::table('bus')
                    ->where('id', $busId)
                    ->where('bus_operator_id', $operatorId)
                    ->exists();

                if (!$busExists) {
                    throw new \InvalidArgumentException(
                        'The selected bus does not belong to this operator.'
                    );
                }

                // Fetch the actual route records for this bus.
                $routes = DB::table('ticket_price')
                    ->where('bus_id', $busId)
                    ->whereIn('id', $routeIds)
                    ->select('id', 'source_id', 'destination_id')
                    ->get();

                if ($routes->count() !== count($routeIds)) {
                    throw new \InvalidArgumentException(
                        'One or more selected routes are invalid for this bus.'
                    );
                }

                $totalBusRoutes = DB::table('ticket_price')
                    ->where('bus_id', $busId)
                    ->count();

                // Access mode 1 means every route for the bus is selected.
                // Access mode 2 means only some routes are selected.
                $accessMode = (
                    $totalBusRoutes > 0 &&
                    count($routeIds) === $totalBusRoutes
                ) ? 1 : 2;

                $now = now();

                // Main assignment.
                $assignmentId = DB::table('operator_bus_assignments')
                    ->insertGetId([
                        'bus_operator_id' => $operatorId,
                        'role_type' => $roleType,
                        'user_id' => $userId,
                        'bus_id' => $busId,
                        'route_access' => $accessMode,
                        'access_mode' => $accessMode,
                        'valid_from' => $request->from_date,
                        'valid_until' => $request->to_date,
                        'status' => 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                        'created_by' => auth()->id() ?? null,
                        'updated_by' => auth()->id() ?? null,
                    ]);

                // One assigned-route row for every selected ticket_price ID.
                $assignedRouteRows = [];

                foreach ($routes as $route) {
                    $assignedRouteRows[] = [
                        'operator_id' => $operatorId,
                        'operator_user_id' => $userId,
                        'bus_id' => $busId,
                        'ticket_price_id' => (int) $route->id,
                        'status' => (
                            ($routeStatuses[$route->id] ?? 'Active') === 'Inactive'
                        ) ? 0 : 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                foreach (array_chunk($assignedRouteRows, 500) as $chunk) {
                    DB::table('operator_user_assigned_routes')
                        ->insert($chunk);
                }

                // Gather source and destination IDs, then deduplicate them.
                $locationIds = $routes
                    ->flatMap(function ($route) {
                        return [
                            (int) $route->source_id,
                            (int) $route->destination_id,
                        ];
                    })
                    ->filter(function ($id) {
                        return $id > 0;
                    })
                    ->unique()
                    ->values();

                $locationRows = [];

                foreach ($locationIds as $locationId) {
                    $locationRows[] = [
                        'operator_id' => $operatorId,
                        'operator_user_id' => $userId,
                        'location_id' => $locationId,
                        'active_status' => 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                foreach (array_chunk($locationRows, 500) as $chunk) {
                    DB::table('operator_locations_ids')->insert($chunk);
                }

                return [
                    'assignment_id' => $assignmentId,
                    'access_mode' => $accessMode,
                    'assigned_routes' => count($assignedRouteRows),
                    'unique_locations' => count($locationRows),
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'User bus access saved successfully.',
                'data' => $result,
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('ManageUserAccessController saveUserBusAccess failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Unable to save user bus access.',
            ], 500);
        }
    }


    public function updateAssignedRouteStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'operator_id' => 'required|integer|min:1',
            'user_id' => 'required|integer|min:1',
            'bus_id' => 'required|integer|min:1',
            'status' => 'required|in:0,1',
            'ticket_price_ids' => 'nullable|array',
            'ticket_price_ids.*' => 'integer|min:1|distinct',
            'all' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid route status request.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $query = DB::table('operator_user_assigned_routes')
            ->where('operator_id', (int) $request->operator_id)
            ->where('operator_user_id', (int) $request->user_id)
            ->where('bus_id', (int) $request->bus_id);

        if (!$request->boolean('all')) {
            $ids = $request->input('ticket_price_ids', []);

            if (empty($ids)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Select one or more routes, or set all to true.',
                ], 422);
            }

            $query->whereIn('ticket_price_id', $ids);
        }

        $updated = $query->update([
            'status' => (int) $request->status,
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Assigned route status updated successfully.',
            'data' => ['updated' => $updated],
        ]);
    }



    public function getUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'operator_id' => 'required|integer|min:1',
            'user_type' => 'required|in:3,7,8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid request parameters',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $users = DB::table('user')
                ->select('id', 'name', 'client_id')
                ->where('bus_operator_id', (int) $request->operator_id)
                ->where('role_id', (int) $request->user_type)
                ->orderBy('name', 'asc')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Users fetched successfully',
                'data' => $users
            ], 200);
        } catch (\Exception $e) {
            Log::error('ManageUserAccessController getUsers: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Unable to fetch users'
            ], 500);
        }
    }


    public function getBuses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'operator_id' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid operator ID',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $buses = DB::table('bus')
                ->select('id', 'name', 'bus_number')
                ->where('bus_operator_id', (int) $request->operator_id)
                ->orderBy('name', 'asc')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Buses fetched successfully',
                'data' => $buses
            ], 200);
        } catch (\Exception $e) {
            Log::error('BusUserAccessController getBuses: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Unable to fetch buses'
            ], 500);
        }
    }

    public function getBusRoutes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bus_id' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid bus ID',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $routes = DB::table('ticket_price as tp')
                ->join('location as src', 'src.id', '=', 'tp.source_id')
                ->join('location as dest', 'dest.id', '=', 'tp.destination_id')
                ->where('tp.bus_id', (int) $request->bus_id)
                ->select(
                    'tp.id',
                    'tp.source_id',
                    'src.name as source_name',
                    'tp.destination_id',
                    'dest.name as destination_name'
                )
                ->orderBy('src.name')
                ->orderBy('dest.name')
                ->get();

            // Add a readable label for the dropdown.
            $routes = $routes->map(function ($route) {
                $route->name = $route->source_name . ' → ' . $route->destination_name;
                return $route;
            });

            return response()->json([
                'status' => true,
                'message' => 'Bus routes fetched successfully',
                'data' => $routes
            ], 200);
        } catch (\Exception $e) {
            Log::error('ManageUserAccessController getBusRoutes: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Unable to fetch bus routes'
            ], 500);
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
                    $q->where('o.organisation_name', 'like', "%{$search}%")
                        ->orWhere('u.name', 'like', "%{$search}%")
                        ->orWhere('u.client_id', 'like', "%{$search}%")
                        ->orWhere('b.name', 'like', "%{$search}%")
                        ->orWhere('b.bus_number', 'like', "%{$search}%");
                });
            }

            if ($request->filled('status')) {
                $query->where('a.status', (int) $request->status);
            }

            if ($request->filled('user_id')) {
                $query->where('a.user_id', (int) $request->user_id);
            }

            $assignments = $query
                ->orderByDesc('a.id')
                ->get();

            $assignmentIds = $assignments->pluck('id')->all();

            $routeRows = empty($assignmentIds)
                ? collect()
                : DB::table('operator_user_assigned_routes as ar')
                ->join('ticket_price as tp', 'tp.id', '=', 'ar.ticket_price_id')
                ->join('location as src', 'src.id', '=', 'tp.source_id')
                ->join('location as dest', 'dest.id', '=', 'tp.destination_id')
                ->whereIn('ar.operator_id', $assignments->pluck('bus_operator_id'))
                ->whereIn('ar.operator_user_id', $assignments->pluck('user_id'))
                ->whereIn('ar.bus_id', $assignments->pluck('bus_id'))
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
                )
                ->get();

            $data = $assignments->map(function ($assignment) use ($routeRows) {
                $routes = $routeRows->filter(function ($route) use ($assignment) {
                    return (int) $route->operator_id === (int) $assignment->bus_operator_id
                        && (int) $route->operator_user_id === (int) $assignment->user_id
                        && (int) $route->bus_id === (int) $assignment->bus_id;
                })->values();

                $assignment->role_name = [
                    3 => 'Agent',
                    7 => 'Conductor',
                    8 => 'Manager',
                ][$assignment->role_type] ?? 'Unknown';

                $assignment->access_mode_label =
                    (int) $assignment->route_access === 1
                    ? 'All Routes'
                    : 'Specific Routes';

                $assignment->status_label =
                    (int) $assignment->status === 1 ? 'Active' : 'Inactive';

                $assignment->routes = $routes;
                $assignment->route_count = $routes->count();

                return $assignment;
            });

            return response()->json([
                'status' => true,
                'message' => 'Assignments fetched successfully.',
                'data' => $data,
            ]);
        } catch (\Throwable $e) {
            Log::error('getUserBusAccessList failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Unable to fetch saved assignments.',
            ], 500);
        }
    }

    public function updateUserBusAssignmentStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid assignment status request.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $updated = DB::table('operator_bus_assignments')
            ->where('id', (int) $request->id)
            ->update([
                'status' => (int) $request->status,
                'updated_at' => now(),
                'updated_by' => auth()->id() ?? null,
            ]);

        if (!$updated) {
            $exists = DB::table('operator_bus_assignments')
                ->where('id', (int) $request->id)
                ->exists();

            if (!$exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'Assignment not found.',
                ], 404);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Assignment status updated successfully.',
        ]);
    }
}
