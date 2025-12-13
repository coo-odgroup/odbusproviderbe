<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 

class MsNotificationType extends Model
{
    protected $connection = 'mysql_scheduler';
    protected $table = 'ms_notification_type';

    protected $fillable = [
        'type',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function templateKeys()
    {
        return $this->hasMany(MsTemplateKey::class, 'ms_notification_type_id');
    }
}
