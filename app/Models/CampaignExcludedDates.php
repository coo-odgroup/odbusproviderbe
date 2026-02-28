<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignExcludedDates extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'campaign_excluded_dates';

    protected $primaryKey = 'id';

    protected $fillable = [
        'campaign_id',
        'excluded_date',
        'created_by',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
}
