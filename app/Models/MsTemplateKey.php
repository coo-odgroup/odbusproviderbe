<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsTemplateKey extends Model
{
    protected $connection = 'mysql_scheduler';
    protected $table = 'ms_template_key';

    protected $fillable = [
        'ms_notification_type_id',
        'template_key',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function notificationType()
    {
        return $this->belongsTo(MsNotificationType::class, 'ms_notification_type_id');
    }
}
