<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronFrequency extends Model
{
    use HasFactory;

    protected $connection = 'mysql_scheduler';

    protected $fillable = [
        'name',
        'expression',
        'is_active',
        'created_at',
        'updated_at'
    ];

    // One frequency has many crons
    public function crons()
    {
        return $this->hasMany(Cron::class, 'frequency_id');
    }
}
