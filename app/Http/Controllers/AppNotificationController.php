<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppNotification;
use App\Models\User;

class AppNotificationController extends Controller
{

    public function list(Request $request)
    {
        $query = AppNotification::whereNull('deleted_at')
            ->with(['creator', 'updater', 'deleter']);

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


    public function create(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'message'     => 'nullable|string',
            'user_id'     => 'required|integer'
        ]);

        $validated['created_by'] = $request->user_id;

        $notification = AppNotification::create($validated);

        return response()->json([
            'status' => 1,
            'message' => 'Notification created successfully',
            'data' => $notification->load(['creator'])
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

        $validated['updated_by'] = $request->user_id;

        $notification->update($validated);

        return response()->json([
            'status' => 1,
            'message' => 'Notification updated successfully',
            'data' => $notification->load(['creator', 'updater'])
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

        $notification->deleted_by = $request->user_id;
        $notification->status = 0;
        $notification->save();
        $notification->delete();

        return response()->json([
            'status' => 1,
            'message' => 'Notification deleted and set to inactive',
            'deleted_by' => $request->user_id
        ]);
    }
}
