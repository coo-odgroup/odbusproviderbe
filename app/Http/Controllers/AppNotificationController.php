<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppNotification;
use App\Models\User;

class AppNotificationController extends Controller
{
    public function list(Request $request)
    {
        $query = AppNotification::query();

        if ($request->name) {
            $query->where('title', 'LIKE', '%' . $request->name . '%');
        }

        $rows = $request->rows_number ?? 10;

        $data = $query->orderBy('id', 'DESC')->paginate($rows);

        return response()->json([
            'status' => 1,
            'message' => 'Data fetched successfully',
            'data' => $data
        ]);
    }

    public function updateStatus(Request $request, $id)
{
    $notification = AppNotification::find($id);

    if (!$notification) {
        return response()->json(['status' => 0, 'message' => 'Record not found']);
    }

    $status = $request->status;

    if (!in_array($status, [0, 1])) {
        return response()->json(['status' => 0, 'message' => 'Invalid status value']);
    }

    $notification->status = $status;
    $notification->save();

    return response()->json([
        'status' => 1,
        'message' => 'Status updated successfully',
        'data' => $notification
    ]);
}


    public function create(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'message'     => 'nullable|string',
            'user_id'     => 'required|integer'
        ]);


        $userId = $request->user_id;

        $user = User::find($userId);
        $userName = $user ? $user->name : 'System';

        $validated['created_by'] = $userName;

        $notification = AppNotification::create($validated);

        return response()->json([
            'status' => 1,
            'message' => 'Notification created successfully',
            'data' => $notification
        ]);
    }


    public function update(Request $request, $id)
    {
        $notification = AppNotification::find($id);

        if (!$notification) {
            return response()->json(['status' => 0, 'message' => 'Record not found']);
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'message'     => 'nullable|string',
            'user_id'     => 'required|integer'
        ]);

        $userId = $request->user_id;
        $user = User::find($userId);
        $userName = $user ? $user->name : 'System';

        $validated['updated_by'] = $userName;

        $notification->update($validated);

        return response()->json([
            'status' => 1,
            'message' => 'Notification updated successfully',
            'data' => $notification
        ]);
    }


    public function delete(Request $request, $id)
    {
        $notification = AppNotification::find($id);

        if (!$notification) {
            return response()->json([
                'status' => 0,
                'message' => 'Record not found'
            ]);
        }

        $userId = $request->user_id;

        $user = User::find($userId);
        $deletedBy = $user ? $user->name : 'System';

        $notification->updated_by = $deletedBy;
        $notification->save();

        $notification->delete();

        return response()->json([
            'status' => 1,
            'message' => "Notification deleted by $deletedBy",
            'deleted_by' => $deletedBy
        ]);
    }
}
