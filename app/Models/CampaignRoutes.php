<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignRoutes extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'campaign_routes';

    protected $primaryKey = 'id';

    protected $fillable = [
        'campaign_id',
        'src_id',
        'dest_id',
        'active_status',
        'created_by',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    public function source()
    {
        return $this->belongsTo(Location::class, 'src_id');
    }

    public function destination()
    {
        return $this->belongsTo(Location::class, 'dest_id');
    }
}
