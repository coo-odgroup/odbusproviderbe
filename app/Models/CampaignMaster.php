<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignMaster extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'campaign_master';

    protected $primaryKey = 'id';

    protected $fillable = [
        'campaign_name',
        'short_desc',
        'full_desc',
        'start',
        'stop',
        'active_status',
        'created_by',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
