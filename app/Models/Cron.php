<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cron extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql_scheduler';

    protected $fillable = [
        'name',
        'command',
        'frequency_id',
        'run_type',
        'is_active',
        'last_run_at',
        'next_run_at',
        'created_at',
        'updated_at'
    ];

    // Cron belongs to one frequency
    public function frequency()
    {
        return $this->belongsTo(CronFrequency::class, 'frequency_id');
    }

    // Cron has many logs
    public function logs()
    {
        return $this->hasMany(CronLog::class, 'cron_id');
    }

    // Latest log (optional helper)
    public function latestLog()
    {
        return $this->hasOne(CronLog::class, 'cron_id')->latestOfMany();
    }
}
