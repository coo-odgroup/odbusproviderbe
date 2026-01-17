<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronLog extends Model
{
    use HasFactory;

    protected $connection = 'mysql_scheduler';

    protected $fillable = [
        'cron_id',
        'run_type',
        'status',
        'started_at',
        'ended_at',
        'execution_time_ms',
        'output',
        'error',
        'created_at'
    ];

    // Log belongs to one cron
    public function cron()
    {
        return $this->belongsTo(Cron::class, 'cron_id');
    }
}
