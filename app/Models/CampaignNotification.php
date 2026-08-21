<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class CampaignNotification extends Model
{
    use HasFactory;

    protected $table = 'notification_campaigns';
    protected $primaryKey = 'id';

    protected $fillable = [
        'notification_category_id',
        'campaign_name',
        'title',
        'message',
        'image',
        'type',
        'target_type',
        'schedule_type',
        'schedule_minutes',
        'schedule_at',
        'active_user_duration',
        'active_status',
        'total_users',
        'processed_users',
        'success_users',
        'failed_users',
        'is_completed',
        'started_at',
        'completed_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
