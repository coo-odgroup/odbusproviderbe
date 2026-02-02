<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'campaign';

    protected $primaryKey = 'id';

    protected $fillable = [
        'operator_id',
        'campaign_master_id',
        'offer_type',
        'offer_value',
        'min_ticket_value',
        'services',
        'auto_renewwal',
        'validity_type',
        'start_date',
        'end_date',
        'duration_value',
        'duration_unit',
        'active_status',
        'created_by',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
