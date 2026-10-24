<?php

namespace App\Http\Controllers;

use App\Models\ApiVendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ManageUserController extends Controller
{

    public function getUsers(Request $request)
    {
        try {
            $users = DB::table('user')
                ->leftJoin('role', 'role.id', '=', 'user.role_id')
                ->leftJoin(
                    'bus_operator',
                    'bus_operator.id',
                    '=',
                    'user.bus_operator_id'
                )
                ->whereNotNull('user.user_type')
                ->whereNotNull('user.client_id')
                ->select(
                    'user.id',
                    'user.name',
                    'user.email',
                    'user.phone',
                    'user.user_type',
                    'user.client_id',
                    'user.role_id',
                    'user.bus_operator_id',
                    'user.status',
                    'user.created_at',
                    'user.address',
                    'user.street',
                    'user.city',
                    'user.pincode',
                    'user.state_id',
                    'role.name as role_name',
                    'bus_operator.organisation_name'
                )
                ->orderByDesc('user.id')
                ->get();

            return response()->json([
                'status' => 1,
                'message' => 'Users fetched successfully.',
                'data' => $users,
            ]);
        } catch (\Throwable $e) {
            Log::error('Manage users list failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 0,
                'message' => 'Unable to fetch users.',
            ], 500);
        }
    }
    public function getBusOperators(Request $request)
    {
        try {
            $operators = DB::table('bus_operator')
                ->select('id', 'organisation_name')
                ->where('status', 1)
                ->orderBy('organisation_name', 'ASC')
                ->get();

            return response()->json([
                'status' => 1,
                'message' => 'Bus operators fetched successfully',
                'data' => $operators
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Unable to fetch bus operators'
            ], 500);
        }
    }

    public function getRoles(Request $request)
    {
        try {
            $roles = DB::table('role')
                ->select('id', 'name')
                ->orderBy('name', 'ASC')
                ->get();

            return response()->json([
                'status' => 1,
                'message' => 'Roles fetched successfully',
                'data' => $roles
            ], 200);
        } catch (\Exception $e) {
            Log::error('Fetching roles failed: ' . $e->getMessage());

            return response()->json([
                'status' => 0,
                'message' => 'Unable to fetch roles'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        // Validate the incoming form data.
        $validator = \Illuminate\Support\Facades\Validator::make(
            $request->all(),
            [
                'bus_operator_id' => [
                    'nullable',
                    'integer',
                    'exists:bus_operator,id',
                ],
                'name' => ['required', 'string', 'max:120'],
                'email' => [
                    'required',
                    'email',
                    'max:120',
                    Rule::unique('user', 'email'),
                ],
                'client_id' => ['nullable', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:40'],
                'role_id' => ['required', 'integer', 'exists:role,id'],
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid data.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        try {
            $role = DB::table('role')
                ->where('id', $validated['role_id'])
                ->first();

            if (!$role) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Selected role was not found.',
                ], 422);
            }

            $userType = strtoupper(trim($role->name));

            $userId = DB::table('user')->insertGetId([
                'bus_operator_id' => $validated['bus_operator_id'] ?? null,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'client_id' => $validated['client_id'] ?? null,
                'phone' => $validated['phone'],
                'role_id' => $validated['role_id'],
                'user_type' => $userType,
                'password' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => 1,
                'message' => 'User created successfully.',
                'data' => [
                    'id' => $userId,
                    'bus_operator_id' => $validated['bus_operator_id'] ?? null,
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'client_id' => $validated['client_id'] ?? null,
                    'phone' => $validated['phone'],
                    'role_id' => $validated['role_id'],
                    'user_type' => $userType,
                ],
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Manage user creation failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 0,
                'message' => 'Unable to create user.',
            ], 500);
        }
    }

    public function changeStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:user,id'],
            'status' => ['required', 'integer', 'in:0,1'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid user status data.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = DB::table('user')
                ->where('id', $request->id)
                ->first();

            if (!$user) {
                return response()->json([
                    'status' => 0,
                    'message' => 'User not found.',
                ], 404);
            }

            DB::table('user')
                ->where('id', $request->id)
                ->update([
                    'status' => (int) $request->status,
                    'updated_at' => now(),
                ]);

            return response()->json([
                'status' => 1,
                'message' => 'User status updated successfully.',
                'data' => [
                    'id' => (int) $request->id,
                    'status' => (int) $request->status,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('User status update failed', [
                'user_id' => $request->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 0,
                'message' => 'Unable to update user status.',
            ], 500);
        }
    }

    public function getStates(Request $request)
    {
        try {

            $states = DB::table('state')
                ->select(
                    'id',
                    'state_name'
                )
                ->where('status', 1)
                ->orderBy('state_name', 'ASC')
                ->get();

            return response()->json([
                'status' => 1,
                'message' => 'States fetched successfully',
                'data' => $states
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateUserAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'       => 'required|integer|exists:user,id',
            'address'  => 'nullable|string|max:255',
            'street'   => 'nullable|string|max:255',
            'city'     => 'nullable|string|max:100',
            'pincode'  => 'nullable|string|max:20',
            'state_id' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 0,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Verify the selected user exists.
        $user = DB::table('user')
            ->where('id', $request->id)
            ->first();

        if (!$user) {
            return response()->json([
                'status'  => 0,
                'message' => 'User not found.',
            ], 404);
        }

        // Save the address against the selected user ID.
        DB::table('user')
            ->where('id', $request->id)
            ->update([
                'address'    => $request->input('address'),
                'street'     => $request->input('street'),
                'city'       => $request->input('city'),
                'pincode'    => $request->input('pincode'),
                'state_id'   => $request->input('state_id'),
                'updated_at' => now(),
            ]);

        return response()->json([
            'status'  => 1,
            'message' => 'User address updated successfully.',
            'data'    => [
                'id'       => (int) $request->id,
                'address'  => $request->input('address'),
                'street'   => $request->input('street'),
                'city'     => $request->input('city'),
                'pincode'  => $request->input('pincode'),
                'state_id' => $request->input('state_id'),
            ],
        ]);
    }
}
