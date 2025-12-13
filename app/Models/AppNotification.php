<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppNotification extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql_scheduler';
    protected $table = 'push_notification_template';

    protected $fillable = [
        'title',
        'description',
        'message',
        'created_by',
        'updated_by',
        'deleted_by',
        'status','type_id',         
        'template_key_id' 
    ];


    public $timestamps = true;

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(\App\Models\User::class, 'deleted_by');
    }
     public function type()
    {
        return $this->belongsTo(MsNotificationType::class, 'type_id');
    }

    public function templateKey()
    {
        return $this->belongsTo(MsTemplateKey::class, 'template_key_id');
    }
}
