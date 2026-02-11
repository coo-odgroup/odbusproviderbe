<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignServices extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'campaign_services';

    protected $primaryKey = 'id';

    protected $fillable = [
        'campaign_id',
        'campaign_routes_id',
        'bus_id',
        'active_status',
        'created_by',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
