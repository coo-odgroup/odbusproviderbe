<?php

namespace App\Http\Controllers;

use App\Models\MsNotificationType;
use App\Models\MsTemplateKey;

class NotificationMasterController extends Controller
{
    public function getTypes()
    {
        $types = MsNotificationType::where('status', 1)->get();

        return response()->json([
            'status' => 1,
            'data' => $types
        ]);
    }

    public function getTemplateKeys($typeId)
    {
        $keys = MsTemplateKey::where('ms_notification_type_id', $typeId)
                             ->where('status', 1)
                             ->get();

        return response()->json([
            'status' => 1,
            'data' => $keys
        ]);
    }
}
