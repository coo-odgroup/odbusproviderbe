<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

    public function sendNotification(Request $request) {
        $cur_date = date('Y-m-d');
        $cur_time = date('H:i:s');
        $bookings = DB::table('booking as b')
        ->join('users as u', 'u.id', '=', 'b.users_id')
        ->join('location as sl', 'sl.id', '=', 'b.source_id')
        ->join('location as dl', 'dl.id', '=', 'b.destination_id')
        ->join('bus as bus', 'bus.id','=','b.bus_id')
        ->select(
            'b.id',
            'b.boarding_time',
            'b.dropping_time',
            'b.journey_dt',
            'b.pnr',
            'b.boarding_point',
            'b.dropping_point',

            'u.name as user_name',

            'sl.name as source_name',
            'dl.name as destination_name',

            'bus.bus_number',
            'bus.name as bus_name',
        )
        ->where('b.journey_dt', $cur_date)
        ->whereRaw(
            "TIME(?) BETWEEN DATE_SUB(TIME(b.boarding_time), INTERVAL 1 HOUR) AND TIME(b.boarding_time)",
            [$cur_time]
        )
        ->get();

        // return $bookings;
        $tempData = [];

        foreach ($bookings as $b) {
            $data = [
                'ROUTENAME'     => $b->source_name . ' to ' . $b->destination_name,
                'BUSNAME'       => $b->bus_name,
                'NUMBER'        => $b->bus_number,
                'TIME'          => $b->boarding_time,
                'BOARDINGPOINT' => $b->boarding_point
            ];

            $tempData[] = $this->getTemp($data);
        }

        return $tempData;
    }

    public function getTemp($data)
    {
        $templateData = DB::table('scheduler.push_notification_template')
        ->where('id', 26)
        ->get();

        $template = $templateData[0]->message;

        foreach ($data as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }

        return $template;
    }
}
