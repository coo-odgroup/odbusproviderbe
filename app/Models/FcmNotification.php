<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FcmNotification extends Model
{
    use HasFactory;
    protected $table = 'scheduler.notifications';
    protected $fillable = [
        'customer_id',
        'device_id',
        'template_id',
        'title',
        'message',
        'link',
        'data_payload',
        'booking_id',
        'src',
        'destination',
        'notification_type',
        'status',
        'fcm_message_id',
        'error_code',
        'error_message',
        'scheduled_at',
        'sent_at',
        'delivered_at',
    ];

}
