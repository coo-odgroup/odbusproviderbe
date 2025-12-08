<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppNotification;

class AppNotificationController extends Controller
{
    public function list(Request $request)
    {
        $query = AppNotification::with([
            'creator',
            'updater',
            'deleter',
            'type',           
            'templateKey'     
        ])->whereNull('deleted_at');

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
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'message' => 'required|string',
        'type_id' => 'required|integer|exists:mysql_scheduler.ms_notification_type,id',
        'template_key_id' => 'required|integer|exists:mysql_scheduler.ms_template_key,id',
        'user_id' => 'required|integer'
    ]);

    $validated['created_by'] = $request->user_id;
    $validated['status'] = 1;

    unset($validated['user_id']); 

    $notification = AppNotification::create($validated);

    return response()->json([
        'status' => 1,
        'message' => 'Notification created successfully',
        'data' => $notification->load(['creator', 'type', 'templateKey'])
    ]);
}

public function toggleStatus($id)
{
    $notification = AppNotification::find($id);

    if (!$notification) {
        return response()->json([
            'status' => 0,
            'message' => 'Record not found'
        ]);
    }

    
    $notification->status = $notification->status == 1 ? 0 : 1;
    $notification->updated_by = request()->user_id ?? null;
    
    $notification->save();

    return response()->json([
        'status' => 1,
        'message' => 'Status updated successfully',
        'new_status' => $notification->status,
    ]);
}
public function updateStatus(Request $request, $id)
{
    $notification = AppNotification::find($id);

    if (!$notification) {
        return response()->json([
            'status' => 0,
            'message' => 'Record not found'
        ]);
    }

 
    $notification->status = $request->status;
    $notification->updated_by = $request->user_id ?? null;

    $notification->save();

    return response()->json([
        'status' => 1,
        'message' => 'Status updated successfully',
        'new_status' => $notification->status
    ]);
}


public function update(Request $request, $id)
{
    $notification = AppNotification::find($id);

    if (!$notification) {
        return response()->json(['status' => 0, 'message' => 'Record not found']);
    }

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'message' => 'required|string',
        'type_id' => 'required|integer|exists:mysql_scheduler.ms_notification_type,id',
        'template_key_id' => 'required|integer|exists:mysql_scheduler.ms_template_key,id',
        'user_id' => 'required|integer'
    ]);

    $validated['updated_by'] = $request->user_id;
    unset($validated['user_id']);

    $notification->update($validated);

    return response()->json([
        'status' => 1,
        'message' => 'Notification updated successfully',
        'data' => $notification->load(['creator', 'updater', 'type', 'templateKey'])
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
