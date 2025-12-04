<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    protected $connection = 'mysql_scheduler';
    protected $table = 'push_notification';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'message',
        'created_by',
        'updated_by',
        'status'
    ];
}
